<?php
use App\Core\View;

$pageTitle = 'Users';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-person-gear"></i> User Management</h5>
    <a href="<?= $base ?>/admin/password-logs" class="btn btn-outline-secondary btn-sm">Password Logs</a>
</div>

<button class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
    <i class="bi bi-person-plus"></i> Add User
</button>

<div class="member-cards">
    <?php foreach ($users as $u): ?>
    <div class="member-card">
        <div class="card-body-content flex-grow-1">
            <h6 class="mb-0"><?= View::escape($u['full_name']) ?></h6>
            <small class="text-muted">@<?= View::escape($u['username']) ?></small>
            <div class="mt-1">
                <span class="badge bg-primary"><?= View::escape($u['role_name']) ?></span>
                <span class="badge bg-<?= $u['is_active'] ? 'success' : 'secondary' ?>"><?= $u['is_active'] ? 'Active' : 'Inactive' ?></span>
                <?php if (!empty($u['force_password_change'])): ?>
                <span class="badge bg-warning text-dark">Must Change Password</span>
                <?php endif; ?>
            </div>
            <?php if (!empty($u['password_changed_at'])): ?>
            <small class="text-muted d-block mt-1">Password changed: <?= date('d M Y', strtotime($u['password_changed_at'])) ?></small>
            <?php endif; ?>
        </div>
        <div class="d-flex flex-column gap-1">
            <button type="button" class="btn btn-sm btn-outline-warning reset-password-btn" data-id="<?= (int) $u['id'] ?>" data-name="<?= View::escape($u['full_name']) ?>">
                Reset Password
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary force-password-btn" data-id="<?= (int) $u['id'] ?>" data-force="<?= !empty($u['force_password_change']) ? '1' : '0' ?>">
                <?= !empty($u['force_password_change']) ? 'Clear Force Change' : 'Force Password Change' ?>
            </button>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form id="addUserForm">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
                <div class="mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="full_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username *</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" required minlength="8">
                </div>
                <div class="mb-3">
                    <label class="form-label">Role *</label>
                    <select name="role_id" class="form-select" required>
                        <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id'] ?>"><?= View::escape($r['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Add User</button>
            </div>
        </form>
    </div></div>
</div>

<script>
$('#addUserForm').on('submit', function(e) {
    e.preventDefault();
    $.post(BASE_URL + '/admin/users', $(this).serialize(), function(res) {
        if (res.success) {
            bootstrap.Modal.getInstance($('#addUserModal')).hide();
            Swal.fire('Created', res.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Error', res.message || 'Unable to create user.', 'error');
        }
    });
});

$('.reset-password-btn').on('click', function() {
    const userId = $(this).data('id');
    const userName = $(this).data('name');
    Swal.fire({
        title: 'Reset Password',
        html: 'Set a new password for <strong>' + userName + '</strong>',
        input: 'password',
        inputLabel: 'New Password (min 8 characters)',
        inputAttributes: { minlength: 8, autocomplete: 'new-password' },
        showCancelButton: true,
        confirmButtonText: 'Reset Password',
        preConfirm: (value) => {
            if (!value || value.length < 8) {
                Swal.showValidationMessage('Password must be at least 8 characters.');
            }
            return value;
        }
    }).then((result) => {
        if (!result.isConfirmed) return;
        Swal.fire({
            title: 'Force password change on next login?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, force change',
            cancelButtonText: 'No'
        }).then((forceResult) => {
            $.post(BASE_URL + '/admin/users/' + userId + '/reset-password', {
                _csrf_token: CSRF_TOKEN,
                new_password: result.value,
                force_password_change: forceResult.isConfirmed ? 1 : 0
            }, function(res) {
                if (res.success) {
                    Swal.fire('Success', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        });
    });
});

$('.force-password-btn').on('click', function() {
    const userId = $(this).data('id');
    const currentlyForced = $(this).data('force') === 1 || $(this).data('force') === '1';
    $.post(BASE_URL + '/admin/users/' + userId + '/force-password-change', {
        _csrf_token: CSRF_TOKEN,
        force: currentlyForced ? 0 : 1
    }, function(res) {
        if (res.success) {
            Swal.fire('Updated', res.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    });
});
</script>
