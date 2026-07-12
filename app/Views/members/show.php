<?php
$pageTitle = 'Member Details';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$fileBase = $base . '/files';
use App\Helpers\Lang;
$membershipDisplay = Lang::membershipDisplayFromName($member['membership_type_name'] ?? '');
if (!empty($member['membership_type_slug'])) {
    $membershipDisplay = Lang::membershipDisplayFromSlug(
        $member['membership_type_slug'],
        null,
        $member['membership_type_name'] ?? null
    );
}
?>
<div class="member-profile-card text-center mb-3">
    <div class="profile-avatar mx-auto">
        <?php if ($member['photo']): ?>
        <img src="<?= $fileBase ?>/<?= ltrim(str_replace('\\', '/', $member['photo']), '/') ?>" alt="" loading="lazy" decoding="async">
        <?php else: ?>
        <div class="avatar-placeholder-lg"><?= strtoupper(substr($member['full_name_english'], 0, 1)) ?></div>
        <?php endif; ?>
    </div>
    <h5 class="mt-3 mb-0"><?= \App\Core\View::escape($member['full_name_english']) ?></h5>
    <?php if ($member['full_name_tamil']): ?><p class="text-muted"><?= \App\Core\View::escape($member['full_name_tamil']) ?></p><?php endif; ?>
    <p class="text-primary fw-bold"><?= \App\Core\View::escape($member['membership_number']) ?></p>
    <span class="badge bg-<?= $member['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($member['status']) ?></span>
</div>

<div class="card mb-3"><div class="card-body">
    <div class="info-row"><span>NIC</span><strong><?= \App\Core\View::escape($member['nic_number'] ?? '-') ?></strong></div>
    <div class="info-row"><span>Mobile</span><strong><?= \App\Core\View::escape($member['mobile']) ?></strong></div>
    <div class="info-row"><span>Email</span><strong><?= \App\Core\View::escape($member['email'] ?? '-') ?></strong></div>
    <div class="info-row"><span>Country</span><strong><?= \App\Core\View::escape($member['country_name'] ?? '-') ?></strong></div>
    <div class="info-row"><span>Batch</span><strong><?= \App\Core\View::escape($member['studied_to_year'] ?? '-') ?></strong></div>
    <div class="info-row"><span>Occupation</span><strong><?= \App\Core\View::escape($member['occupation'] ?? '-') ?></strong></div>
    <div class="info-row"><span>Membership Type</span><strong><?= \App\Core\View::escape($membershipDisplay['bilingual']) ?></strong></div>
    <div class="info-row"><span>Validity Period</span><strong><?= \App\Core\View::escape($membershipDisplay['validity_en']) ?></strong></div>
    <div class="info-row"><span>Expires</span><strong><?= \App\Core\View::escape($member['membership_expiry_date'] ?? 'N/A') ?></strong></div>
</div></div>

<div class="d-grid gap-2 mb-3">
    <a href="<?= $base ?>/card/<?= $member['id'] ?>" class="btn btn-primary"><i class="bi bi-qr-code"></i> View Membership Card</a>
    <a href="<?= $base ?>/members/<?= $member['id'] ?>/edit" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i> Edit Member</a>
    <a href="<?= $base ?>/members/<?= $member['id'] ?>/print" class="btn btn-outline-dark" target="_blank"><i class="bi bi-printer"></i> Print Profile</a>
    <a href="<?= $base ?>/payments/create?member_id=<?= (int) $member['id'] ?>" class="btn btn-outline-success"><i class="bi bi-credit-card"></i> Record Payment</a>
    <?php if ($member['status'] === 'active'): ?>
    <button type="button" class="btn btn-outline-warning member-action" data-action="suspend">Suspend</button>
    <button type="button" class="btn btn-outline-secondary member-action" data-action="deactivate">Deactivate / Expire</button>
    <?php else: ?>
    <button type="button" class="btn btn-outline-success member-action" data-action="activate">Activate</button>
    <?php endif; ?>
    <button type="button" class="btn btn-outline-primary member-action" data-action="renew">Renew Membership</button>
</div>

<script>
$('.member-action').on('click', function() {
    const action = $(this).data('action');
    const titles = { suspend: 'Suspend member?', activate: 'Activate member?', deactivate: 'Deactivate member?', renew: 'Renew membership?' };
    Swal.fire({ title: titles[action] || 'Confirm?', icon: 'question', showCancelButton: true }).then(r => {
        if (!r.isConfirmed) return;
        $.post(BASE_URL + '/members/<?= (int) $member['id'] ?>/' + action, { _csrf_token: CSRF_TOKEN }, function(res) {
            if (res.success) Swal.fire('Done', res.message, 'success').then(() => location.reload());
            else Swal.fire('Error', res.message || 'Failed', 'error');
        });
    });
});
</script>
