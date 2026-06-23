<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Member;
use App\Models\Payment;
use App\Models\MembershipCard;
use App\Helpers\QrGenerator;
use App\Helpers\PdfGenerator;

class CardController extends Controller
{
    public function show(string $memberId): void
    {
        $member = Member::findById((int) $memberId);
        if (!$member) {
            http_response_code(404);
            return;
        }

        $card = MembershipCard::findByMemberId((int) $memberId);
        if (!$card) {
            $card = $this->generateCard($member);
        }

        $this->view('cards/show', compact('member', 'card'));
    }

    public function downloadPdf(string $memberId): void
    {
        $member = Member::findById((int) $memberId);
        if (!$member) {
            http_response_code(404);
            return;
        }

        $card = MembershipCard::findByMemberId((int) $memberId) ?? $this->generateCard($member);
        $html = $this->renderCardHtml($member, $card);
        $filename = $member['membership_number'] . '_card.pdf';
        $path = PdfGenerator::generateFromHtml($html, $filename, 'cards');

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile(\App\Core\App::config('app.upload_path') . '/' . $path);
        exit;
    }

    public function downloadImage(string $memberId): void
    {
        $member = Member::findById((int) $memberId);
        if (!$member) {
            http_response_code(404);
            return;
        }

        $card = MembershipCard::findByMemberId((int) $memberId) ?? $this->generateCard($member);
        $qrPath = \App\Core\App::config('app.upload_path') . '/' . $card['qr_code_path'];

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="' . $member['membership_number'] . '_qr.png"');
        readfile($qrPath);
        exit;
    }

    private function generateCard(array $member): array
    {
        $qrData = json_encode([
            'membership_number' => $member['membership_number'],
            'name' => $member['full_name_english'],
            'type' => $member['membership_type_name'] ?? '',
            'expiry' => $member['membership_expiry_date'],
            'verify_url' => rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/verify/' . $member['membership_number'],
        ]);

        $qrFilename = $member['membership_number'] . '.png';
        $qrPath = QrGenerator::generate($qrData, $qrFilename);

        $cardId = MembershipCard::create([
            'member_id' => $member['id'],
            'card_number' => $member['membership_number'],
            'qr_code_data' => $qrData,
            'qr_code_path' => $qrPath,
            'expires_at' => $member['membership_expiry_date'],
        ]);

        return [
            'id' => $cardId,
            'qr_code_path' => $qrPath,
            'qr_code_data' => $qrData,
        ];
    }

    private function renderCardHtml(array $member, array $card): string
    {
        $qrFullPath = \App\Core\App::config('app.upload_path') . '/' . $card['qr_code_path'];
        $qrBase64 = base64_encode(file_get_contents($qrFullPath));
        $photoHtml = '';
        if ($member['photo']) {
            $photoPath = \App\Core\App::config('app.upload_path') . '/' . $member['photo'];
            if (file_exists($photoPath)) {
                $photoBase64 = base64_encode(file_get_contents($photoPath));
                $photoHtml = '<img src="data:image/jpeg;base64,' . $photoBase64 . '" style="width:80px;height:80px;border-radius:50%;object-fit:cover;">';
            }
        }

        return '<html><body style="font-family:DejaVu Sans,sans-serif;margin:0;padding:20px;">
            <div style="max-width:400px;margin:0 auto;border:2px solid #1a5276;border-radius:15px;overflow:hidden;">
                <div style="background:#1a5276;color:white;padding:15px;text-align:center;">
                    <h3 style="margin:0;">Kilinochchi / Thiruvaiyaru Maha Vidyalayam</h3>
                    <p style="margin:5px 0 0;">Old Students\' Association</p>
                </div>
                <div style="padding:20px;text-align:center;">
                    ' . $photoHtml . '
                    <h2 style="margin:10px 0;color:#1a5276;">' . htmlspecialchars($member['full_name_english']) . '</h2>
                    <p style="font-size:18px;font-weight:bold;color:#2c3e50;">' . htmlspecialchars($member['membership_number']) . '</p>
                    <p>' . htmlspecialchars($member['membership_type_name'] ?? '') . '</p>
                    <p>Valid Until: ' . htmlspecialchars($member['membership_expiry_date'] ?? 'N/A') . '</p>
                    <img src="data:image/png;base64,' . $qrBase64 . '" style="width:120px;height:120px;margin-top:10px;">
                </div>
            </div>
        </body></html>';
    }

    public function verify(string $number): void
    {
        $member = Member::findByNumber($number);
        if (!$member || $member['status'] !== 'active') {
            $this->json(['valid' => false, 'message' => 'Invalid or inactive membership.']);
        }

        $this->json([
            'valid' => true,
            'member' => [
                'name' => $member['full_name_english'],
                'membership_number' => $member['membership_number'],
                'status' => $member['status'],
                'expiry' => $member['membership_expiry_date'],
            ],
        ]);
    }
}
