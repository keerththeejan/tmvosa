<?php $pageTitle = 'Application Details'; $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><?= \App\Core\View::escape($application['application_number']) ?></span>
        <span class="badge bg-<?= match($application['status']) { 'approved' => 'success', 'rejected' => 'danger', 'pending' => 'warning', default => 'info' } ?>">
            <?= ucfirst(str_replace('_', ' ', $application['status'])) ?>
        </span>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12"><strong>Name:</strong> <?= \App\Core\View::escape($application['full_name_english']) ?></div>
            <?php if ($application['full_name_tamil']): ?>
            <div class="col-12"><strong>பெயர்:</strong> <?= \App\Core\View::escape($application['full_name_tamil']) ?></div>
            <?php endif; ?>
            <div class="col-6"><strong>NIC:</strong> <?= \App\Core\View::escape($application['nic_number'] ?? '-') ?></div>
            <div class="col-6"><strong>Mobile:</strong> <?= \App\Core\View::escape($application['mobile']) ?></div>
            <div class="col-6"><strong>Email:</strong> <?= \App\Core\View::escape($application['email'] ?? '-') ?></div>
            <div class="col-6"><strong>Country:</strong> <?= \App\Core\View::escape($application['country_name'] ?? '-') ?></div>
            <div class="col-6"><strong>Batch:</strong> <?= \App\Core\View::escape($application['studied_to_year'] ?? '-') ?></div>
            <div class="col-6"><strong>Occupation:</strong> <?= \App\Core\View::escape($application['occupation'] ?? '-') ?></div>
            <div class="col-6"><strong>Membership:</strong> <?= \App\Core\View::escape($application['membership_type_name'] ?? '') ?></div>
            <div class="col-6"><strong>Amount Paid:</strong> Rs. <?= number_format($application['amount_paid'] ?? 0, 2) ?></div>
        </div>
    </div>
</div>

<?php if (!empty($documents)): ?>
<div class="card mb-3">
    <div class="card-header">Documents</div>
    <div class="card-body">
        <div class="row g-2">
            <?php foreach ($documents as $doc): ?>
            <div class="col-4">
                <a href="<?= $base ?>/../storage/uploads/<?= $doc['file_path'] ?>" target="_blank" class="doc-thumb">
                    <i class="bi bi-file-earmark"></i>
                    <small><?= ucfirst(str_replace('_', ' ', $doc['document_type'])) ?></small>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (in_array($application['status'], ['pending', 'under_review'])): ?>
<div class="d-flex gap-2">
    <button class="btn btn-success flex-fill" id="approveBtn"><i class="bi bi-check-lg"></i> Approve</button>
    <button class="btn btn-danger flex-fill" id="rejectBtn"><i class="bi bi-x-lg"></i> Reject</button>
</div>
<script>
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
