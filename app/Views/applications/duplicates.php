<?php
use App\Core\View;

$pageTitle = 'Duplicate Applications';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<h5 class="mb-3"><i class="bi bi-exclamation-triangle"></i> Duplicate NIC Records</h5>

<p class="text-muted small mb-4">
    Super Admins can review duplicate NIC numbers across members and applications.
    You may still open each application and approve it when appropriate, or update member records separately.
</p>

<?php if (empty($summary)): ?>
<div class="alert alert-success">
    <i class="bi bi-check-circle"></i> No duplicate NIC numbers found.
</div>
<?php else: ?>
<?php foreach ($summary as $row):
    $nic = $row['nic_key'];
    $detail = $details[$nic] ?? ['members' => [], 'applications' => []];
?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>NIC: <?= View::escape($nic) ?></strong>
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
                <a href="<?= $base ?>/members/<?= (int) $member['id'] ?>" class="btn btn-sm btn-outline-primary">View Member</a>
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
                    <small class="text-muted">
                        <?= View::escape($app['application_number'] ?? '') ?> ·
                        <?= ucfirst(str_replace('_', ' ', $app['status'])) ?> ·
                        <?= date('d M Y', strtotime($app['created_at'])) ?>
                    </small>
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

<div class="mt-3">
    <a href="<?= $base ?>/applications" class="btn btn-outline-secondary">Back to Applications</a>
</div>
