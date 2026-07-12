<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Database;
use App\Core\View;
use App\Models\Payment;
use App\Models\Member;
use App\Models\AuditLog;
use App\Helpers\NumberGenerator;
use App\Helpers\Mailer;
use App\Helpers\PdfGenerator;
use App\Helpers\PaymentMethod;

class PaymentController extends Controller
{
    private const PAYMENT_TYPES = ['registration', 'renewal', 'annual', 'donation', 'other'];

    public function index(): void
    {
        $filters = [
            'status' => $this->input('status', ''),
            'from_date' => $this->input('from_date', ''),
            'to_date' => $this->input('to_date', ''),
            'search' => $this->input('search', ''),
        ];
        $page = max(1, (int) $this->input('page', 1));
        $payments = Payment::getAll($filters, $page);
        $stats = Payment::getRevenueStats();
        $outstanding = Payment::getOutstandingMembers(10);
        $this->view('payments/index', compact('payments', 'filters', 'stats', 'outstanding'));
    }

    public function createForm(): void
    {
        $members = Database::fetchAll(
            "SELECT id, membership_number, full_name_english, status
             FROM members
             WHERE status IN ('active','pending','payment_verified','expired','suspended')
             ORDER BY full_name_english ASC
             LIMIT 500"
        );
        $selectedMemberId = (int) $this->input('member_id', 0);
        $this->view('payments/create', compact('members', 'selectedMemberId'));
    }

    public function store(): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $memberId = (int) $this->input('member_id', 0);
        $amount = (float) $this->input('amount', 0);
        $method = PaymentMethod::normalize((string) $this->input('payment_method', ''));
        $paymentType = strtolower(trim((string) $this->input('payment_type', 'other')));
        $paymentDate = Security::sanitize((string) $this->input('payment_date', date('Y-m-d')));
        $transactionNumber = Security::sanitize((string) $this->input('transaction_number', ''));
        $notes = Security::sanitize((string) $this->input('notes', ''));
        $autoVerify = $this->input('auto_verify', '0') === '1';

        if ($memberId <= 0 || !Member::findById($memberId)) {
            $this->json(['success' => false, 'message' => 'Please select a valid member.'], 422);
        }
        if ($amount <= 0) {
            $this->json(['success' => false, 'message' => 'Amount must be greater than zero.'], 422);
        }
        if ($method === null) {
            $this->json(['success' => false, 'message' => 'Invalid payment method.'], 422);
        }
        if (!in_array($paymentType, self::PAYMENT_TYPES, true)) {
            $paymentType = 'other';
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $paymentDate)) {
            $paymentDate = date('Y-m-d');
        }

        $noteParts = ['Type: ' . $paymentType];
        if ($notes !== '') {
            $noteParts[] = $notes;
        }

        $paymentId = Payment::create([
            'member_id' => $memberId,
            'amount' => $amount,
            'payment_method' => $method,
            'transaction_number' => $transactionNumber !== '' ? $transactionNumber : null,
            'payment_date' => $paymentDate,
            'status' => $autoVerify ? 'verified' : 'pending',
            'notes' => implode(' | ', $noteParts),
            'created_by' => Auth::id(),
            'verified_by' => $autoVerify ? Auth::id() : null,
            'verified_at' => $autoVerify ? date('Y-m-d H:i:s') : null,
        ]);

        $receiptNumber = null;
        if ($autoVerify) {
            $receiptNumber = NumberGenerator::receiptNumber();
            Database::insert('payment_receipts', [
                'payment_id' => $paymentId,
                'receipt_number' => $receiptNumber,
                'member_id' => $memberId,
                'amount' => $amount,
                'issued_by' => Auth::id(),
            ]);
            $this->maybeUpdateMemberAfterPayment($memberId);
        }

        AuditLog::log('create_payment', 'payments', $paymentId, null, [
            'amount' => $amount,
            'payment_type' => $paymentType,
            'auto_verify' => $autoVerify,
        ]);

        $this->json([
            'success' => true,
            'message' => $autoVerify ? 'Payment recorded and verified.' : 'Payment recorded.',
            'payment_id' => $paymentId,
            'receipt_number' => $receiptNumber,
        ]);
    }

    public function verify(string $id): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $payment = Payment::findById((int) $id);
        if (!$payment) {
            $this->json(['success' => false, 'message' => 'Payment not found.'], 404);
        }
        if ($payment['status'] === 'verified') {
            $this->json(['success' => false, 'message' => 'Payment already verified.'], 422);
        }

        Payment::update((int) $id, [
            'status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => date('Y-m-d H:i:s'),
        ]);

        $existingReceipt = Database::fetch(
            "SELECT id, receipt_number FROM payment_receipts WHERE payment_id = ?",
            [(int) $id]
        );

        if ($existingReceipt) {
            $receiptNumber = $existingReceipt['receipt_number'];
            $receiptId = (int) $existingReceipt['id'];
        } else {
            $receiptNumber = NumberGenerator::receiptNumber();
            $receiptId = Database::insert('payment_receipts', [
                'payment_id' => (int) $id,
                'receipt_number' => $receiptNumber,
                'member_id' => $payment['member_id'],
                'amount' => $payment['amount'],
                'issued_by' => Auth::id(),
            ]);
        }

        $this->maybeUpdateMemberAfterPayment((int) $payment['member_id']);

        AuditLog::log('verify_payment', 'payments', (int) $id);

        $member = Member::findById((int) $payment['member_id']);
        if ($member && !empty($member['email'])) {
            Mailer::sendTemplate($member['email'], 'payment_verified', [
                'full_name' => $member['full_name_english'],
                'amount' => number_format((float) $payment['amount'], 2),
                'receipt_number' => $receiptNumber,
            ], $member['full_name_english'], [
                'related_type' => 'payments',
                'related_id' => (int) $id,
            ]);
        }

        $this->json([
            'success' => true,
            'message' => 'Payment verified.',
            'receipt_number' => $receiptNumber,
            'receipt_id' => $receiptId,
        ]);
    }

    public function reject(string $id): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $payment = Payment::findById((int) $id);
        if (!$payment) {
            $this->json(['success' => false, 'message' => 'Payment not found.'], 404);
        }
        if ($payment['status'] === 'verified') {
            $this->json(['success' => false, 'message' => 'Cannot reject a verified payment.'], 422);
        }

        $reason = Security::sanitize((string) $this->input('reason', ''));
        $notes = trim((string) ($payment['notes'] ?? ''));
        if ($reason !== '') {
            $notes = ($notes !== '' ? $notes . ' | ' : '') . 'Rejected: ' . $reason;
        }

        Payment::update((int) $id, [
            'status' => 'rejected',
            'notes' => $notes !== '' ? $notes : null,
        ]);

        AuditLog::log('reject_payment', 'payments', (int) $id, null, ['reason' => $reason]);

        $this->json(['success' => true, 'message' => 'Payment rejected.']);
    }

    public function receipt(string $id): void
    {
        $receipt = Database::fetch(
            "SELECT pr.*, m.full_name_english, m.membership_number, m.mobile, m.membership_type_id,
                    mt.name as membership_type_name, mt.slug as membership_type_slug,
                    p.payment_method, p.payment_date, p.amount as payment_amount, p.notes
             FROM payment_receipts pr
             JOIN members m ON pr.member_id = m.id
             JOIN payments p ON pr.payment_id = p.id
             LEFT JOIN membership_types mt ON mt.id = m.membership_type_id
             WHERE pr.id = ?",
            [(int) $id]
        );

        if (!$receipt) {
            http_response_code(404);
            View::render('errors/404');
            return;
        }

        $html = $this->renderReceiptHtml($receipt);
        $filename = $receipt['receipt_number'] . '.pdf';
        $path = PdfGenerator::generateFromHtml($html, $filename, 'receipts');

        Database::update('payment_receipts', ['pdf_path' => $path], 'id = ?', [(int) $id]);
        AuditLog::log('download_receipt', 'payment_receipts', (int) $id);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile(\App\Core\App::config('app.upload_path') . '/' . $path);
        exit;
    }

    /**
     * Create a verified payment from an approved application (called from ApplicationController).
     */
    public static function createFromApplication(int $memberId, array $application, int $applicationId): ?int
    {
        $amount = (float) ($application['amount_paid'] ?? 0);
        if ($amount <= 0) {
            return null;
        }

        $method = PaymentMethod::normalize((string) ($application['payment_method'] ?? '')) ?? PaymentMethod::DEFAULT;
        $paymentDate = $application['payment_date'] ?? date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $paymentDate)) {
            $paymentDate = date('Y-m-d');
        }

        $paymentId = Payment::create([
            'member_id' => $memberId,
            'application_id' => $applicationId,
            'amount' => $amount,
            'payment_method' => $method,
            'transaction_number' => $application['transaction_number'] ?? null,
            'payment_date' => $paymentDate,
            'status' => 'verified',
            'notes' => 'Type: registration | From application approval',
            'created_by' => Auth::id(),
            'verified_by' => Auth::id(),
            'verified_at' => date('Y-m-d H:i:s'),
        ]);

        Database::insert('payment_receipts', [
            'payment_id' => $paymentId,
            'receipt_number' => NumberGenerator::receiptNumber(),
            'member_id' => $memberId,
            'amount' => $amount,
            'issued_by' => Auth::id(),
        ]);

        AuditLog::log('create_payment', 'payments', $paymentId, null, [
            'source' => 'application_approve',
            'application_id' => $applicationId,
        ]);

        return $paymentId;
    }

    private function maybeUpdateMemberAfterPayment(int $memberId): void
    {
        $member = Member::findById($memberId);
        if (!$member) {
            return;
        }

        $status = $member['status'] ?? '';
        // Never overwrite active / suspended / expired membership status
        if (in_array($status, ['pending', 'under_review', 'payment_verified'], true)) {
            Member::update($memberId, ['status' => 'payment_verified']);
        }
    }

    private function renderReceiptHtml(array $receipt): string
    {
        $typeLabel = \App\Helpers\MembershipType::bilingualLabel(
            $receipt['membership_type_name'] ?? '',
            $receipt['membership_type_slug'] ?? null
        );

        return '<html><body style="font-family:DejaVu Sans,sans-serif;padding:40px;">
            <h2 style="text-align:center;">Payment Receipt</h2>
            <p><strong>Receipt No:</strong> ' . htmlspecialchars($receipt['receipt_number']) . '</p>
            <p><strong>Date:</strong> ' . htmlspecialchars($receipt['issued_at'] ?? '') . '</p>
            <hr>
            <p><strong>Member:</strong> ' . htmlspecialchars($receipt['full_name_english']) . '</p>
            <p><strong>Membership No:</strong> ' . htmlspecialchars($receipt['membership_number']) . '</p>
            <p><strong>Membership Type:</strong> ' . htmlspecialchars($typeLabel) . '</p>
            <p><strong>Amount:</strong> Rs. ' . number_format((float) $receipt['amount'], 2) . '</p>
            <p><strong>Payment Method:</strong> ' . htmlspecialchars(PaymentMethod::display($receipt['payment_method'] ?? '')) . '</p>
            <p><strong>Payment Date:</strong> ' . htmlspecialchars($receipt['payment_date'] ?? '') . '</p>
            <hr>
            <p style="text-align:center;color:#666;">Kilinochchi / Thiruvaiyaru Maha Vidyalayam OSA</p>
        </body></html>';
    }
}
