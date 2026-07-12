<?php
use App\Core\View;
$pageTitle = 'Print Application';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application <?= View::escape($application['application_number']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 24px; }
        h1 { color: #1a5276; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        td:first-child { width: 200px; color: #666; }
        .history { margin-top: 24px; font-size: 13px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
<button class="no-print" onclick="window.print()">Print</button>
<h1>Membership Application</h1>
<p><?= View::escape($application['application_number']) ?> · <?= View::escape(ucfirst(str_replace('_',' ',$application['status']))) ?></p>
<table>
    <tr><td>Name (EN)</td><td><?= View::escape($application['full_name_english']) ?></td></tr>
    <tr><td>Name (TA)</td><td><?= View::escape($application['full_name_tamil'] ?? '-') ?></td></tr>
    <tr><td>NIC</td><td><?= View::escape($application['nic_number'] ?? '-') ?></td></tr>
    <tr><td>Mobile</td><td><?= View::escape($application['mobile']) ?></td></tr>
    <tr><td>Email</td><td><?= View::escape($application['email'] ?? '-') ?></td></tr>
    <tr><td>Country</td><td><?= View::escape($application['country_name'] ?? '-') ?></td></tr>
    <tr><td>Membership Type</td><td><?= View::escape(\App\Helpers\MembershipType::bilingualLabel($application['membership_type_name'] ?? '', $application['membership_type_slug'] ?? null)) ?></td></tr>
    <tr><td>Amount Paid</td><td>Rs. <?= number_format((float)($application['amount_paid'] ?? 0), 2) ?></td></tr>
    <tr><td>Submitted</td><td><?= View::escape($application['created_at']) ?></td></tr>
</table>

<?php if (!empty($history)): ?>
<div class="history">
    <h3>Status History (Audit)</h3>
    <ul>
        <?php foreach ($history as $h): ?>
        <li><?= View::escape($h['created_at']) ?> — <?= View::escape($h['action']) ?> by <?= View::escape($h['user_name'] ?? 'System') ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
</body>
</html>
