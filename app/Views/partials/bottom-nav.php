<?php
use App\Core\View;

$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<nav class="bottom-nav d-lg-none" aria-label="Bottom navigation">
    <?php
    $navItems = [
        ['dashboard', 'speedometer2', 'home'],
        ['applications', 'file-earmark-text', 'apps_short'],
        ['members', 'people', 'members'],
        ['payments', 'credit-card', 'pay_short'],
        ['membership-cards', 'person-vcard', 'cards_short'],
    ];
    foreach ($navItems as [$path, $icon, $key]):
        $active = str_contains($_SERVER['REQUEST_URI'], $path);
    ?>
    <a href="<?= $base ?>/<?= $path ?>" class="<?= $active ? 'active' : '' ?>">
        <i class="bi bi-<?= $icon ?>"></i>
        <span><?= View::escape(__($key)) ?></span>
    </a>
    <?php endforeach; ?>
</nav>
