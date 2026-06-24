<?php
use App\Core\View;
use App\Helpers\Lang;

$membershipTypes = $membershipTypes ?? [];
$validityLabel = Lang::field('validity_period');
$selectedBadge = Lang::ui('selected_badge');
?>
<div class="membership-options">
    <?php foreach ($membershipTypes as $type):
        $slug = $type['slug'] ?? 'ordinary';
        $years = (int) ($type['duration_years'] ?? ($slug === 'ten_year' ? 10 : 1));
        $display = Lang::membershipDisplayFromSlug($slug, $years);
        $amount = number_format((float) $type['fee'], 0);
    ?>
    <label class="membership-option">
        <input type="radio"
               name="membership_type_id"
               value="<?= (int) $type['id'] ?>"
               data-fee="<?= View::escape((string) $type['fee']) ?>"
               data-slug="<?= View::escape($slug) ?>"
               data-years="<?= $years ?>">
        <div class="option-card">
            <span class="option-selected-badge" aria-hidden="true">
                <i class="bi bi-check-circle-fill"></i>
                <span class="label-ta"><?= View::escape($selectedBadge['ta']) ?></span>
                <span class="label-en"><?= View::escape($selectedBadge['en']) ?></span>
            </span>
            <div class="option-title-block">
                <span class="label-ta option-title-ta"><?= View::escape($display['title_ta']) ?></span>
                <span class="label-en option-title-en"><?= View::escape($display['title_en']) ?></span>
            </div>
            <div class="option-validity-block">
                <div class="option-validity-line">
                    <span class="label-ta option-validity-ta">
                        <span class="option-meta-label"><?= View::escape($validityLabel['ta']) ?>:</span>
                        <strong><?= View::escape($display['validity_ta']) ?></strong>
                    </span>
                </div>
                <div class="option-validity-line">
                    <span class="label-en option-validity-en">
                        <span class="option-meta-label"><?= View::escape($validityLabel['en']) ?>:</span>
                        <strong><?= View::escape($display['validity_en']) ?></strong>
                    </span>
                </div>
            </div>
            <div class="option-fee-block mt-auto">
                <div class="option-fee-ta">
                    <span class="option-meta-label"><?= View::escape($display['fee_label_ta']) ?>:</span>
                    <strong>ரூ. <?= $amount ?></strong>
                </div>
                <div class="option-fee-en">
                    <span class="option-meta-label"><?= View::escape($display['fee_label_en']) ?>:</span>
                    <strong>Rs. <?= $amount ?></strong>
                </div>
            </div>
        </div>
    </label>
    <?php endforeach; ?>
</div>
<input type="hidden" id="membershipTypeSlug" value="">
<input type="hidden" id="membershipFeeValue" value="">
<input type="hidden" id="membershipValidityYears" value="">
