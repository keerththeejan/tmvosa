<?php
use App\Core\View;

$pageTitle = 'Dashboard';
$statKeys = ['total_members', 'active', 'pending_apps', 'monthly_revenue', 'total_revenue', 'expiring_soon'];
$statValues = [
    number_format($stats['total'] ?? 0),
    number_format($stats['active'] ?? 0),
    number_format($stats['pending_applications'] ?? 0),
    number_format($stats['monthly_revenue'] ?? 0),
    number_format($stats['total_revenue'] ?? 0),
    number_format($stats['expiring'] ?? 0),
];
$statIcons = ['people', 'person-check', 'hourglass-split', 'currency-rupee', 'cash-stack', 'calendar-x'];
$statColors = ['primary', 'success', 'warning', 'info', 'dark', 'danger'];
?>
<div class="mb-3"><?php \App\Core\View::heading('dashboard', 'h5', 'speedometer2'); ?></div>
<div class="row g-3 mb-4">
    <?php foreach ($statKeys as $i => $key): ?>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon bg-<?= $statColors[$i] ?>-subtle text-<?= $statColors[$i] ?>"><i class="bi bi-<?= $statIcons[$i] ?>"></i></div>
            <div class="stat-value"><?= $statValues[$i] ?></div>
            <div class="stat-label bilingual-text bilingual-block">
                <?php View::text($key, 'span', true); ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-3">
    <?php
    $charts = [
        ['membership_growth', 'growthChart'],
        ['revenue_growth', 'revenueChart'],
        ['country_distribution', 'countryChart'],
        ['membership_types', 'typeChart'],
    ];
    foreach ($charts as [$key, $canvasId]):
    ?>
    <div class="col-12 <?= str_contains($canvasId, 'growth') || str_contains($canvasId, 'revenue') ? 'col-lg-6' : 'col-md-6' ?>">
        <div class="card chart-card">
            <div class="card-header bilingual-text bilingual-block">
                <?php View::text($key, 'h6', true, 'mb-0'); ?>
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
