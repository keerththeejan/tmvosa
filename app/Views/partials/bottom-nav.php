<?php $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>
<nav class="bottom-nav d-lg-none">
    <?php
    $navItems = [
        ['dashboard', 'speedometer2', 'Home'],
        ['applications', 'file-earmark-text', 'Apps'],
        ['members', 'people', 'Members'],
        ['payments', 'credit-card', 'Pay'],
        ['reports', 'bar-chart', 'Reports'],
    ];
    foreach ($navItems as [$path, $icon, $label]):
        $active = str_contains($_SERVER['REQUEST_URI'], $path);
    ?>
    <a href="<?= $base ?>/<?= $path ?>" class="<?= $active ? 'active' : '' ?>">
        <i class="bi bi-<?= $icon ?>"></i>
        <span><?= htmlspecialchars($label) ?></span>
    </a>
    <?php endforeach; ?>
</nav>
