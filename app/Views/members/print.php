<?php
use App\Core\View;
$pageTitle = 'Print Member Profile';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Profile — <?= View::escape($member['membership_number']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; color: #222; }
        h1 { color: #1a5276; margin-bottom: 4px; }
        .meta { color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        td:first-child { width: 180px; color: #666; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()">Print</button>
    <h1><?= View::escape($member['full_name_english']) ?></h1>
    <div class="meta"><?= View::escape($member['membership_number']) ?> · <?= View::escape(ucfirst($member['status'])) ?></div>
    <table>
        <tr><td>Tamil Name</td><td><?= View::escape($member['full_name_tamil'] ?? '-') ?></td></tr>
        <tr><td>NIC</td><td><?= View::escape($member['nic_number'] ?? '-') ?></td></tr>
        <tr><td>Mobile</td><td><?= View::escape($member['mobile']) ?></td></tr>
        <tr><td>Email</td><td><?= View::escape($member['email'] ?? '-') ?></td></tr>
        <tr><td>Country</td><td><?= View::escape($member['country_name'] ?? '-') ?></td></tr>
        <tr><td>Batch</td><td><?= View::escape($member['studied_to_year'] ?? '-') ?></td></tr>
        <tr><td>Occupation</td><td><?= View::escape($member['occupation'] ?? '-') ?></td></tr>
        <tr><td>Membership Type</td><td><?= View::escape(\App\Helpers\MembershipType::bilingualLabel($member['membership_type_name'] ?? '', $member['membership_type_slug'] ?? null)) ?></td></tr>
        <tr><td>Start</td><td><?= View::escape($member['membership_start_date'] ?? '-') ?></td></tr>
        <tr><td>Expiry</td><td><?= View::escape($member['membership_expiry_date'] ?? '-') ?></td></tr>
        <tr><td>Address</td><td><?= View::escape($member['current_address'] ?? '-') ?></td></tr>
    </table>
    <p style="margin-top:30px;color:#888;font-size:12px;">Kilinochchi / Thiruvaiyaru Maha Vidyalayam OSA</p>
</body>
</html>
