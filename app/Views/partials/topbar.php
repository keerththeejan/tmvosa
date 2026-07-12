<?php
use App\Core\View;

$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<header class="topbar d-flex align-items-center justify-content-between gap-2">
    <div class="d-flex align-items-center gap-2 min-w-0 flex-grow-1">
        <button type="button"
                class="btn btn-light border d-lg-none sidebar-toggle-btn"
                id="sidebarToggle"
                aria-label="Open menu"
                aria-controls="sidebar"
                aria-expanded="false">
            <i class="bi bi-list fs-4"></i>
        </button>
        <h5 class="mb-0 page-title text-truncate"><?= View::escape($pageTitle ?? __('dashboard')) ?></h5>
    </div>
    <div class="d-flex align-items-center gap-2 flex-shrink-0">
        <?php View::partial('language-switcher', ['variant' => 'light']); ?>
        <div class="dropdown">
            <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-1 topbar-user-btn"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                <i class="bi bi-person-circle fs-5"></i>
                <span class="topbar-user-name d-none d-sm-inline text-truncate"><?= View::escape($user['full_name'] ?? '') ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><span class="dropdown-item-text text-muted small"><?= View::escape($user['role_name'] ?? '') ?></span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="<?= $base ?>/settings/password">
                        <i class="bi bi-shield-lock"></i>
                        <?= View::escape(__('change_password')) ?>
                    </a>
                </li>
                <?php if (\App\Core\Auth::hasRole('super_admin')): ?>
                <li>
                    <a class="dropdown-item" href="<?= $base ?>/admin/email-settings">
                        <i class="bi bi-envelope-at"></i>
                        <?= View::escape(__('email_settings')) ?>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?= $base ?>/admin/settings">
                        <i class="bi bi-gear"></i>
                        <?= View::escape(__('settings')) ?>
                    </a>
                </li>
                <?php endif; ?>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="<?= $base ?>/logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <?= View::escape(__('logout')) ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
