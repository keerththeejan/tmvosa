<?php $pageTitle = 'Member Details'; $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>
<div class="member-profile-card text-center mb-3">
    <div class="profile-avatar mx-auto">
        <?php if ($member['photo']): ?>
        <img src="<?= $base ?>/../storage/uploads/<?= $member['photo'] ?>" alt="">
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
    <?php
    $membershipDisplay = \App\Helpers\Lang::membershipDisplayFromName($member['membership_type_name'] ?? '');
    ?>
    <div class="info-row">
        <span class="bilingual-text bilingual-block">
            <span class="label-ta"><?= \App\Core\View::escape(\App\Helpers\Lang::field('membership_type')['ta']) ?></span>
            <span class="label-en"><?= \App\Core\View::escape(\App\Helpers\Lang::field('membership_type')['en']) ?></span>
        </span>
        <strong class="text-end">
            <span class="d-block label-ta"><?= \App\Core\View::escape($membershipDisplay['with_validity_ta']) ?></span>
            <span class="d-block label-en text-muted small"><?= \App\Core\View::escape($membershipDisplay['with_validity_en']) ?></span>
        </strong>
    </div>
    <div class="info-row">
        <span class="bilingual-text bilingual-block">
            <span class="label-ta"><?= \App\Core\View::escape(\App\Helpers\Lang::field('validity_period')['ta']) ?></span>
            <span class="label-en"><?= \App\Core\View::escape(\App\Helpers\Lang::field('validity_period')['en']) ?></span>
        </span>
        <strong class="text-end">
            <span class="d-block label-ta"><?= \App\Core\View::escape($membershipDisplay['validity_ta']) ?></span>
            <span class="d-block label-en text-muted small"><?= \App\Core\View::escape($membershipDisplay['validity_en']) ?></span>
        </strong>
    </div>
    <div class="info-row"><span>Expires</span><strong><?= \App\Core\View::escape($member['membership_expiry_date'] ?? 'N/A') ?></strong></div>
</div></div>

<div class="d-grid gap-2 mb-3">
    <a href="<?= $base ?>/card/<?= $member['id'] ?>" class="btn btn-primary"><i class="bi bi-qr-code"></i> View Membership Card</a>
    <a href="<?= $base ?>/members/<?= $member['id'] ?>/edit" class="btn btn-outline-secondary"><i class="bi bi-pencil"></i> Edit Member</a>
</div>
