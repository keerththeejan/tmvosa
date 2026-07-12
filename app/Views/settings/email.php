<?php
use App\Core\View;

$pageTitle = 'Email Settings';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$s = $emailSettings ?? [];
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h5 class="mb-0"><i class="bi bi-envelope-at"></i> Email Settings</h5>
    <a href="<?= $base ?>/admin/email-templates" class="btn btn-outline-primary btn-sm">Email Templates</a>
</div>

<div class="alert alert-info">
    <i class="bi bi-shield-lock"></i>
    <strong>Security:</strong> SMTP password is stored in <code>.env</code> as <code>SMTP_PASSWORD</code> — never in the database or source code.
</div>

<?php $diag = $mailDiagnostics ?? []; ?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white"><h6 class="mb-0">Server mail status</h6></div>
    <div class="card-body small">
        <ul class="mb-0">
            <li><strong>.env file on server:</strong> <?= !empty($diag['env_file_exists']) ? 'Found' : '<span class="text-danger">Missing — upload .env to the project root</span>' ?></li>
            <li><strong>Composer vendor/ (PHPMailer):</strong> <?= !empty($diag['vendor_installed']) ? 'Installed' : '<span class="text-danger">Missing — run composer install or upload vendor/ folder</span>' ?></li>
            <li><strong>SMTP password in .env:</strong> <?= !empty($diag['smtp_password_set']) ? 'Set' : '<span class="text-danger">Not set — add SMTP_PASSWORD</span>' ?></li>
            <li><strong>PHP openssl:</strong> <?= !empty($diag['openssl_loaded']) ? 'Enabled' : '<span class="text-danger">Disabled — enable in cPanel</span>' ?></li>
            <li><strong>Current host:</strong> <?= View::escape(($diag['smtp_host'] ?? '') . ':' . ($diag['smtp_port'] ?? '')) ?> (<?= View::escape($diag['smtp_encryption'] ?? '') ?>)</li>
        </ul>
        <?php if (!empty($diag['config_error'])): ?>
        <div class="alert alert-danger mt-3 mb-0 py-2"><?= View::escape($diag['config_error']) ?></div>
        <?php elseif (!empty($diag['cpanel_hint'])): ?>
        <div class="alert alert-warning mt-3 mb-0 py-2"><?= View::escape($diag['cpanel_hint']) ?></div>
        <?php endif; ?>
    </div>
</div>

<form id="emailSettingsForm" class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white"><h6 class="mb-0">SMTP Configuration</h6></div>
    <div class="card-body">
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label">SMTP Host</label>
                <input type="text" name="smtp_host" class="form-control" value="<?= View::escape($s['smtp_host'] ?? 'mail.vkitnet.info') ?>" required>
                <div class="form-text">Use <strong>mail.vkitnet.info</strong> or on cPanel try <strong>localhost</strong> with port <strong>587</strong> (TLS).</div>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">SMTP Port</label>
                <input type="number" name="smtp_port" class="form-control" value="<?= View::escape((string) ($s['smtp_port'] ?? 465)) ?>" required>
            </div>
            <div class="col-12 col-md-3">
                <label class="form-label">Encryption</label>
                <select name="smtp_encryption" class="form-select">
                    <option value="ssl" <?= ($s['smtp_encryption'] ?? 'ssl') === 'ssl' ? 'selected' : '' ?>>SSL (465)</option>
                    <option value="tls" <?= ($s['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS (587)</option>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">SMTP Username</label>
                <input type="email" name="smtp_username" class="form-control" value="<?= View::escape($s['smtp_username'] ?? 'tmvosa@vkitnet.info') ?>">
                <div class="form-text">Usually the full email address. Password is read from .env.</div>
            </div>
            <div class="col-12 col-md-6">
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
    <button type="button" class="btn btn-outline-secondary" id="applyCpanelSmtpBtn">
        Use cPanel SMTP (localhost:587 TLS)
    </button>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white"><h6 class="mb-0">Test Email Configuration</h6></div>
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-8">
                <label class="form-label">Send test email to</label>
                <input type="email" id="testEmailAddress" class="form-control" placeholder="tmvosa@vkitnet.info" value="<?= View::escape($user['email'] ?? '') ?>">
            </div>
            <div class="col-12 col-md-4">
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
            <div class="col-12 col-md-4">
                <label class="form-label">Days ahead</label>
                <input type="number" id="expiryReminderDays" class="form-control" value="30" min="1" max="365">
            </div>
            <div class="col-12 col-md-4">
                <button type="button" class="btn btn-outline-warning w-100" id="sendExpiryRemindersBtn">Send Expiry Reminders</button>
            </div>
        </div>
    </div>
</div>
