<?php
use App\Core\View;
use App\Helpers\PaymentMethod;

$pageTitle = 'Edit Application';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h5 class="mb-0">Edit Application</h5>
    <a href="<?= $base ?>/applications/<?= (int) $application['id'] ?>" class="btn btn-sm btn-outline-secondary">Back</a>
</div>

<form id="editAppForm" class="card border-0 shadow-sm">
    <div class="card-body">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
        <div class="mb-3">
            <label class="form-label">Application No</label>
            <input type="text" class="form-control" value="<?= View::escape($application['application_number']) ?>" disabled>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Name (Tamil)</label>
                <input type="text" name="full_name_tamil" class="form-control" value="<?= View::escape($application['full_name_tamil'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Name (English)</label>
                <input type="text" name="full_name_english" class="form-control" value="<?= View::escape($application['full_name_english'] ?? '') ?>">
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-4">
                <label class="form-label">NIC</label>
                <input type="text" name="nic_number" class="form-control" value="<?= View::escape($application['nic_number'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Mobile</label>
                <input type="text" name="mobile" class="form-control" value="<?= View::escape($application['mobile'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= View::escape($application['email'] ?? '') ?>">
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-4">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <?php foreach (['male','female','other'] as $g): ?>
                    <option value="<?= $g ?>" <?= ($application['gender'] ?? '') === $g ? 'selected' : '' ?>><?= ucfirst($g) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">DOB</label>
                <input type="date" name="date_of_birth" class="form-control" value="<?= View::escape($application['date_of_birth'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <?php foreach (['pending','under_review','rejected'] as $st): ?>
                    <option value="<?= $st ?>" <?= ($application['status'] ?? '') === $st ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$st)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Country</label>
                <select name="country_id" class="form-select">
                    <option value="">Select</option>
                    <?php foreach ($countries as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= (int)($application['country_id'] ?? 0) === (int)$c['id'] ? 'selected' : '' ?>><?= View::escape($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Membership Type</label>
                <select name="membership_type_id" class="form-select">
                    <?php foreach ($membershipTypes as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= (int)($application['membership_type_id'] ?? 0) === (int)$t['id'] ? 'selected' : '' ?>><?= View::escape(\App\Helpers\MembershipType::optionLabel($t)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Address</label>
            <textarea name="current_address" class="form-control" rows="2"><?= View::escape($application['current_address'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Permanent Address</label>
            <textarea name="permanent_address" class="form-control" rows="2"><?= View::escape($application['permanent_address'] ?? '') ?></textarea>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-3">
                <label class="form-label">Studied From</label>
                <input type="number" name="studied_from_year" class="form-control" value="<?= View::escape($application['studied_from_year'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Studied To</label>
                <input type="number" name="studied_to_year" class="form-control" value="<?= View::escape($application['studied_to_year'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Occupation</label>
                <input type="text" name="occupation" class="form-control" value="<?= View::escape($application['occupation'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Company</label>
                <input type="text" name="company" class="form-control" value="<?= View::escape($application['company'] ?? '') ?>">
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-4">
                <label class="form-label">Amount Paid</label>
                <input type="number" step="0.01" name="amount_paid" class="form-control" value="<?= View::escape($application['amount_paid'] ?? 0) ?>">
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Payment Method</label>
                <select name="payment_method" class="form-select">
                    <?php foreach (PaymentMethod::options() as $opt): ?>
                    <option value="<?= $opt['value'] ?>" <?= ($application['payment_method'] ?? '') === $opt['value'] ? 'selected' : '' ?>><?= View::escape(PaymentMethod::display($opt['value'])) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label">Txn No</label>
                <input type="text" name="transaction_number" class="form-control" value="<?= View::escape($application['transaction_number'] ?? '') ?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Payment Date</label>
            <input type="date" name="payment_date" class="form-control" value="<?= View::escape($application['payment_date'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
    </div>
</form>

<script>
$('#editAppForm').on('submit', function(e) {
    e.preventDefault();
    $.post(BASE_URL + '/applications/<?= (int) $application['id'] ?>/update', $(this).serialize(), function(res) {
        if (res.success) Swal.fire('Saved', res.message, 'success').then(() => location.href = BASE_URL + '/applications/<?= (int) $application['id'] ?>');
        else Swal.fire('Error', res.message || 'Failed', 'error');
    });
});
</script>
