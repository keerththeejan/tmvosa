<?php
use App\Helpers\Lang;

if (isset($key)) {
    $labels = Lang::ui($key);
    $ta = $labels['ta'];
    $en = $labels['en'];
}
$tag = $tag ?? 'h5';
$icon = $icon ?? '';
?>
<<?= $tag ?> class="bilingual-heading step-title<?= $class ?? '' ?>">
    <?php if ($icon): ?><i class="bi bi-<?= $icon ?>"></i> <?php endif; ?>
    <span class="label-ta"><?= \App\Core\View::escape($ta) ?></span>
    <span class="label-en"><?= \App\Core\View::escape($en) ?></span>
</<?= $tag ?>>
