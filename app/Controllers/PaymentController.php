<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Database;
use App\Models\Payment;
use App\Models\Member;
use App\Models\AuditLog;
use App\Helpers\NumberGenerator;
use App\Helpers\Mailer;
use App\Helpers\PdfGenerator;
use App\Helpers\PaymentMethod;

class PaymentController extends Controller
{
    public function index(): void
    {
        $filters = [
            'status' => $this->input('status', ''),
            'from_date' => $this->input('from_date', ''),
            'to_date' => $this->input('to_date', ''),
        ];
        $page = (int) $this->input('page', 1);
        $payments = Payment::getAll($filters, $page);
        $stats = Payment::getRevenueStats();
        $this->view('payments/index', compact('payments', 'filters', 'stats'));
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

        Payment::update((int) $id, [
            'status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => date('Y-m-d H:i:s'),
        ]);

        $receiptNumber = NumberGenerator::receiptNumber();
        Database::insert('payment_receipts', [
            'payment_id' => (int) $id,
            'receipt_number' => $receiptNumber,
            'member_id' => $payment['member_id'],
            'amount' => $payment['amount'],
            'issued_by' => Auth::id(),
        ]);

        Member::update($payment['member_id'], ['status' => 'payment_verified']);

        AuditLog::log('verify_payment', 'payments', (int) $id);

        $member = Member::findById($payment['member_id']);
        if ($member && !empty($member['email'])) {
            Mailer::sendTemplate($member['email'], 'payment_verified', [
                'full_name' => $member['full_name_english'],
                'amount' => number_format($payment['amount'], 2),
                'receipt_number' => $receiptNumber,
            ], $member['full_name_english'], [
                'related_type' => 'payments',
                'related_id' => (int) $id,
            ]);
        }

        $this->json(['success' => true, 'message' => 'Payment verified.', 'receipt_number' => $receiptNumber]);
    }

    public function receipt(string $id): void
    {
        $receipt = Database::fetch(
            "SELECT pr.*, m.full_name_english, m.membership_number, m.mobile, p.payment_method, p.payment_date
             FROM payment_receipts pr
             JOIN members m ON pr.member_id = m.id
             JOIN payments p ON pr.payment_id = p.id
             WHERE pr.id = ?",
            [(int) $id]
        );

        if (!$receipt) {
            http_response_code(404);
            return;
        }

        $html = $this->renderReceiptHtml($receipt);
        $filename = $receipt['receipt_number'] . '.pdf';
        $path = PdfGenerator::generateFromHtml($html, $filename, 'receipts');

        Database::update('payment_receipts', ['pdf_path' => $path], 'id = ?', [(int) $id]);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile(\App\Core\App::config('app.upload_path') . '/' . $path);
        exit;
    }

    private function renderReceiptHtml(array $receipt): string
    {
        return '<html><body style="font-family:DejaVu Sans,sans-serif;padding:40px;">
            <h2 style="text-align:center;">Payment Receipt</h2>
            <p><strong>Receipt No:</strong> ' . htmlspecialchars($receipt['receipt_number']) . '</p>
            <p><strong>Date:</strong> ' . htmlspecialchars($receipt['issued_at']) . '</p>
            <hr>
            <p><strong>Member:</strong> ' . htmlspecialchars($receipt['full_name_english']) . '</p>
            <p><strong>Membership No:</strong> ' . htmlspecialchars($receipt['membership_number']) . '</p>
            <p><strong>Amount:</strong> Rs. ' . number_format($receipt['amount'], 2) . '</p>
            <p><strong>Payment Method:</strong> ' . htmlspecialchars(PaymentMethod::display($receipt['payment_method'] ?? '')) . '</p>
            <p><strong>Payment Date:</strong> ' . htmlspecialchars($receipt['payment_date']) . '</p>
            <hr>
            <p style="text-align:center;color:#666;">Kilinochchi / Thiruvaiyaru Maha Vidyalayam OSA</p>
        </body></html>';
    }
}
