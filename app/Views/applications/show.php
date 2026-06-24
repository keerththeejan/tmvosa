<?php
use App\Core\View;
use App\Helpers\Lang;

$pageTitle = 'Application Details';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$appBase = dirname($base);
if ($appBase === '/' || $appBase === '.') {
    $appBase = $base;
}
$docTypes = ['payment_slip', 'nic_copy', 'passport_photo'];
$documentsByType = $documentsByType ?? [];
?>
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
            <div class="col-6">
                <div class="bilingual-label mb-1">
                    <span class="label-ta fw-semibold"><?= View::escape(Lang::field('membership_type')['ta']) ?></span>
                    <span class="label-en"><?= View::escape(Lang::field('membership_type')['en']) ?></span>
                </div>
                <?php
                $membershipDisplay = Lang::membershipDisplayFromName($application['membership_type_name'] ?? '');
                ?>
                <div class="label-ta"><?= View::escape($membershipDisplay['with_validity_ta']) ?></div>
                <div class="label-en text-muted"><?= View::escape($membershipDisplay['with_validity_en']) ?></div>
            </div>
            <div class="col-6">
                <div class="bilingual-label mb-1">
                    <span class="label-ta fw-semibold"><?= View::escape(Lang::field('validity_period')['ta']) ?></span>
                    <span class="label-en"><?= View::escape(Lang::field('validity_period')['en']) ?></span>
                </div>
                <div class="label-ta"><?= View::escape($membershipDisplay['validity_ta']) ?></div>
                <div class="label-en text-muted"><?= View::escape($membershipDisplay['validity_en']) ?></div>
            </div>
            <div class="col-6"><strong>Amount Paid:</strong> Rs. <?= number_format($application['amount_paid'] ?? 0, 2) ?></div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <?php View::heading('documents', 'h6', 'folder2', 'mb-0'); ?>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <?php foreach ($docTypes as $type):
                $doc = $documentsByType[$type] ?? null;
                $label = Lang::field($type);
                $isOptional = in_array($type, ['nic_copy', 'passport_photo'], true);
            ?>
            <div class="col-12 col-md-4">
                <div class="doc-status-card h-100 p-3 border rounded">
                    <div class="bilingual-label mb-2">
                        <span class="label-ta fw-semibold"><?= View::escape($label['ta']) ?></span>
                        <span class="label-en"><?= View::escape($label['en']) ?></span>
                    </div>
                    <?php if ($doc): ?>
                    <span class="badge bg-success mb-2 bilingual-text">
                        <span class="label-ta"><?= View::escape(Lang::ui('uploaded')['ta']) ?></span>
                        <span class="label-en"><?= View::escape(Lang::ui('uploaded')['en']) ?></span>
                    </span>
                    <?php
                    $fileName = basename($doc['file_path']);
                    $fileUrl = $appBase . '/files/documents/' . rawurlencode($fileName);
                    ?>
                    <div>
                        <a href="<?= View::escape($fileUrl) ?>" target="_blank" class="btn btn-sm btn-outline-primary bilingual-btn">
                            <span class="label-ta"><?= View::escape(Lang::ui('view_document')['ta']) ?></span>
                            <span class="label-en"><?= View::escape(Lang::ui('view_document')['en']) ?></span>
                        </a>
                    </div>
                    <?php else: ?>
                    <span class="badge bg-secondary mb-2 bilingual-text">
                        <span class="label-ta"><?= View::escape(Lang::ui('not_uploaded')['ta']) ?></span>
                        <span class="label-en"><?= View::escape(Lang::ui('not_uploaded')['en']) ?></span>
                    </span>
                    <?php if ($isOptional): ?>
                    <p class="small text-muted mb-2 bilingual-text bilingual-block">
                        <span class="label-ta"><?= View::escape(Lang::ui('upload_later_hint')['ta']) ?></span>
                        <span class="label-en"><?= View::escape(Lang::ui('upload_later_hint')['en']) ?></span>
                    </p>
                    <?php endif; ?>
                    <?php if (in_array($application['status'], ['pending', 'under_review', 'approved'])): ?>
                    <form class="admin-doc-upload mt-2" data-type="<?= View::escape($type) ?>">
                        <input type="file" name="document" accept="image/*,.pdf" class="form-control form-control-sm mb-2" required>
                        <button type="submit" class="btn btn-sm btn-primary w-100 bilingual-btn">
                            <span class="label-ta"><?= View::escape(Lang::ui('upload_document')['ta']) ?></span>
                            <span class="label-en"><?= View::escape(Lang::ui('upload_document')['en']) ?></span>
                        </button>
                    </form>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if (in_array($application['status'], ['pending', 'under_review'])): ?>
<div class="d-flex gap-2">
    <button class="btn btn-success flex-fill" id="approveBtn"><i class="bi bi-check-lg"></i> Approve</button>
    <button class="btn btn-danger flex-fill" id="rejectBtn"><i class="bi bi-x-lg"></i> Reject</button>
</div>
<script>
$('.admin-doc-upload').on('submit', function(e) {
    e.preventDefault();
    const $form = $(this);
    const fd = new FormData();
    fd.append('document_type', $form.data('type'));
    fd.append('document', $form.find('input[type="file"]')[0].files[0]);
    fd.append('_csrf_token', CSRF_TOKEN);
    $.ajax({
        url: BASE_URL + '/applications/<?= (int) $application['id'] ?>/documents',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        success: function(res) {
            if (res.success) {
                Swal.fire('Uploaded', res.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        },
        error: function(xhr) {
            const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Upload failed.';
            Swal.fire('Error', msg, 'error');
        }
    });
});

$('#approveBtn').on('click', function() {
    Swal.fire({ title: 'Approve Application?', icon: 'question', showCancelButton: true, confirmButtonText: 'Approve' })
    .then(r => { if (r.isConfirmed) {
        $.post(BASE_URL + '/applications/<?= $application['id'] ?>/approve', { _csrf_token: CSRF_TOKEN }, function(res) {
            if (res.success) Swal.fire('Approved!', 'Membership: ' + res.membership_number, 'success').then(() => location.reload());
            else Swal.fire('Error', res.message, 'error');
        });
    }});
});
$('#rejectBtn').on('click', function() {
    Swal.fire({ title: 'Reject Application', input: 'textarea', inputLabel: 'Reason', showCancelButton: true })
    .then(r => { if (r.isConfirmed) {
        $.post(BASE_URL + '/applications/<?= $application['id'] ?>/reject', { _csrf_token: CSRF_TOKEN, reason: r.value }, function(res) {
            if (res.success) Swal.fire('Rejected', res.message, 'info').then(() => location.reload());
        });
    }});
});
</script>
<?php endif; ?>
