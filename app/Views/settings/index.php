<?php $pageTitle = 'Settings'; $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>
<form id="settingsForm">
    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
    <?php foreach ($settings as $group => $items): ?>
    <div class="card mb-3"><div class="card-header text-capitalize"><?= \App\Core\View::escape($group) ?></div><div class="card-body">
        <?php foreach ($items as $item): ?>
        <div class="mb-3">
            <label class="form-label"><?= \App\Core\View::escape($item['description'] ?? $item['setting_key']) ?></label>
            <input type="text" name="settings[<?= $item['setting_key'] ?>]" class="form-control" value="<?= \App\Core\View::escape($item['setting_value'] ?? '') ?>">
        </div>
        <?php endforeach; ?>
    </div></div>
    <?php endforeach; ?>
    <button type="submit" class="btn btn-primary w-100 mb-3">Save Settings</button>
</form>
<a href="<?= $base ?>/admin/backup" class="btn btn-outline-danger w-100"><i class="bi bi-download"></i> Download Database Backup</a>
<script>
$('#settingsForm').on('submit', function(e) { e.preventDefault();
    $.post(BASE_URL + '/admin/settings', $(this).serialize(), function(res) {
        Swal.fire(res.success ? 'Saved' : 'Error', res.message, res.success ? 'success' : 'error');
    });
});
</script>
