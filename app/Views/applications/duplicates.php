<?php
use App\Core\View;

$pageTitle = 'Duplicate Records';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$emailDuplicates = $emailDuplicates ?? [];
$mobileDuplicates = $mobileDuplicates ?? [];
$membershipDuplicates = $membershipDuplicates ?? [];
?>
<h5 class="mb-3"><i class="bi bi-exclamation-triangle"></i> Duplicate Records</h5>

<p class="text-muted small mb-4">
    Review duplicate NIC, email, mobile, and membership numbers. Open records side-by-side to compare differences.
    Merge is not automated — update or reject records manually as needed.
</p>

<ul class="nav nav-tabs mb-3 flex-nowrap overflow-auto" role="tablist">
    <li class="nav-item flex-shrink-0"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-nic" type="button">NIC</button></li>
    <li class="nav-item flex-shrink-0"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-email" type="button">Email</button></li>
    <li class="nav-item flex-shrink-0"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-mobile" type="button">Mobile</button></li>
    <li class="nav-item flex-shrink-0"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-membership" type="button">Membership No</button></li>
</ul>

<div class="tab-content">
<div class="tab-pane fade show active" id="tab-nic">
<?php if (empty($summary)): ?>
<div class="alert alert-success"><i class="bi bi-check-circle"></i> No duplicate NIC numbers found.</div>
<?php else: ?>
<?php foreach ($summary as $row):
    $nic = $row['nic_key'];
    $detail = $details[$nic] ?? ['members' => [], 'applications' => []];
?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <strong class="text-break">NIC: <?= View::escape($nic) ?></strong>
        <span class="badge bg-warning text-dark"><?= (int) $row['record_count'] ?> records</span>
    </div>
    <div class="card-body">
        <?php if (!empty($detail['members'])): ?>
        <h6 class="small text-uppercase text-muted">Members</h6>
        <ul class="list-group list-group-flush mb-3">
            <?php foreach ($detail['members'] as $member): ?>
            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                <div>
                    <div><?= View::escape($member['full_name_english']) ?></div>
                    <small class="text-muted"><?= View::escape($member['membership_number'] ?? '') ?> · <?= View::escape($member['mobile'] ?? '') ?></small>
                </div>
                <a href="<?= $base ?>/members/<?= (int) $member['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        <?php if (!empty($detail['applications'])): ?>
        <h6 class="small text-uppercase text-muted">Applications</h6>
        <ul class="list-group list-group-flush">
            <?php foreach ($detail['applications'] as $app): ?>
            <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                <div>
                    <div><?= View::escape($app['full_name_english']) ?></div>
                    <small class="text-muted"><?= View::escape($app['application_number'] ?? '') ?> · <?= ucfirst(str_replace('_', ' ', $app['status'])) ?></small>
                </div>
                <a href="<?= $base ?>/applications/<?= (int) $app['id'] ?>" class="btn btn-sm btn-outline-primary">Review</a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>

<div class="tab-pane fade" id="tab-email">
<?php if (empty($emailDuplicates)): ?>
<div class="alert alert-success">No duplicate emails found.</div>
<?php else: foreach ($emailDuplicates as $row): ?>
<div class="alert alert-warning d-flex flex-wrap justify-content-between align-items-center gap-2">
    <span class="text-break"><?= View::escape($row['email_key']) ?></span>
    <span class="badge bg-dark"><?= (int) $row['record_count'] ?></span>
</div>
<?php endforeach; endif; ?>
</div>

<div class="tab-pane fade" id="tab-mobile">
<?php if (empty($mobileDuplicates)): ?>
<div class="alert alert-success">No duplicate mobiles found.</div>
<?php else: foreach ($mobileDuplicates as $row): ?>
<div class="alert alert-warning d-flex flex-wrap justify-content-between align-items-center gap-2">
    <span class="text-break"><?= View::escape($row['mobile_key']) ?></span>
    <span class="badge bg-dark"><?= (int) $row['record_count'] ?></span>
</div>
<?php endforeach; endif; ?>
</div>

<div class="tab-pane fade" id="tab-membership">
<?php if (empty($membershipDuplicates)): ?>
<div class="alert alert-success">No duplicate membership numbers found.</div>
<?php else: foreach ($membershipDuplicates as $row): ?>
<div class="alert alert-danger d-flex flex-wrap justify-content-between align-items-center gap-2">
    <span class="text-break"><?= View::escape($row['membership_number']) ?></span>
    <span class="badge bg-dark"><?= (int) $row['record_count'] ?></span>
</div>
<?php endforeach; endif; ?>
</div>
</div>

<div class="mt-3">
    <a href="<?= $base ?>/applications" class="btn btn-outline-secondary">Back to Applications</a>
</div>
