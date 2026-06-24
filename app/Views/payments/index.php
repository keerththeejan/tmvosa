<?php
use App\Core\View;
use App\Helpers\PaymentMethod;

$pageTitle = 'Payments';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$statCards = [
    ['Total Revenue', 'Rs. ' . number_format($stats['total_revenue'] ?? 0, 0), 'success'],
    ['This Month', 'Rs. ' . number_format($stats['monthly_revenue'] ?? 0, 0), 'primary'],
    ['Outstanding', 'Rs. ' . number_format($stats['outstanding'] ?? 0, 0), 'warning'],
];
?>
<h5 class="mb-3"><i class="bi bi-credit-card"></i> Payment Management</h5>

<div class="row g-3 mb-3">
    <?php foreach ($statCards as [$label, $value, $color]): ?>
    <div class="col-4">
        <div class="stat-card">
            <div class="stat-value text-<?= $color ?>"><?= $value ?></div>
            <div class="stat-label"><?= View::escape($label) ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="member-cards">
    <?php foreach ($payments['data'] as $p): ?>
    <div class="member-card">
        <div class="card-body-content flex-fill">
            <h6 class="mb-0"><?= View::escape($p['full_name_english']) ?></h6>
            <small class="text-muted"><?= View::escape($p['membership_number']) ?></small>
            <div class="mt-1"><strong class="text-success">Rs. <?= number_format($p['amount'], 2) ?></strong></div>
            <small class="text-muted"><?= View::escape(PaymentMethod::display($p['payment_method'] ?? '')) ?> &middot; <?= date('d M Y', strtotime($p['payment_date'])) ?></small>
        </div>
        <div class="text-end">
            <span class="badge bg-<?= match($p['status']) { 'verified' => 'success', 'rejected' => 'danger', default => 'warning' } ?>">
                <?= ucfirst($p['status']) ?>
            </span>
            <?php if ($p['status'] === 'pending'): ?>
            <button class="btn btn-sm btn-success mt-2 verify-btn" data-id="<?= $p['id'] ?>">Verify</button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<script>
$('.verify-btn').on('click', function(e) {
    e.stopPropagation();
    const id = $(this).data('id');
    Swal.fire({
        title: 'Verify Payment?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Verify'
    }).then(r => {
        if (r.isConfirmed) $.post(BASE_URL + '/payments/' + id + '/verify', { _csrf_token: CSRF_TOKEN }, function(res) {
            if (res.success) Swal.fire('Verified', 'Receipt: ' + res.receipt_number, 'success').then(() => location.reload());
        });
    });
});
</script>
