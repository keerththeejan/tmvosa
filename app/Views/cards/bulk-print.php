<?php
use App\Core\View;
$pageTitle = 'Bulk Print Cards';
$upload = \App\Core\App::config('app.upload_path');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bulk Print Membership Cards</title>
<style>
body { font-family: Arial, sans-serif; }
.card-page { page-break-after: always; padding: 20px; max-width: 420px; margin: 0 auto 24px; border: 2px solid #1a5276; border-radius: 12px; }
.header { background:#1a5276; color:#fff; padding:12px; text-align:center; }
.body { padding:16px; text-align:center; }
img.photo { width:80px; height:80px; border-radius:50%; object-fit:cover; }
img.qr { width:120px; height:120px; }
@media print { .no-print { display:none; } .card-page { border:2px solid #000; } }
</style>
</head>
<body>
<button class="no-print" onclick="window.print()">Print All</button>
<?php foreach ($cards as $item):
    $member = $item['member'];
    $card = $item['card'];
    $qr = $upload . '/' . ($card['qr_code_path'] ?? '');
    $photo = !empty($member['photo']) ? $upload . '/' . $member['photo'] : '';
?>
<div class="card-page">
    <div class="header">
        <strong>Kilinochchi / Thiruvaiyaru Maha Vidyalayam</strong><br>
        Old Students' Association
    </div>
    <div class="body">
        <?php if ($photo && file_exists($photo)): ?>
        <img class="photo" src="data:image/jpeg;base64,<?= base64_encode(file_get_contents($photo)) ?>" alt="">
        <?php endif; ?>
        <h2><?= View::escape($member['full_name_english']) ?></h2>
        <p><strong><?= View::escape($member['membership_number']) ?></strong></p>
        <p><?= View::escape(\App\Helpers\MembershipType::bilingualLabel($member['membership_type_name'] ?? '', $member['membership_type_slug'] ?? null)) ?></p>
        <p>Valid Until: <?= View::escape($member['membership_expiry_date'] ?? 'N/A') ?></p>
        <?php if ($qr && file_exists($qr)): ?>
        <img class="qr" src="data:image/png;base64,<?= base64_encode(file_get_contents($qr)) ?>" alt="QR">
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
</body>
</html>
