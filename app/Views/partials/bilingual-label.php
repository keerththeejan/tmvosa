<?php
use App\Helpers\Lang;

if (isset($key)) {
    $labels = Lang::field($key);
    $ta = $labels['ta'];
    $en = $labels['en'];
}
$required = $required ?? false;
?>
<label class="form-label bilingual-label"<?= isset($for) ? ' for="' . \App\Core\View::escape($for) . '"' : '' ?>>
    <span class="label-ta"><?= \App\Core\View::escape($ta) ?><?php if ($required): ?><span class="text-danger"> *</span><?php endif; ?></span>
    <span class="label-en"><?= \App\Core\View::escape($en) ?><?php if ($required): ?><span class="text-danger"> *</span><?php endif; ?></span>
</label>
