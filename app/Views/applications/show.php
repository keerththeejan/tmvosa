<?php
use App\Core\View;
use App\Helpers\Lang;
use App\Helpers\PaymentMethod;

$pageTitle = 'Application Details';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$appBase = dirname($base);
if ($appBase === '/' || $appBase === '.') {
    $appBase = $base;
}
$docTypes = ['payment_slip', 'nic_copy', 'passport_photo'];
$documentsByType = $documentsByType ?? [];
$duplicates = $duplicates ?? ['members' => [], 'applications' => []];
$relatedMembers = array_filter($duplicates['members'] ?? [], fn($m) => true);
$relatedApplications = array_filter($duplicates['applications'] ?? [], fn($a) => (int) $a['id'] !== (int) $application['id']);
$hasDuplicates = count($relatedMembers) > 0 || count($relatedApplications) > 0;
?>
<?php if ($hasDuplicates && \App\Core\Auth::hasRole('super_admin')): ?>
<div class="alert alert-warning border-warning mb-3">
    <h6 class="alert-heading mb-2"><i class="bi bi-exclamation-triangle"></i> Duplicate NIC Detected</h6>
    <p class="small mb-2">This NIC matches other member or application records. You may still approve this application if appropriate.</p>
    <?php if ($relatedMembers): ?>
    <p class="small mb-1"><strong>Members:</strong>
        <?php foreach ($relatedMembers as $member): ?>
        <a href="<?= $base ?>/members/<?= (int) $member['id'] ?>" class="badge text-bg-light text-decoration-none me-1">
            <?= View::escape($member['membership_number'] ?? ('#' . $member['id'])) ?>
        </a>
        <?php endforeach; ?>
    </p>
    <?php endif; ?>
    <?php if ($relatedApplications): ?>
    <p class="small mb-0"><strong>Other applications:</strong>
        <?php foreach ($relatedApplications as $app): ?>
        <a href="<?= $base ?>/applications/<?= (int) $app['id'] ?>" class="badge text-bg-light text-decoration-none me-1">
            <?= View::escape($app['application_number']) ?> (<?= View::escape($app['status']) ?>)
        </a>
        <?php endforeach; ?>
    </p>
    <?php endif; ?>
    <div class="mt-2">
        <a href="<?= $base ?>/applications/duplicates" class="btn btn-sm btn-outline-warning">View All Duplicates</a>
    </div>
</div>
<?php endif; ?>
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><?= View::escape($application['application_number']) ?></span>
        <span class="badge bg-<?= match($application['status']) { 'approved' => 'success', 'rejected' => 'danger', 'pending' => 'warning', default => 'info' } ?>">
            <?= ucfirst(str_replace('_', ' ', $application['status'])) ?>
        </span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12"><strong>Name:</strong> <?= View::escape($application['full_name_english']) ?></div>
            <?php if ($application['full_name_tamil']): ?>
            <div class="col-12"><strong>பெயர்:</strong> <?= View::escape($application['full_name_tamil']) ?></div>
            <?php endif; ?>
            <div class="col-6"><strong>NIC:</strong> <?= View::escape($application['nic_number'] ?? '-') ?></div>
            <div class="col-6"><strong>Mobile:</strong> <?= View::escape($application['mobile']) ?></div>
            <div class="col-6"><strong>Email:</strong> <?= View::escape($application['email'] ?? '-') ?></div>
            <div class="col-6"><strong>Country:</strong> <?= View::escape($application['country_name'] ?? '-') ?></div>
            <div class="col-6"><strong>Batch:</strong> <?= View::escape($application['studied_to_year'] ?? '-') ?></div>
            <div class="col-6"><strong>Occupation:</strong> <?= View::escape($application['occupation'] ?? '-') ?></div>
            <?php $membershipDisplay = Lang::membershipDisplayFromName($application['membership_type_name'] ?? ''); ?>
            <div class="col-6"><strong>Membership Type:</strong> <?= View::escape($membershipDisplay['with_validity_en']) ?></div>
            <div class="col-6"><strong>Validity Period:</strong> <?= View::escape($membershipDisplay['validity_en']) ?></div>
            <div class="col-6"><strong>Amount Paid:</strong> Rs. <?= number_format($application['amount_paid'] ?? 0, 2) ?></div>
            <div class="col-6"><strong>Payment Method:</strong> <?= View::escape(PaymentMethod::display($application['payment_method'] ?? '')) ?></div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0"><i class="bi bi-folder2"></i> Documents</h6></div>
    <div class="card-body">
        <div class="row g-3">
            <?php foreach ($docTypes as $type):
                $doc = $documentsByType[$type] ?? null;
                $label = Lang::field($type);
                $isOptional = in_array($type, ['nic_copy', 'passport_photo'], true);
            ?>
            <div class="col-12 col-md-4">
                <div class="doc-status-card h-100 p-3 border rounded">
                    <div class="fw-semibold mb-2"><?= View::escape($label['en']) ?></div>
                    <?php if ($doc): ?>
                    <span class="badge bg-success mb-2">Uploaded</span>
                    <?php
                    $fileName = basename($doc['file_path']);
                    $fileUrl = $appBase . '/files/documents/' . rawurlencode($fileName);
                    ?>
                    <div>
                        <a href="<?= View::escape($fileUrl) ?>" target="_blank" class="btn btn-sm btn-outline-primary">View Document</a>
                    </div>
                    <?php else: ?>
                    <span class="badge bg-secondary mb-2">Not Uploaded</span>
                    <?php if ($isOptional): ?>
                    <p class="small text-muted mb-2">Can be uploaded later or requested from the applicant.</p>
                    <?php endif; ?>
                    <?php if (in_array($application['status'], ['pending', 'under_review', 'approved'])): ?>
                    <form class="admin-doc-upload mt-2" data-type="<?= View::escape($type) ?>">
                        <input type="file" name="document" accept="image/*,.pdf" class="form-control form-control-sm mb-2" required>
                        <button type="submit" class="btn btn-sm btn-primary w-100">Upload Document</button>
                    </form>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
$canDelete = !($application['status'] === 'approved' && !empty($application['member_id']));
$canReview = in_array($application['status'], ['pending', 'under_review'], true);
?>
<div id="applicationPage" class="d-none"
     data-app-id="<?= (int) $application['id'] ?>"
     data-app-number="<?= View::escape($application['application_number']) ?>"
     data-can-delete="<?= $canDelete ? '1' : '0' ?>"
     data-can-review="<?= $canReview ? '1' : '0' ?>"></div>

<?php if ($canReview): ?>
<div class="d-flex gap-2 mb-2">
    <button class="btn btn-success flex-fill" id="approveBtn"><i class="bi bi-check-lg"></i> Approve</button>
    <button class="btn btn-danger flex-fill" id="rejectBtn"><i class="bi bi-x-lg"></i> Reject</button>
</div>
<?php endif; ?>

<?php if ($canDelete): ?>
<div class="d-grid">
    <button type="button" class="btn btn-outline-danger" id="deleteAppBtn">
        <i class="bi bi-trash"></i> Delete Application
    </button>
</div>
<?php endif; ?>
