<?php
use App\Core\View;
use App\Helpers\Lang;

$membershipTypes = $membershipTypes ?? [];
$bank = Lang::bank();
?>
<div class="membership-info-summary card border-0 shadow-sm">
    <div class="card-body">
        <div class="membership-fee-summary">
            <div class="mb-3">
                <?php View::text('membership_fee', 'h6', true, 'mb-0'); ?>
            </div>
            <?php foreach ($membershipTypes as $type):
                $slug = $type['slug'] ?? 'ordinary';
                $years = (int) ($type['duration_years'] ?? ($slug === 'ten_year' ? 10 : ($slug === 'half_year' ? 0 : 1)));
                $display = Lang::membershipDisplayFromSlug($slug, $years, $type['name'] ?? null);
                $amount = number_format((float) $type['fee'], 2);
                $title = Lang::pick(['ta' => $display['title_ta'], 'en' => $display['title_en']]);
            ?>
            <div class="fee-summary-item mb-2 fw-semibold">
                <?= View::escape($title) ?> — <?= View::escape(__('currency_rs')) ?> <?= $amount ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="info-row-bilingual mt-3">
            <div class="info-label">
                <?= View::escape(Lang::pick(Lang::field('account_number'))) ?>
            </div>
            <div class="info-value fw-semibold"><?= View::escape($bank['account_number']) ?></div>
        </div>

        <div class="important-notice alert alert-warning border-0 mt-3 mb-0">
            <div class="mb-2">
                <?php View::text('important_notice', 'strong', true); ?>
            </div>
            <p class="mb-0">
                <?= View::escape(__('payment_notice')) ?>
            </p>
        </div>
    </div>
</div>
