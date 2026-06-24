<?php
use App\Core\View;
use App\Helpers\Lang;

$membershipTypes = $membershipTypes ?? [];
$bank = Lang::bank();
?>
<div class="membership-info-summary card border-0 shadow-sm">
    <div class="card-body">
        <div class="membership-fee-summary">
            <div class="bilingual-text bilingual-block mb-3">
                <?php View::text('membership_fee', 'h6', true, 'mb-0'); ?>
            </div>
            <?php foreach ($membershipTypes as $type):
                $slug = $type['slug'] ?? 'ordinary';
                $years = (int) ($type['duration_years'] ?? ($slug === 'ten_year' ? 10 : 1));
                $display = Lang::membershipDisplayFromSlug($slug, $years);
                $amount = number_format((float) $type['fee'], 0);
            ?>
            <div class="fee-summary-item mb-2">
                <div class="label-ta fw-semibold"><?= View::escape($display['title_ta']) ?> (<?= View::escape($display['validity_ta']) ?>) – ரூ. <?= $amount ?></div>
                <div class="label-en"><?= View::escape($display['title_en']) ?> (<?= View::escape($display['validity_en']) ?>) – Rs. <?= $amount ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="info-row-bilingual mt-3">
            <?php $acct = Lang::field('account_number'); ?>
            <div class="info-label bilingual-text bilingual-block">
                <span class="label-ta"><?= View::escape($acct['ta']) ?></span>
                <span class="label-en"><?= View::escape($acct['en']) ?></span>
            </div>
            <div class="info-value fw-semibold"><?= View::escape($bank['account_number']) ?></div>
        </div>

        <div class="important-notice alert alert-warning border-0 mt-3 mb-0">
            <div class="bilingual-text bilingual-block mb-2">
                <?php View::text('important_notice', 'strong', true); ?>
            </div>
            <p class="mb-0 bilingual-text bilingual-block">
                <span class="label-ta"><?= View::escape(Lang::ui('payment_notice_ta')) ?></span>
                <span class="label-en"><?= View::escape(Lang::ui('payment_notice_en')) ?></span>
            </p>
        </div>
    </div>
</div>
