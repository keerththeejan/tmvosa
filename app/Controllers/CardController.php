<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\View;
use App\Models\Member;
use App\Models\MembershipCard;
use App\Models\AuditLog;
use App\Helpers\QrGenerator;
use App\Helpers\PdfGenerator;

class CardController extends Controller
{
    public function index(): void
    {
        $search = trim((string) $this->input('search', ''));
        $page = max(1, (int) $this->input('page', 1));
        $filters = ['status' => 'active'];
        if ($search !== '') {
            $filters['search'] = $search;
        }
        $members = Member::search($filters, $page, 20);
        $this->view('cards/index', compact('members', 'search'));
    }

    public function show(string $memberId): void
    {
        $member = Member::findById((int) $memberId);
        if (!$member) {
            http_response_code(404);
            View::render('errors/404');
            return;
        }

        $card = MembershipCard::findByMemberId((int) $memberId);
        if (!$card) {
            $card = $this->generateCard($member);
            AuditLog::log('issue_card', 'membership_cards', (int) ($card['id'] ?? 0), null, [
                'member_id' => (int) $memberId,
            ]);
        } else {
            $card = $this->ensureUrlQr($member, $card);
        }

        $this->view('cards/show', compact('member', 'card'));
    }

    public function downloadPdf(string $memberId): void
    {
        $member = Member::findById((int) $memberId);
        if (!$member) {
            http_response_code(404);
            View::render('errors/404');
            return;
        }

        $card = MembershipCard::findByMemberId((int) $memberId) ?? $this->generateCard($member);
        $card = $this->ensureUrlQr($member, $card);
        $html = $this->renderCardHtml($member, $card);
        $filename = $member['membership_number'] . '_card.pdf';
        $path = PdfGenerator::generateFromHtml($html, $filename, 'cards');

        if (!empty($card['id'])) {
            MembershipCard::update((int) $card['id'], ['pdf_path' => $path]);
        }

        AuditLog::log('download_card_pdf', 'membership_cards', (int) ($card['id'] ?? 0));

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
            View::render('errors/404');
            return;
        }

        $card = MembershipCard::findByMemberId((int) $memberId) ?? $this->generateCard($member);
        $card = $this->ensureUrlQr($member, $card);
        $qrPath = \App\Core\App::config('app.upload_path') . '/' . $card['qr_code_path'];

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="' . $member['membership_number'] . '_qr.png"');
        readfile($qrPath);
        exit;
    }

    public function bulkPrint(): void
    {
        if (!$this->validateCsrf()) {
            http_response_code(403);
            echo 'Invalid request';
            return;
        }

        $ids = $this->input('member_ids', []);
        if (!is_array($ids) || empty($ids)) {
            http_response_code(422);
            echo 'No members selected';
            return;
        }

        $cards = [];
        foreach ($ids as $id) {
            $member = Member::findById((int) $id);
            if (!$member) {
                continue;
            }
            $card = MembershipCard::findByMemberId((int) $id) ?? $this->generateCard($member);
            $card = $this->ensureUrlQr($member, $card);
            $cards[] = ['member' => $member, 'card' => $card];
        }

        AuditLog::log('bulk_print_cards', 'membership_cards', null, null, ['count' => count($cards)]);
        $this->view('cards/bulk-print', compact('cards'));
    }

    private function generateCard(array $member): array
    {
        $verifyUrl = \App\Helpers\VerifyUrl::forMembershipNumber((string) $member['membership_number']);
        $qrFilename = $member['membership_number'] . '.png';
        $qrPath = QrGenerator::generate($verifyUrl, $qrFilename);

        $cardId = MembershipCard::create([
            'member_id' => $member['id'],
            'card_number' => $member['membership_number'],
            'qr_code_data' => $verifyUrl,
            'qr_code_path' => $qrPath,
            'expires_at' => $member['membership_expiry_date'],
        ]);

        return [
            'id' => $cardId,
            'qr_code_path' => $qrPath,
            'qr_code_data' => $verifyUrl,
            'card_number' => $member['membership_number'],
            'issued_at' => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Re-encode QR as absolute verify URL when legacy JSON (or stale) data is stored.
     */
    private function ensureUrlQr(array $member, array $card): array
    {
        $verifyUrl = \App\Helpers\VerifyUrl::forMembershipNumber((string) $member['membership_number']);
        $current = trim((string) ($card['qr_code_data'] ?? ''));

        if ($current === $verifyUrl && !empty($card['qr_code_path'])) {
            $full = \App\Core\App::config('app.upload_path') . '/' . $card['qr_code_path'];
            if (is_file($full)) {
                return $card;
            }
        }

        $qrFilename = $member['membership_number'] . '.png';
        $qrPath = QrGenerator::generate($verifyUrl, $qrFilename);

        if (!empty($card['id'])) {
            MembershipCard::update((int) $card['id'], [
                'qr_code_data' => $verifyUrl,
                'qr_code_path' => $qrPath,
            ]);
        }

        $card['qr_code_data'] = $verifyUrl;
        $card['qr_code_path'] = $qrPath;

        return $card;
    }

    private function renderCardHtml(array $member, array $card): string
    {
        $qrFullPath = \App\Core\App::config('app.upload_path') . '/' . $card['qr_code_path'];
        $qrBase64 = base64_encode(file_get_contents($qrFullPath));
        $photoHtml = '';
        if (!empty($member['photo'])) {
            $photoPath = \App\Core\App::config('app.upload_path') . '/' . $member['photo'];
            if (file_exists($photoPath)) {
                $photoBase64 = base64_encode(file_get_contents($photoPath));
                $photoHtml = '<img src="data:image/jpeg;base64,' . $photoBase64 . '" style="width:80px;height:80px;border-radius:50%;object-fit:cover;">';
            }
        }

        $typeDisplay = \App\Helpers\Lang::membershipDisplayFromSlug(
            $member['membership_type_slug'] ?? \App\Helpers\MembershipType::slugFromName($member['membership_type_name'] ?? ''),
            null,
            $member['membership_type_name'] ?? null
        );

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
                    <p>' . htmlspecialchars($typeDisplay['title_en']) . '</p>
                    <p>(' . htmlspecialchars($typeDisplay['title_ta']) . ')</p>
                    <p>Valid Until: ' . htmlspecialchars($member['membership_expiry_date'] ?? 'N/A') . '</p>
                    <img src="data:image/png;base64,' . $qrBase64 . '" style="width:120px;height:120px;margin-top:10px;">
                </div>
            </div>
        </body></html>';
    }

    public function verify(string $number): void
    {
        $number = rawurldecode($number);
        $member = Member::findByNumber($number);

        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        $wantsJson = str_contains($accept, 'application/json')
            || ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';

        if (!$member) {
            if ($wantsJson) {
                $this->json(['valid' => false, 'message' => 'Member not found.', 'number' => $number], 404);
            }
            $this->view('cards/verify', [
                'found' => false,
                'verified' => false,
                'statusKey' => 'not_found',
                'member' => null,
                'number' => $number,
                'photoDataUri' => null,
                'membershipDisplay' => null,
            ]);
            return;
        }

        $statusKey = $this->resolvePublicStatus($member);
        $verified = $statusKey === 'active';
        $membershipDisplay = \App\Helpers\Lang::membershipDisplayFromSlug(
            $member['membership_type_slug'] ?? \App\Helpers\MembershipType::slugFromName($member['membership_type_name'] ?? ''),
            null,
            $member['membership_type_name'] ?? null
        );

        $photoDataUri = $this->publicPhotoDataUri($member['photo'] ?? null);

        if ($wantsJson) {
            $this->json([
                'valid' => $verified,
                'status' => $statusKey,
                'member' => [
                    'name' => $member['full_name_english'],
                    'membership_number' => $member['membership_number'],
                    'status' => $statusKey,
                    'expiry' => $member['membership_expiry_date'],
                    'type' => $membershipDisplay['title_en'] ?? ($member['membership_type_name'] ?? ''),
                ],
            ], $verified ? 200 : 200);
            return;
        }

        $this->view('cards/verify', [
            'found' => true,
            'verified' => $verified,
            'statusKey' => $statusKey,
            'member' => $member,
            'number' => $number,
            'photoDataUri' => $photoDataUri,
            'membershipDisplay' => $membershipDisplay,
        ]);
    }

    private function resolvePublicStatus(array $member): string
    {
        $status = strtolower((string) ($member['status'] ?? ''));
        $expiry = $member['membership_expiry_date'] ?? null;

        if ($status === 'suspended') {
            return 'suspended';
        }

        if ($status === 'expired' || ($expiry && $expiry < date('Y-m-d'))) {
            return 'expired';
        }

        if ($status === 'active') {
            return 'active';
        }

        // pending / approved / etc. — treat as not a verified active card scan target
        return $status !== '' ? $status : 'not_found';
    }

    private function publicPhotoDataUri(?string $photoPath): ?string
    {
        if ($photoPath === null || $photoPath === '') {
            return null;
        }

        $full = \App\Core\App::config('app.upload_path') . '/' . ltrim(str_replace('\\', '/', $photoPath), '/');
        if (!is_file($full)) {
            return null;
        }

        $mime = mime_content_type($full) ?: 'image/jpeg';
        if (!str_starts_with($mime, 'image/')) {
            return null;
        }

        return 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($full));
    }
}
