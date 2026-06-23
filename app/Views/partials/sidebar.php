<?php $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>
<nav class="sidebar d-none d-lg-block" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-circle"><i class="bi bi-mortarboard-fill"></i></div>
        <h6 class="mb-0 text-white bilingual-text bilingual-block">
            <span class="label-ta">பழைய மாணவர் சங்கம்</span>
            <span class="label-en">OSA Alumni</span>
        </h6>
        <small class="text-white-50 bilingual-text bilingual-block">
            <span class="label-ta">உறுப்பினர் மேலாண்மை</span>
            <span class="label-en">Membership System</span>
        </small>
    </div>
    <ul class="sidebar-nav">
        <?php
        $navItems = [
            ['dashboard', 'speedometer2', 'dashboard'],
            ['applications', 'file-earmark-text', 'applications'],
            ['members', 'people', 'members'],
            ['payments', 'credit-card', 'payments'],
            ['reports', 'bar-chart', 'reports'],
        ];
        foreach ($navItems as [$path, $icon, $key]):
            $active = str_contains($_SERVER['REQUEST_URI'], $path);
        ?>
        <li>
            <a href="<?= $base ?>/<?= $path ?>" class="<?= $active ? 'active' : '' ?>">
                <i class="bi bi-<?= $icon ?>"></i>
                <?php \App\Core\View::text($key, 'span', true); ?>
            </a>
        </li>
        <?php endforeach; ?>
        <?php if (\App\Core\Auth::hasRole('super_admin')): ?>
        <li class="sidebar-divider"></li>
        <li><a href="<?= $base ?>/admin/users"><i class="bi bi-person-gear"></i> <?php \App\Core\View::text('users', 'span', true); ?></a></li>
        <li><a href="<?= $base ?>/admin/settings"><i class="bi bi-gear"></i> <?php \App\Core\View::text('settings', 'span', true); ?></a></li>
        <li><a href="<?= $base ?>/admin/audit-logs"><i class="bi bi-journal-text"></i> <?php \App\Core\View::text('audit_logs', 'span', true); ?></a></li>
        <?php endif; ?>
    </ul>
</nav>
