<?php
use App\Core\View;
use App\Helpers\Lang;

$variant = $variant ?? 'light'; // light | dark | nav
$locale = Lang::locale();
$btnClass = match ($variant) {
    'nav' => 'btn btn-osa-lang btn-sm dropdown-toggle',
    'dark' => 'btn btn-outline-light btn-sm dropdown-toggle',
    default => 'btn btn-outline-secondary btn-sm dropdown-toggle',
};
?>
<div class="dropdown osa-lang-switch" data-osa-lang-switch>
    <button class="<?= View::escape($btnClass) ?>"
            type="button"
            data-bs-toggle="dropdown"
            data-bs-auto-close="true"
            aria-expanded="false"
            aria-label="<?= View::escape(__('language')) ?>">
        <span class="osa-lang-flag" aria-hidden="true"><?= $locale === 'en' ? '🇬🇧' : '🇱🇰' ?></span>
        <span class="osa-lang-current-label">
            <?= $locale === 'en' ? 'English' : 'தமிழ்' ?>
        </span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow osa-lang-menu">
        <li>
            <button type="button"
                    class="dropdown-item osa-lang-option<?= $locale === 'ta' ? ' active' : '' ?>"
                    data-osa-lang="ta">
                🇱🇰 தமிழ்
            </button>
        </li>
        <li>
            <button type="button"
                    class="dropdown-item osa-lang-option<?= $locale === 'en' ? ' active' : '' ?>"
                    data-osa-lang="en">
                🇬🇧 English
            </button>
        </li>
    </ul>
</div>
