<?php
use App\Core\View;

$pageTitle = 'Email Templates';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$labels = [
    'application_received' => 'Application Submitted',
    'application_approved' => 'Application Approved',
    'application_rejected' => 'Application Rejected',
    'welcome_email' => 'Welcome Email',
    'membership_activated' => 'Membership Activated',
    'password_reset' => 'Password Reset',
    'password_changed_confirmation' => 'Change Password Confirmation',
    'membership_expiry_reminder' => 'Membership Expiry Reminder',
    'admin_notification' => 'Admin Notification',
    'payment_verified' => 'Payment Verified',
];
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h5 class="mb-0"><i class="bi bi-envelope-paper"></i> Email Templates</h5>
    <a href="<?= $base ?>/admin/email-settings" class="btn btn-outline-secondary btn-sm">Email Settings</a>
</div>

<p class="text-muted small">Use variables like <code>{{full_name}}</code>, <code>{{application_number}}</code> in subject and body.</p>

<?php foreach ($templates as $template):
    $vars = json_decode($template['variables'] ?? '[]', true) ?: [];
    $label = $labels[$template['name']] ?? ucwords(str_replace('_', ' ', $template['name']));
?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h6 class="mb-0"><?= View::escape($label) ?></h6>
        <span class="badge bg-<?= $template['is_active'] ? 'success' : 'secondary' ?>"><?= $template['is_active'] ? 'Active' : 'Inactive' ?></span>
    </div>
    <div class="card-body">
        <form class="email-template-form" data-id="<?= (int) $template['id'] ?>">
            <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
            <div class="mb-2">
                <label class="form-label small text-muted">Template Key: <?= View::escape($template['name']) ?></label>
            </div>
            <?php if ($vars): ?>
            <p class="small text-muted">Variables: <?= View::escape(implode(', ', array_map(fn($v) => '{{' . $v . '}}', $vars))) ?></p>
            <?php endif; ?>
            <div class="mb-3">
                <label class="form-label">Subject</label>
                <input type="text" name="subject" class="form-control" value="<?= View::escape($template['subject']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Body (HTML allowed)</label>
                <textarea name="body" class="form-control" rows="6" required><?= View::escape($template['body']) ?></textarea>
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" name="is_active" id="active_<?= (int) $template['id'] ?>" <?= $template['is_active'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="active_<?= (int) $template['id'] ?>">Active</label>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Save Template</button>
        </form>
    </div>
</div>
<?php endforeach; ?>

