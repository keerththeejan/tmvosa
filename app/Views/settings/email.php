<?php
use App\Core\View;

$pageTitle = 'Email Settings';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$s = $emailSettings ?? [];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-envelope-at"></i> Email Settings</h5>
    <a href="<?= $base ?>/admin/email-templates" class="btn btn-outline-primary btn-sm">Email Templates</a>
</div>

<div class="alert alert-info">
    <i class="bi bi-shield-lock"></i>
    <strong>Security:</strong> SMTP password is stored in <code>.env</code> as <code>SMTP_PASSWORD</code> — never in the database or source code.
</div>

<form id="emailSettingsForm" class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white"><h6 class="mb-0">SMTP Configuration</h6></div>
    <div class="card-body">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">SMTP Host</label>
                <input type="text" name="smtp_host" class="form-control" value="<?= View::escape($s['smtp_host'] ?? 'mail.vkitnet.info') ?>" required>
                <div class="form-text">Use <strong>mail.vkitnet.info</strong> (not vkitnet.info).</div>
            </div>
            <div class="col-md-3">
                <label class="form-label">SMTP Port</label>
                <input type="number" name="smtp_port" class="form-control" value="<?= View::escape((string) ($s['smtp_port'] ?? 465)) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Encryption</label>
                <select name="smtp_encryption" class="form-select">
                    <option value="ssl" <?= ($s['smtp_encryption'] ?? 'ssl') === 'ssl' ? 'selected' : '' ?>>SSL (465)</option>
                    <option value="tls" <?= ($s['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS (587)</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">SMTP Username</label>
                <input type="email" name="smtp_username" class="form-control" value="<?= View::escape($s['smtp_username'] ?? 'tmvosa@vkitnet.info') ?>">
                <div class="form-text">Usually the full email address. Password is read from .env.</div>
            </div>
            <div class="col-md-6">
                <label class="form-label">SMTP Password</label>
                <input type="password" class="form-control" value="••••••••" disabled>
                <div class="form-text">Set <code>SMTP_PASSWORD</code> in your <code>.env</code> file.</div>
            </div>
        </div>
    </div>
</form>

<form id="senderSettingsForm" class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white"><h6 class="mb-0">Sender Information</h6></div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Sender Name</label>
            <input type="text" name="from_name" class="form-control" form="emailSettingsForm" value="<?= View::escape($s['from_name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Sender Email</label>
            <input type="email" name="from_email" class="form-control" form="emailSettingsForm" value="<?= View::escape($s['from_email'] ?? 'tmvosa@vkitnet.info') ?>" required>
        </div>
        <div class="mb-0">
            <label class="form-label">Admin Notification Email</label>
            <input type="email" name="admin_notification_email" class="form-control" form="emailSettingsForm" value="<?= View::escape($s['admin_notification_email'] ?? 'tmvosa@vkitnet.info') ?>">
            <div class="form-text">Receives new application and system notifications.</div>
        </div>
    </div>
</form>

<div class="d-grid gap-2 d-md-flex mb-3">
    <button type="button" class="btn btn-primary" id="saveEmailSettingsBtn">Save Email Settings</button>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white"><h6 class="mb-0">Test Email Configuration</h6></div>
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-8">
                <label class="form-label">Send test email to</label>
                <input type="email" id="testEmailAddress" class="form-control" placeholder="tmvosa@vkitnet.info" value="<?= View::escape($user['email'] ?? '') ?>">
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-outline-success w-100" id="sendTestEmailBtn">
                    <i class="bi bi-send"></i> Send Test Email
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h6 class="mb-0">Membership Expiry Reminders</h6></div>
    <div class="card-body">
        <p class="text-muted small">Send expiry reminder emails to active members expiring within the selected period.</p>
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Days ahead</label>
                <input type="number" id="expiryReminderDays" class="form-control" value="30" min="1" max="365">
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-outline-warning w-100" id="sendExpiryRemindersBtn">Send Expiry Reminders</button>
            </div>
        </div>
    </div>
</div>

<script>
function collectEmailSettings() {
    const $form = $('#emailSettingsForm');
    return {
        _csrf_token: CSRF_TOKEN,
        smtp_host: $form.find('[name=smtp_host]').val(),
        smtp_port: $form.find('[name=smtp_port]').val(),
        smtp_encryption: $form.find('[name=smtp_encryption]').val(),
        smtp_username: $form.find('[name=smtp_username]').val(),
        from_name: $('[name=from_name]').val(),
        from_email: $('[name=from_email]').val(),
        admin_notification_email: $('[name=admin_notification_email]').val()
    };
}

$('#saveEmailSettingsBtn').on('click', function() {
    $.post(BASE_URL + '/admin/email-settings', collectEmailSettings(), function(res) {
        Swal.fire(res.success ? 'Saved' : 'Error', res.message, res.success ? 'success' : 'error');
    });
});

$('#sendTestEmailBtn').on('click', function() {
    const $btn = $(this).prop('disabled', true);
    $.post(BASE_URL + '/admin/email-settings/test', {
        _csrf_token: CSRF_TOKEN,
        test_email: $('#testEmailAddress').val()
    }, function(res) {
        Swal.fire(res.success ? 'Sent' : 'Failed', res.message, res.success ? 'success' : 'error');
    }).fail(function(xhr) {
        const res = xhr.responseJSON || {};
        const msg = res.message || res.error || 'Test email failed.';
        Swal.fire('Failed', msg, 'error');
    }).always(function() {
        $btn.prop('disabled', false);
    });
});

$('#sendExpiryRemindersBtn').on('click', function() {
    Swal.fire({
        title: 'Send expiry reminders?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Send'
    }).then(function(result) {
        if (!result.isConfirmed) return;
        $.post(BASE_URL + '/admin/email/send-expiry-reminders', {
            _csrf_token: CSRF_TOKEN,
            days: $('#expiryReminderDays').val()
        }, function(res) {
            Swal.fire(res.success ? 'Done' : 'Error', res.message, res.success ? 'success' : 'error');
        });
    });
});
</script>
