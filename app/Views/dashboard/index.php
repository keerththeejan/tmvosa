<?php
use App\Core\View;

$pageTitle = 'Dashboard';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$statCards = [
    ['Total Applications', number_format($stats['applications_total'] ?? 0), 'file-earmark-text', 'primary'],
    ['Pending Applications', number_format($stats['applications_pending'] ?? 0), 'hourglass-split', 'warning'],
    ['Approved Applications', number_format($stats['applications_approved'] ?? 0), 'check-circle', 'success'],
    ['Rejected Applications', number_format($stats['applications_rejected'] ?? 0), 'x-circle', 'danger'],
    ['Active Members', number_format($stats['active'] ?? 0), 'person-check', 'info'],
    ['Expired Members', number_format($stats['expired'] ?? 0), 'calendar-x', 'secondary'],
    ['Cards Printed', number_format($stats['cards_printed'] ?? 0), 'person-vcard', 'dark'],
    ['Pending Renewals', number_format($stats['pending_renewals'] ?? 0), 'arrow-repeat', 'warning'],
    ["Today's Payments", 'Rs. ' . number_format($stats['today_revenue'] ?? 0, 0), 'cash', 'success'],
    ['Monthly Income', 'Rs. ' . number_format($stats['monthly_revenue'] ?? 0, 0), 'cash-stack', 'primary'],
    ['Annual Income', 'Rs. ' . number_format($stats['annual_revenue'] ?? 0, 0), 'graph-up', 'info'],
    ['Total Revenue', 'Rs. ' . number_format($stats['total_revenue'] ?? 0, 0), 'wallet2', 'dark'],
];
?>
<h5 class="mb-3"><i class="bi bi-speedometer2"></i> Dashboard</h5>

<div class="row g-3 mb-4">
    <?php foreach ($statCards as [$label, $value, $icon, $color]): ?>
    <div class="col-12 col-md-6 col-xl-3 col-xxl-2 d-flex">
        <div class="stat-card w-100">
            <div class="stat-icon bg-<?= $color ?>-subtle text-<?= $color ?>"><i class="bi bi-<?= $icon ?>"></i></div>
            <div class="stat-value"><?= $value ?></div>
            <div class="stat-label"><?= View::escape($label) ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h6></div>
    <div class="card-body d-flex flex-wrap gap-2">
        <a href="<?= dirname($base) ?>/" class="btn btn-sm btn-outline-primary" target="_blank"><i class="bi bi-plus-circle"></i> New Application</a>
        <a href="<?= $base ?>/members/create" class="btn btn-sm btn-outline-primary"><i class="bi bi-person-plus"></i> Add Member</a>
        <a href="<?= $base ?>/payments/create" class="btn btn-sm btn-outline-success"><i class="bi bi-credit-card"></i> New Payment</a>
        <a href="<?= $base ?>/applications?status=pending" class="btn btn-sm btn-outline-warning"><i class="bi bi-hourglass"></i> Pending Apps</a>
        <a href="<?= $base ?>/membership-cards" class="btn btn-sm btn-outline-dark"><i class="bi bi-person-vcard"></i> Cards</a>
        <a href="<?= $base ?>/reports" class="btn btn-sm btn-outline-secondary"><i class="bi bi-bar-chart"></i> Reports</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h6 class="mb-0">System Status</h6></div>
            <div class="card-body">
                <?php
                $checks = [
                    'Database' => $systemStatus['database'] ?? false,
                    'Composer Vendor' => $systemStatus['vendor'] ?? false,
                    'SMTP Configured' => $systemStatus['smtp'] ?? false,
                    'Uploads Writable' => $systemStatus['uploads_writable'] ?? false,
                ];
                foreach ($checks as $label => $ok):
                ?>
                <div class="d-flex justify-content-between mb-2">
                    <span><?= View::escape($label) ?></span>
                    <span class="badge bg-<?= $ok ? 'success' : 'danger' ?>"><?= $ok ? 'OK' : 'Check' ?></span>
                </div>
                <?php endforeach; ?>
                <small class="text-muted">New this month: <?= (int) ($stats['new_this_month'] ?? 0) ?> members</small>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h6 class="mb-0">Latest Notifications / Audit</h6></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if (empty($recentAudits)): ?>
                    <li class="list-group-item text-muted">No recent activity.</li>
                    <?php else: ?>
                    <?php foreach ($recentAudits as $log): ?>
                    <li class="list-group-item small">
                        <strong><?= View::escape($log['action']) ?></strong>
                        <span class="text-muted">by <?= View::escape($log['user_name'] ?? 'System') ?></span>
                        <div class="text-muted"><?= date('d M Y H:i', strtotime($log['created_at'])) ?></div>
                    </li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h6 class="mb-0"><i class="bi bi-file-earmark-text"></i> Recent Applications</h6>
        <a href="<?= $base ?>/applications?status=pending" class="btn btn-sm btn-outline-primary">View pending</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Application No.</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentApplications)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No applications yet.</td></tr>
                    <?php else: ?>
                    <?php foreach ($recentApplications as $app): ?>
                    <tr>
                        <td><a href="<?= $base ?>/applications/<?= (int) $app['id'] ?>"><?= View::escape($app['application_number']) ?></a></td>
                        <td><?= View::escape($app['full_name_tamil'] ?: $app['full_name_english']) ?></td>
                        <td><?= View::escape($app['mobile']) ?></td>
                        <td><span class="badge bg-<?= match($app['status']) { 'approved' => 'success', 'rejected' => 'danger', 'pending' => 'warning', default => 'info' } ?>"><?= ucfirst(str_replace('_', ' ', $app['status'])) ?></span></td>
                        <td><?= date('d M Y H:i', strtotime($app['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h6 class="mb-0">Recently Registered Members</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead><tr><th>Name</th><th>Number</th><th>Date</th></tr></thead>
                        <tbody>
                        <?php if (empty($recentMembers)): ?>
                        <tr><td colspan="3" class="text-center text-muted py-3">No members yet.</td></tr>
                        <?php else: foreach ($recentMembers as $m): ?>
                        <tr>
                            <td><a href="<?= $base ?>/members/<?= (int) $m['id'] ?>"><?= View::escape($m['full_name_english']) ?></a></td>
                            <td><?= View::escape($m['membership_number']) ?></td>
                            <td><?= date('d M Y', strtotime($m['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-receipt"></i> Recent Payments</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentPayments)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">No recent payments.</td></tr>
                            <?php else: ?>
                            <?php foreach ($recentPayments as $payment): ?>
                            <tr>
                                <td><?= View::escape($payment['full_name_english']) ?></td>
                                <td>Rs. <?= number_format((float) $payment['amount'], 2) ?></td>
                                <td><span class="badge bg-<?= match($payment['status']) { 'verified' => 'success', 'rejected' => 'danger', default => 'warning' } ?>"><?= ucfirst($payment['status']) ?></span></td>
                                <td><?= date('d M Y', strtotime($payment['payment_date'])) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <?php
    $charts = [
        ['Membership Growth', 'growthChart', 'col-12 col-lg-6'],
        ['Monthly Registration / Revenue', 'revenueChart', 'col-12 col-lg-6'],
        ['Country Distribution', 'countryChart', 'col-12 col-md-6'],
        ['Membership Types', 'typeChart', 'col-12 col-md-6'],
        ['Gender Distribution', 'genderChart', 'col-12 col-md-6'],
        ['Batch Distribution', 'batchChart', 'col-12 col-md-6'],
    ];
    foreach ($charts as [$title, $canvasId, $col]):
    ?>
    <div class="<?= $col ?>">
        <div class="card chart-card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0"><?= View::escape($title) ?></h6>
            </div>
            <div class="card-body"><canvas id="<?= $canvasId ?>" height="200"></canvas></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const growth = <?= json_encode($growth) ?>;
    const revenue = <?= json_encode($revenue) ?>;
    const countries = <?= json_encode($countries) ?>;
    const types = <?= json_encode($types) ?>;
    const genders = <?= json_encode($genders ?? []) ?>;
    const batches = <?= json_encode($batches ?? []) ?>;

    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: growth.map(d => d.month),
            datasets: [{ label: 'Members', data: growth.map(d => d.count), borderColor: '#1a5276', tension: 0.3, fill: true, backgroundColor: 'rgba(26,82,118,0.1)' }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: revenue.map(d => d.month),
            datasets: [{ label: 'Revenue (Rs.)', data: revenue.map(d => d.total), backgroundColor: '#27ae60' }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('countryChart'), {
        type: 'doughnut',
        data: {
            labels: countries.map(d => d.country),
            datasets: [{ data: countries.map(d => d.count), backgroundColor: ['#1a5276','#2980b9','#27ae60','#f39c12','#e74c3c','#8e44ad','#2c3e50','#16a085'] }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('typeChart'), {
        type: 'pie',
        data: {
            labels: types.map(d => d.type),
            datasets: [{ data: types.map(d => d.count), backgroundColor: ['#1a5276','#27ae60'] }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        data: {
            labels: genders.map(d => d.gender),
            datasets: [{ data: genders.map(d => d.count), backgroundColor: ['#2980b9','#e74c3c','#8e44ad','#95a5a6'] }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    new Chart(document.getElementById('batchChart'), {
        type: 'bar',
        data: {
            labels: batches.map(d => d.batch),
            datasets: [{ label: 'Members', data: batches.map(d => d.count), backgroundColor: '#1a5276' }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
});
</script>
