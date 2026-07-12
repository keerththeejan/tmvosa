<?php
use App\Core\View;

$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<nav class="sidebar" id="sidebar" aria-label="Main navigation">
    <div class="sidebar-header">
        <button type="button" class="btn btn-link text-white sidebar-close d-lg-none p-0" id="sidebarClose" aria-label="Close menu">
            <i class="bi bi-x-lg fs-4"></i>
        </button>
        <div class="logo-circle"><i class="bi bi-mortarboard-fill"></i></div>
        <h6 class="mb-0 text-white">OSA Alumni</h6>
        <small class="text-white-50"><?= View::escape(__('app_title')) ?></small>
    </div>
    <ul class="sidebar-nav">
        <?php
        $navItems = [
            ['dashboard', 'speedometer2', 'dashboard'],
            ['applications', 'file-earmark-text', 'applications'],
            ['members', 'people', 'members'],
            ['payments', 'credit-card', 'payments'],
            ['membership-cards', 'person-vcard', 'membership_cards'],
            ['reports', 'bar-chart', 'reports'],
        ];
        foreach ($navItems as [$path, $icon, $key]):
            $active = str_contains($_SERVER['REQUEST_URI'], $path);
        ?>
        <li>
            <a href="<?= $base ?>/<?= $path ?>" class="<?= $active ? 'active' : '' ?>">
                <i class="bi bi-<?= $icon ?>"></i>
                <span><?= View::escape(__($key)) ?></span>
            </a>
        </li>
        <?php endforeach; ?>
        <?php if (\App\Core\Auth::hasRole('super_admin')): ?>
        <li class="sidebar-divider"></li>
        <?php
        $adminItems = [
            ['admin/users', 'person-gear', 'users'],
            ['applications/duplicates', 'exclamation-triangle', 'duplicate_nics'],
            ['admin/settings', 'gear', 'settings'],
            ['admin/email-settings', 'envelope-at', 'email_settings'],
            ['admin/audit-logs', 'journal-text', 'audit_logs'],
        ];
        foreach ($adminItems as [$path, $icon, $key]):
        ?>
        <li>
            <a href="<?= $base ?>/<?= $path ?>">
                <i class="bi bi-<?= $icon ?>"></i>
                <span><?= View::escape(__($key)) ?></span>
            </a>
        </li>
        <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</nav>
<div class="sidebar-backdrop" id="sidebarBackdrop" hidden></div>
