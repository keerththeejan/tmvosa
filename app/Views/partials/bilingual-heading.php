<?php
use App\Helpers\Lang;

if (isset($key)) {
    $labels = Lang::ui($key);
    if (is_array($labels)) {
        $ta = $labels['ta'];
        $en = $labels['en'];
    } else {
        $ta = $en = (string) $labels;
    }
}
$tag = $tag ?? 'h5';
$icon = $icon ?? '';
$text = Lang::pick(['ta' => $ta ?? '', 'en' => $en ?? '']);
?>
<<?= $tag ?> class="bilingual-heading step-title<?= $class ?? '' ?>">
    <?php if ($icon): ?><i class="bi bi-<?= $icon ?>"></i> <?php endif; ?>
    <span class="label-current"><?= \App\Core\View::escape($text) ?></span>
</<?= $tag ?>>
