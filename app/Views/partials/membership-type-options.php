<?php
use App\Core\View;
use App\Helpers\Lang;
use App\Helpers\MembershipType;

$membershipTypes = $membershipTypes ?? [];
$validityLabel = Lang::pick(Lang::field('validity_period'));

$planMeta = [
    'half_year' => [
        'icon' => 'hourglass-split',
        'desc_en' => 'Flexible short-term access for 6 months.',
        'desc_ta' => '6 மாத குறுகிய கால உறுப்பினர்.',
    ],
    'ordinary' => [
        'icon' => 'patch-check',
        'desc_en' => 'Standard annual membership with full privileges.',
        'desc_ta' => 'முழு சலுகைகளுடன் வருடாந்த உறுப்பினர்.',
    ],
    'ten_year' => [
        'icon' => 'award',
        'desc_en' => 'Long-term membership with extended recognition.',
        'desc_ta' => 'நீண்டகால அங்கீகாரத்துடன் 10 ஆண்டு உறுப்பினர்.',
    ],
];
?>
<div class="membership-options osa-membership-options">
    <?php foreach ($membershipTypes as $type):
        $slug = $type['slug'] ?? 'ordinary';
        $years = (int) ($type['duration_years'] ?? ($slug === 'ten_year' ? 10 : ($slug === 'half_year' ? 0 : 1)));
        $display = Lang::membershipDisplayFromSlug($slug, $years, $type['name'] ?? null);
        $amount = number_format((float) $type['fee'], 2);
        $days = MembershipType::durationDays($type);
        $meta = $planMeta[$slug] ?? $planMeta['ordinary'];
        $title = Lang::pick(['ta' => $display['title_ta'], 'en' => $display['title_en']]);
        $validity = Lang::pick(['ta' => $display['validity_ta'], 'en' => $display['validity_en']]);
        $feeLabel = Lang::pick(['ta' => $display['fee_label_ta'], 'en' => $display['fee_label_en']]);
        $desc = Lang::pick(['ta' => $meta['desc_ta'], 'en' => $meta['desc_en']]);
    ?>
    <label class="membership-option">
        <input type="radio"
               name="membership_type_id"
               value="<?= (int) $type['id'] ?>"
               data-fee="<?= View::escape((string) $type['fee']) ?>"
               data-slug="<?= View::escape($slug) ?>"
               data-years="<?= $years ?>"
               data-days="<?= $days ?>">
        <div class="option-card">
            <span class="option-selected-badge" aria-hidden="true">
                <i class="bi bi-check-circle-fill"></i>
                <?= View::escape(__('selected_badge')) ?>
            </span>
            <div class="option-icon" aria-hidden="true">
                <i class="bi bi-<?= View::escape($meta['icon']) ?>"></i>
            </div>
            <div class="option-title-block">
                <span class="option-title-ta"><?= View::escape($title) ?></span>
            </div>
            <p class="option-desc small mb-2">
                <?= View::escape($desc) ?>
            </p>
            <div class="option-validity-block">
                <div class="option-validity-line">
                    <span class="option-validity-ta">
                        <span class="option-meta-label"><?= View::escape($validityLabel) ?>:</span>
                        <strong><?= View::escape($validity) ?></strong>
                    </span>
                </div>
            </div>
            <div class="option-fee-block mt-auto">
                <div class="option-fee-ta">
                    <span class="option-meta-label"><?= View::escape($feeLabel) ?>:</span>
                    <strong><?= View::escape(__('currency_rs')) ?> <?= $amount ?></strong>
                </div>
            </div>
        </div>
    </label>
    <?php endforeach; ?>
</div>
<input type="hidden" id="membershipTypeSlug" value="">
<input type="hidden" id="membershipFeeValue" value="">
<input type="hidden" id="membershipValidityYears" value="">
