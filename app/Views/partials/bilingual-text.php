<?php
use App\Helpers\Lang;

if (isset($key)) {
    $labels = Lang::ui($key);
    $ta = $labels['ta'];
    $en = $labels['en'];
}
$tag = $tag ?? 'span';
$block = $block ?? false;
$class = $class ?? '';
?>
<<?= $tag ?> class="bilingual-text<?= $block ? ' bilingual-block' : '' ?><?= $class ? ' ' . $class : '' ?>">
    <span class="label-ta"><?= \App\Core\View::escape($ta) ?></span>
    <span class="label-en"><?= \App\Core\View::escape($en) ?></span>
</<?= $tag ?>>
