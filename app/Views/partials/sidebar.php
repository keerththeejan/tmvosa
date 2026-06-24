<?php $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>
<nav class="sidebar d-none d-lg-block" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-circle"><i class="bi bi-mortarboard-fill"></i></div>
        <h6 class="mb-0 text-white">OSA Alumni</h6>
        <small class="text-white-50">Membership System</small>
    </div>
    <ul class="sidebar-nav">
        <?php
        $navItems = [
            ['dashboard', 'speedometer2', 'Dashboard'],
            ['applications', 'file-earmark-text', 'Applications'],
            ['members', 'people', 'Members'],
            ['payments', 'credit-card', 'Payments'],
            ['membership-cards', 'person-vcard', 'Membership Cards'],
            ['reports', 'bar-chart', 'Reports'],
        ];
        foreach ($navItems as [$path, $icon, $label]):
            $active = str_contains($_SERVER['REQUEST_URI'], $path);
        ?>
        <li>
            <a href="<?= $base ?>/<?= $path ?>" class="<?= $active ? 'active' : '' ?>">
                <i class="bi bi-<?= $icon ?>"></i>
                <?= htmlspecialchars($label) ?>
            </a>
        </li>
        <?php endforeach; ?>
        <?php if (\App\Core\Auth::hasRole('super_admin')): ?>
        <li class="sidebar-divider"></li>
        <li><a href="<?= $base ?>/admin/users"><i class="bi bi-person-gear"></i> Users</a></li>
        <li><a href="<?= $base ?>/applications/duplicates"><i class="bi bi-exclamation-triangle"></i> Duplicate NICs</a></li>
        <li><a href="<?= $base ?>/admin/settings"><i class="bi bi-gear"></i> Settings</a></li>
        <li><a href="<?= $base ?>/admin/email-settings"><i class="bi bi-envelope-at"></i> Email Settings</a></li>
        <li><a href="<?= $base ?>/admin/audit-logs"><i class="bi bi-journal-text"></i> Audit Logs</a></li>
        <?php endif; ?>
    </ul>
</nav>
