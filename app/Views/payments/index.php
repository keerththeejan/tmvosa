<?php
use App\Core\View;

$pageTitle = 'Payments';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$statsKeys = ['total_revenue', 'this_month', 'outstanding'];
$statValues = [
    number_format($stats['total_revenue'] ?? 0),
    number_format($stats['monthly_revenue'] ?? 0),
    number_format($stats['outstanding'] ?? 0),
];
?>
<div class="mb-3 bilingual-text bilingual-block">
    <?php View::heading('payment_management', 'h5'); ?>
</div>

<div class="row g-3 mb-3">
    <?php foreach ($statsKeys as $i => $key): ?>
    <div class="col-4">
        <div class="stat-card">
            <div class="stat-value text-<?= ['success', 'primary', 'warning'][$i] ?>"><?= $statValues[$i] ?></div>
            <div class="stat-label bilingual-text bilingual-block"><?php View::text($key, 'span', true); ?></div>
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
            <small class="text-muted"><?= View::escape($p['payment_method']) ?> &middot; <?= date('d M Y', strtotime($p['payment_date'])) ?></small>
        </div>
        <div class="text-end">
            <?php $st = \App\Helpers\Lang::ui($p['status'] === 'verified' ? 'approved' : ($p['status'] === 'rejected' ? 'rejected' : 'pending')); ?>
            <span class="badge bg-<?= match($p['status']) { 'verified' => 'success', 'rejected' => 'danger', default => 'warning' } ?>">
                <?= View::escape(is_array($st) ? $st['ta'] : $st) ?>
            </span>
            <?php if ($p['status'] === 'pending'): ?>
            <button class="btn btn-sm btn-success mt-2 verify-btn bilingual-btn" data-id="<?= $p['id'] ?>">
                <span class="label-ta"><?= View::escape(\App\Helpers\Lang::ui('verify')['ta']) ?></span>
                <span class="label-en"><?= View::escape(\App\Helpers\Lang::ui('verify')['en']) ?></span>
            </button>
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
        title: 'கட்டணத்தை சரிபார்க்கவா? / Verify Payment?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'சரி / Yes',
        cancelButtonText: 'ரத்து / Cancel'
    }).then(r => {
        if (r.isConfirmed) $.post(BASE_URL + '/payments/' + id + '/verify', { _csrf_token: CSRF_TOKEN }, function(res) {
            if (res.success) Swal.fire('சரிபார்க்கப்பட்டது / Verified', 'Receipt: ' + res.receipt_number, 'success').then(() => location.reload());
        });
    });
});
</script>
