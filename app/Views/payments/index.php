<?php
use App\Core\View;
use App\Helpers\PaymentMethod;

$pageTitle = 'Payments';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$statCards = [
    ['Total Revenue', 'Rs. ' . number_format($stats['total_revenue'] ?? 0, 0), 'success'],
    ['This Month', 'Rs. ' . number_format($stats['monthly_revenue'] ?? 0, 0), 'primary'],
    ['Today', 'Rs. ' . number_format($stats['today_revenue'] ?? 0, 0), 'info'],
    ['Outstanding', 'Rs. ' . number_format($stats['outstanding'] ?? 0, 0), 'warning'],
];
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h5 class="mb-0"><i class="bi bi-credit-card"></i> Payment Management</h5>
    <a href="<?= $base ?>/payments/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> New Payment</a>
</div>

<div class="row g-3 mb-3">
    <?php foreach ($statCards as [$label, $value, $color]): ?>
    <div class="col-12 col-md-6 col-xl-3 d-flex">
        <div class="stat-card w-100">
            <div class="stat-value text-<?= $color ?>"><?= $value ?></div>
            <div class="stat-label"><?= View::escape($label) ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<form method="get" class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label class="form-label small mb-1">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" value="<?= View::escape($filters['search'] ?? '') ?>" placeholder="Name / membership / txn">
            </div>
            <div class="col-12 col-sm-6 col-md-2">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <?php foreach (['pending', 'verified', 'rejected'] as $st): ?>
                    <option value="<?= $st ?>" <?= ($filters['status'] ?? '') === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small mb-1">From</label>
                <input type="date" name="from_date" class="form-control form-control-sm" value="<?= View::escape($filters['from_date'] ?? '') ?>">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small mb-1">To</label>
                <input type="date" name="to_date" class="form-control form-control-sm" value="<?= View::escape($filters['to_date'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                <a href="<?= $base ?>/payments" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </div>
    </div>
</form>

<?php if (!empty($outstanding)): ?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white"><h6 class="mb-0">Outstanding / Expired Memberships</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead><tr><th>Member</th><th>Expiry</th><th>Fee</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($outstanding as $o): ?>
                <tr>
                    <td><?= View::escape($o['full_name_english']) ?><br><small class="text-muted"><?= View::escape($o['membership_number']) ?></small></td>
                    <td><?= $o['membership_expiry_date'] ? date('d M Y', strtotime($o['membership_expiry_date'])) : '-' ?></td>
                    <td>Rs. <?= number_format((float) ($o['fee'] ?? 0), 2) ?></td>
                    <td><a class="btn btn-sm btn-outline-primary" href="<?= $base ?>/payments/create?member_id=<?= (int) $o['id'] ?>">Pay</a></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="member-cards">
    <?php if (empty($payments['data'])): ?>
    <div class="text-center text-muted py-5">No payments found.</div>
    <?php endif; ?>
    <?php foreach ($payments['data'] as $p): ?>
    <div class="member-card">
        <div class="card-body-content flex-fill">
            <h6 class="mb-0"><?= View::escape($p['full_name_english']) ?></h6>
            <small class="text-muted"><?= View::escape($p['membership_number']) ?></small>
            <div class="mt-1"><strong class="text-success">Rs. <?= number_format((float) $p['amount'], 2) ?></strong></div>
            <small class="text-muted"><?= View::escape(PaymentMethod::display($p['payment_method'] ?? '')) ?> &middot; <?= date('d M Y', strtotime($p['payment_date'])) ?></small>
            <?php if (!empty($p['notes'])): ?>
            <div><small class="text-muted"><?= View::escape($p['notes']) ?></small></div>
            <?php endif; ?>
        </div>
        <div class="text-end">
            <span class="badge bg-<?= match($p['status']) { 'verified' => 'success', 'rejected' => 'danger', default => 'warning' } ?>">
                <?= ucfirst($p['status']) ?>
            </span>
            <div class="mt-2 d-flex flex-column gap-1">
                <?php if ($p['status'] === 'pending'): ?>
                <button class="btn btn-sm btn-success verify-btn" data-id="<?= (int) $p['id'] ?>">Verify</button>
                <button class="btn btn-sm btn-outline-danger reject-btn" data-id="<?= (int) $p['id'] ?>">Reject</button>
                <?php endif; ?>
                <?php if (!empty($p['receipt_id'])): ?>
                <a class="btn btn-sm btn-outline-primary" href="<?= $base ?>/receipts/<?= (int) $p['receipt_id'] ?>">Receipt PDF</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if (($payments['total_pages'] ?? 1) > 1): ?>
<nav class="mt-3">
    <ul class="pagination pagination-sm justify-content-center">
        <?php for ($i = 1; $i <= $payments['total_pages']; $i++): ?>
        <li class="page-item <?= $i === (int) $payments['page'] ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>&status=<?= urlencode($filters['status'] ?? '') ?>&from_date=<?= urlencode($filters['from_date'] ?? '') ?>&to_date=<?= urlencode($filters['to_date'] ?? '') ?>&search=<?= urlencode($filters['search'] ?? '') ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<script>
$('.verify-btn').on('click', function(e) {
    e.stopPropagation();
    const id = $(this).data('id');
    Swal.fire({ title: 'Verify Payment?', icon: 'question', showCancelButton: true, confirmButtonText: 'Yes, Verify' }).then(r => {
        if (r.isConfirmed) {
            $.post(BASE_URL + '/payments/' + id + '/verify', { _csrf_token: CSRF_TOKEN }, function(res) {
                if (res.success) Swal.fire('Verified', 'Receipt: ' + res.receipt_number, 'success').then(() => location.reload());
                else Swal.fire('Error', res.message || 'Failed', 'error');
            });
        }
    });
});
$('.reject-btn').on('click', function(e) {
    e.stopPropagation();
    const id = $(this).data('id');
    Swal.fire({ title: 'Reject Payment?', input: 'text', inputPlaceholder: 'Reason (optional)', showCancelButton: true, confirmButtonText: 'Reject' }).then(r => {
        if (r.isConfirmed) {
            $.post(BASE_URL + '/payments/' + id + '/reject', { _csrf_token: CSRF_TOKEN, reason: r.value || '' }, function(res) {
                if (res.success) location.reload();
                else Swal.fire('Error', res.message || 'Failed', 'error');
            });
        }
    });
});
</script>
