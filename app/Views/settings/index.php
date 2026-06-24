<?php $pageTitle = 'Settings'; $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-shield-lock"></i> Account Security</h6>
                <p class="text-muted small">Update your login password.</p>
                <a href="<?= $base ?>/settings/password" class="btn btn-outline-primary btn-sm">Change Password</a>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-envelope-at"></i> Email Settings</h6>
                <p class="text-muted small">Configure SMTP, sender details, and test email.</p>
                <a href="<?= $base ?>/admin/email-settings" class="btn btn-outline-primary btn-sm">Email Settings</a>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-envelope-paper"></i> Email Templates</h6>
                <p class="text-muted small">Edit system email templates.</p>
                <a href="<?= $base ?>/admin/email-templates" class="btn btn-outline-primary btn-sm">Manage Templates</a>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title"><i class="bi bi-journal-text"></i> Password Logs</h6>
                <p class="text-muted small">View password change and reset activity.</p>
                <a href="<?= $base ?>/admin/password-logs" class="btn btn-outline-secondary btn-sm">View Password Logs</a>
            </div>
        </div>
    </div>
</div>

<form id="settingsForm">
    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
    <?php foreach ($settings as $group => $items): ?>
    <div class="card mb-3 border-0 shadow-sm"><div class="card-header bg-white text-capitalize"><?= \App\Core\View::escape($group) ?></div><div class="card-body">
        <?php foreach ($items as $item): ?>
        <div class="mb-3">
            <label class="form-label"><?= \App\Core\View::escape($item['description'] ?? $item['setting_key']) ?></label>
            <input type="text" name="settings[<?= $item['setting_key'] ?>]" class="form-control" value="<?= \App\Core\View::escape($item['setting_value'] ?? '') ?>">
        </div>
        <?php endforeach; ?>
    </div></div>
    <?php endforeach; ?>
    <?php if (!empty($settings)): ?>
    <button type="submit" class="btn btn-primary w-100 mb-3">Save Settings</button>
    <?php endif; ?>
</form>
<a href="<?= $base ?>/admin/backup" class="btn btn-outline-danger w-100"><i class="bi bi-download"></i> Download Database Backup</a>
