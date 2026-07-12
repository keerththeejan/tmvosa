<?php
/**
 * Regenerate membership QR codes to encode only the absolute verification URL.
 * Usage (Apache PHP preferred on WAMP): open via browser as admin, or:
 *   C:\wamp64\bin\php\php8.3.xx\php.exe scripts/regenerate-qr-urls.php
 */
declare(strict_types=1);

require dirname(__DIR__) . '/bootstrap.php';

use App\Core\App;
use App\Core\Database;
use App\Helpers\QrGenerator;
use App\Helpers\VerifyUrl;
use App\Models\MembershipCard;

App::init();

$cards = Database::fetchAll(
    "SELECT mc.id, mc.member_id, mc.qr_code_data, m.membership_number
     FROM membership_cards mc
     INNER JOIN members m ON m.id = mc.member_id
     WHERE mc.is_active = 1"
);

if (empty($_SERVER['HTTP_HOST'])) {
    $appUrl = rtrim((string) (App::config('app.url') ?: 'http://localhost/tmvosa'), '/');
    $parts = parse_url($appUrl) ?: [];
    $_SERVER['HTTP_HOST'] = $parts['host'] ?? 'localhost';
    if (!empty($parts['port'])) {
        $_SERVER['HTTP_HOST'] .= ':' . $parts['port'];
    }
    $_SERVER['HTTPS'] = (($parts['scheme'] ?? 'http') === 'https') ? 'on' : 'off';
    $_SERVER['SCRIPT_NAME'] = ($parts['path'] ?? '/tmvosa') . '/public/index.php';
}

$updated = 0;
$skipped = 0;

foreach ($cards as $card) {
    $url = VerifyUrl::forMembershipNumber((string) $card['membership_number']);
    $current = trim((string) ($card['qr_code_data'] ?? ''));

    if ($current === $url) {
        $skipped++;
        continue;
    }

    $path = QrGenerator::generate($url, $card['membership_number'] . '.png');
    MembershipCard::update((int) $card['id'], [
        'qr_code_data' => $url,
        'qr_code_path' => $path,
    ]);
    $updated++;
    echo "Updated {$card['membership_number']} -> {$url}\n";
}

echo "Done. Updated={$updated}, skipped={$skipped}, total=" . count($cards) . "\n";
