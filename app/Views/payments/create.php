<?php
use App\Core\View;
use App\Helpers\PaymentMethod;

$pageTitle = 'New Payment';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> New Payment</h5>
    <a href="<?= $base ?>/payments" class="btn btn-sm btn-outline-secondary">Back</a>
</div>

<form id="paymentForm" class="card border-0 shadow-sm">
    <div class="card-body">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div class="mb-3">
            <label class="form-label">Member <span class="text-danger">*</span></label>
            <select name="member_id" class="form-select" required>
                <option value="">Select member</option>
                <?php foreach ($members as $m): ?>
                <option value="<?= (int) $m['id'] ?>" <?= ((int) $selectedMemberId === (int) $m['id']) ? 'selected' : '' ?>>
                    <?= View::escape($m['membership_number'] . ' — ' . $m['full_name_english'] . ' (' . $m['status'] . ')') ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Amount (Rs.) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0.01" name="amount" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                <select name="payment_method" class="form-select" required>
                    <?php foreach (PaymentMethod::options() as $opt): ?>
                    <option value="<?= View::escape($opt['value']) ?>"><?= View::escape(PaymentMethod::display($opt['value'])) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Payment Type</label>
                <select name="payment_type" class="form-select">
                    <option value="registration">Registration</option>
                    <option value="renewal">Renewal</option>
                    <option value="annual">Annual</option>
                    <option value="donation">Donation</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Transaction / Reference No.</label>
            <input type="text" name="transaction_number" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="2"></textarea>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="auto_verify" value="1" id="autoVerify" checked>
            <label class="form-check-label" for="autoVerify">Mark as verified and generate receipt now</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-save"></i> Save Payment
        </button>
    </div>
</form>

<script>
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const data = new FormData(form);
    fetch(BASE_URL + '/payments', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-Token': CSRF_TOKEN },
        body: data
    }).then(r => r.json()).then(res => {
        if (res.success) {
            Swal.fire('Saved', res.message + (res.receipt_number ? (' Receipt: ' + res.receipt_number) : ''), 'success')
                .then(() => location.href = BASE_URL + '/payments');
        } else {
            Swal.fire('Error', res.message || 'Failed to save payment', 'error');
        }
    }).catch(() => Swal.fire('Error', 'Request failed', 'error'));
});
</script>
