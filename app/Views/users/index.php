<?php
use App\Core\View;
use App\Helpers\Lang;

$pageTitle = 'Users';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<div class="mb-3"><?php View::heading('user_management', 'h5', 'person-gear'); ?></div>

<button class="btn btn-primary btn-sm mb-3 bilingual-btn" data-bs-toggle="modal" data-bs-target="#addUserModal">
    <span class="label-ta"><i class="bi bi-person-plus"></i> <?= View::escape(Lang::ui('add_user')['ta']) ?></span>
    <span class="label-en"><?= View::escape(Lang::ui('add_user')['en']) ?></span>
</button>

<div class="member-cards">
    <?php foreach ($users as $u): ?>
    <div class="member-card">
        <div class="card-body-content">
            <h6 class="mb-0"><?= View::escape($u['full_name']) ?></h6>
            <small class="text-muted">@<?= View::escape($u['username']) ?></small>
            <div class="mt-1">
                <span class="badge bg-primary"><?= View::escape($u['role_name']) ?></span>
                <span class="badge bg-<?= $u['is_active'] ? 'success' : 'secondary' ?>">
                    <?= View::escape($u['is_active'] ? Lang::ui('active')['ta'] : Lang::ui('pending')['ta']) ?>
                </span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form id="addUserForm">
            <div class="modal-header">
                <div class="bilingual-text bilingual-block flex-grow-1">
                    <?php View::text('add_user', 'h5', true, 'modal-title mb-0'); ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                <div class="mb-3">
                    <?php View::label('full_name_english', true); ?>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <?php View::label('username', true); ?>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <?php View::label('email', true); ?>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <?php View::label('password', true); ?>
                    <input type="password" name="password" class="form-control" required minlength="8">
                </div>
                <div class="mb-3">
                    <?php View::label('role', true); ?>
                    <select name="role_id" class="form-select" required>
                        <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= View::escape($r['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary bilingual-btn">
                    <span class="label-ta"><?= View::escape(Lang::ui('add_user')['ta']) ?></span>
                    <span class="label-en"><?= View::escape(Lang::ui('add_user')['en']) ?></span>
                </button>
            </div>
        </form>
    </div></div>
</div>
<script>
$('#addUserForm').on('submit', function(e) { e.preventDefault();
    $.post(BASE_URL + '/admin/users', $(this).serialize(), function(res) {
        if (res.success) { bootstrap.Modal.getInstance($('#addUserModal')).hide(); Swal.fire('வெற்றி / Created', res.message, 'success').then(() => location.reload()); }
    });
});
</script>
