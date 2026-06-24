<?php
use App\Helpers\Lang;

if (isset($key)) {
    $labels = Lang::field($key);
    $ta = $labels['ta'];
    $en = $labels['en'];
}
$required = $required ?? false;
$for = $for ?? null;
?>
<label class="form-label bilingual-label<?= $required ? ' form-label-required' : '' ?>"<?= $for ? ' for="' . \App\Core\View::escape($for) . '"' : '' ?>>
    <span class="label-ta"><?= \App\Core\View::escape($ta) ?><?php if ($required): ?><span class="text-danger fw-bold"> *</span><?php endif; ?></span>
    <span class="label-en"><?= \App\Core\View::escape($en) ?><?php if ($required): ?><span class="text-danger fw-bold"> *</span><?php endif; ?></span>
    <?php if ($required): ?>
    <span class="required-hint text-danger" aria-hidden="true">
        <span class="label-ta d-block">(தேவை)</span>
        <span class="label-en d-block">(Required)</span>
    </span>
    <?php endif; ?>
</label>
