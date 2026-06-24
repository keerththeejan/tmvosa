<?php
use App\Core\View;

$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<header class="topbar d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-link text-dark d-lg-none p-0" id="sidebarToggle"><i class="bi bi-list fs-4"></i></button>
        <h5 class="mb-0 page-title"><?= View::escape($pageTitle ?? 'Dashboard') ?></h5>
    </div>
    <div class="dropdown">
        <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i> <?= View::escape($user['full_name'] ?? '') ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><span class="dropdown-item-text text-muted small"><?= View::escape($user['role_name'] ?? '') ?></span></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="<?= $base ?>/settings/password">
                    <i class="bi bi-shield-lock"></i> Change Password
                </a>
            </li>
            <?php if (\App\Core\Auth::hasRole('super_admin')): ?>
            <li>
                <a class="dropdown-item" href="<?= $base ?>/admin/email-settings">
                    <i class="bi bi-envelope-at"></i> Email Settings
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="<?= $base ?>/admin/settings">
                    <i class="bi bi-gear"></i> Settings
                </a>
            </li>
            <?php endif; ?>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="<?= $base ?>/logout">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</header>
