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
$tag = $tag ?? 'span';
$block = $block ?? false;
$class = $class ?? '';
$text = Lang::pick(['ta' => $ta ?? '', 'en' => $en ?? '']);
?>
<<?= $tag ?> class="bilingual-text<?= $block ? ' bilingual-block' : '' ?><?= $class ? ' ' . $class : '' ?>">
    <span class="label-current"><?= \App\Core\View::escape($text) ?></span>
</<?= $tag ?>>
