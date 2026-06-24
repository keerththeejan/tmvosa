<?php
use App\Core\View;

$pageTitle = 'Dashboard';
$statCards = [
    ['Total Members', number_format($stats['total'] ?? 0), 'people', 'primary'],
    ['Pending Applications', number_format($stats['applications_pending'] ?? 0), 'hourglass-split', 'warning'],
    ['Approved Applications', number_format($stats['applications_approved'] ?? 0), 'check-circle', 'success'],
    ['Rejected Applications', number_format($stats['applications_rejected'] ?? 0), 'x-circle', 'danger'],
    ['Active Memberships', number_format($stats['active'] ?? 0), 'person-check', 'info'],
    ['Expired Memberships', number_format($stats['expired'] ?? 0), 'calendar-x', 'secondary'],
    ['Total Revenue', 'Rs. ' . number_format($stats['total_revenue'] ?? 0, 0), 'cash-stack', 'dark'],
];
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<h5 class="mb-3"><i class="bi bi-speedometer2"></i> Dashboard</h5>

<div class="row g-3 mb-4">
    <?php foreach ($statCards as [$label, $value, $icon, $color]): ?>
    <div class="col-6 col-md-4 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon bg-<?= $color ?>-subtle text-<?= $color ?>"><i class="bi bi-<?= $icon ?>"></i></div>
            <div class="stat-value"><?= $value ?></div>
            <div class="stat-label"><?= View::escape($label) ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
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

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white">
        <h6 class="mb-0"><i class="bi bi-receipt"></i> Recent Payments</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead>
                    <tr>
                        <th>Member Name</th>
                        <th>Membership Number</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentPayments)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No recent payments.</td></tr>
                    <?php else: ?>
                    <?php foreach ($recentPayments as $payment): ?>
                    <tr>
                        <td><?= View::escape($payment['full_name_english']) ?></td>
                        <td><?= View::escape($payment['membership_number']) ?></td>
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

<div class="row g-3">
    <?php
    $charts = [
        ['Membership Growth', 'growthChart'],
        ['Revenue Growth', 'revenueChart'],
        ['Country Distribution', 'countryChart'],
        ['Membership Types', 'typeChart'],
    ];
    foreach ($charts as [$title, $canvasId]):
    ?>
    <div class="col-12 <?= str_contains($canvasId, 'growth') || str_contains($canvasId, 'revenue') ? 'col-lg-6' : 'col-md-6' ?>">
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
});
</script>
