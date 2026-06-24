<?php
use App\Core\View;

$pageTitle = 'Change Password';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Change Password</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($forceRequired)): ?>
                <div class="alert alert-warning">
                    You must change your password before continuing.
                </div>
                <?php endif; ?>

                <?php if (!empty($passwordChangedAt)): ?>
                <p class="text-muted small mb-3">
                    <i class="bi bi-clock-history"></i>
                    Last changed: <?= View::escape(date('d M Y, h:i A', strtotime($passwordChangedAt))) ?>
                </p>
                <?php else: ?>
                <p class="text-muted small mb-3">
                    <i class="bi bi-clock-history"></i>
                    Password has not been changed since account creation.
                </p>
                <?php endif; ?>

                <form id="changePasswordForm">
                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                    <div class="mb-3">
                        <label class="form-label">Current Password <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control" required autocomplete="current-password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="new_password" class="form-control" required minlength="8" autocomplete="new-password">
                        <div class="form-text">Minimum 8 characters.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" name="confirm_password" class="form-control" required minlength="8" autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check2-circle"></i> Update Password
                    </button>
                </form>
            </div>
        </div>

        <?php if (empty($forceRequired)): ?>
        <div class="text-center mt-3">
            <a href="<?= $base ?>/dashboard" class="btn btn-link">Back to Dashboard</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
$('#changePasswordForm').on('submit', function(e) {
    e.preventDefault();
    $.post(BASE_URL + '/settings/password', $(this).serialize(), function(res) {
        if (res.success) {
            Swal.fire('Success', res.message, 'success').then(function() {
                window.location.href = res.redirect || (BASE_URL + '/dashboard');
            });
        } else {
            Swal.fire('Error', res.message || 'Unable to update password.', 'error');
        }
    }).fail(function(xhr) {
        const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Unable to update password.';
        Swal.fire('Error', msg, 'error');
    });
});
</script>
