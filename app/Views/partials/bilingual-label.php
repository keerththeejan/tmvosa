<?php
use App\Helpers\Lang;

if (isset($key)) {
    $labels = Lang::field($key);
    $ta = $labels['ta'];
    $en = $labels['en'];
}
$required = $required ?? false;
$for = $for ?? null;
$text = Lang::pick(['ta' => $ta ?? '', 'en' => $en ?? '']);
?>
<label class="form-label bilingual-label<?= $required ? ' form-label-required' : '' ?>"<?= $for ? ' for="' . \App\Core\View::escape($for) . '"' : '' ?>>
    <span class="label-current"><?= \App\Core\View::escape($text) ?><?php if ($required): ?><span class="text-danger fw-bold"> *</span><?php endif; ?></span>
    <?php if ($required): ?>
    <span class="required-hint text-danger" aria-hidden="true">(<?= \App\Core\View::escape(__('required')) ?>)</span>
    <?php endif; ?>
</label>
