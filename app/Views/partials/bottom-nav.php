<?php $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>
<nav class="bottom-nav d-lg-none">
    <?php
    $navItems = [
        ['dashboard', 'speedometer2', 'home'],
        ['applications', 'file-earmark-text', 'apps_short'],
        ['members', 'people', 'members'],
        ['payments', 'credit-card', 'pay_short'],
        ['reports', 'bar-chart', 'reports'],
    ];
    foreach ($navItems as [$path, $icon, $key]):
        $active = str_contains($_SERVER['REQUEST_URI'], $path);
    ?>
    <a href="<?= $base ?>/<?= $path ?>" class="<?= $active ? 'active' : '' ?>">
        <i class="bi bi-<?= $icon ?>"></i>
        <?php \App\Core\View::text($key, 'span', true); ?>
    </a>
    <?php endforeach; ?>
</nav>
