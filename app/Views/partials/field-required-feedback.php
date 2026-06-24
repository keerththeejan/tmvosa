<?php
/** Bootstrap invalid-feedback block — place after input */
$ta = $ta ?? 'இந்த புலம் அவசியம்.';
$en = $en ?? 'This field is required.';
?>
<div class="invalid-feedback bilingual-text bilingual-block">
    <span class="label-ta"><?= \App\Core\View::escape($ta) ?></span>
    <span class="label-en"><?= \App\Core\View::escape($en) ?></span>
</div>
