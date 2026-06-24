<?php
use App\Core\View;
use App\Helpers\Lang;

$bank = Lang::bank();
$membershipTypes = $membershipTypes ?? [];
?>
<div class="page-bank-only bank-compact-card" id="pageBankOnly">
    <div class="card official-bank-card border-0 shadow-sm mb-3">
        <div class="card-header official-bank-card-header py-3">
            <div class="bilingual-text bilingual-block">
                <span class="bank-emoji" aria-hidden="true">🏦</span>
                <?php View::text('official_bank_details', 'h5', true, 'mb-0 d-inline-block'); ?>
            </div>
        </div>
        <div class="card-body py-3">
            <?php
            $compactRows = [
                ['account_name', $bank['account_name']],
                ['account_number', $bank['account_number']],
                ['branch', $bank['bank_name'] . ' — ' . $bank['branch']],
            ];
            foreach ($compactRows as [$key, $value]):
                $label = Lang::field($key);
                $isAccount = $key === 'account_number';
            ?>
            <div class="bank-compact-row<?= $isAccount ? ' bank-compact-row--account' : '' ?>">
                <div class="info-label bilingual-text bilingual-block">
                    <span class="label-ta"><?= View::escape($label['ta']) ?></span>
                    <span class="label-en"><?= View::escape($label['en']) ?></span>
                </div>
                <div class="info-value fw-semibold<?= $isAccount ? ' account-number-value' : '' ?>"><?= View::escape($value) ?></div>
            </div>
            <?php endforeach; ?>

            <button type="button" class="btn btn-primary copy-bank-btn mt-3 w-100" data-copy="<?= View::escape($bank['account_number']) ?>">
                <span class="label-ta"><?= View::escape(Lang::ui('copy_account_number')['ta']) ?></span>
                <span class="label-en"><?= View::escape(Lang::ui('copy_account_number')['en']) ?></span>
            </button>

            <div class="membership-fee-summary mt-3 pt-3 border-top">
                <div class="bilingual-text bilingual-block mb-2">
                    <?php View::text('membership_fee', 'h6', true, 'mb-0'); ?>
                </div>
                <?php foreach ($membershipTypes as $type):
                    $isTenYear = ($type['slug'] ?? '') === 'ten_year';
                    $title = Lang::ui($isTenYear ? 'ten_year_member' : 'ordinary_member');
                    $amount = number_format((float) $type['fee'], 0);
                ?>
                <div class="fee-summary-item mb-1">
                    <div class="label-ta fw-semibold"><?= View::escape($title['ta']) ?> – ரூ. <?= $amount ?></div>
                    <div class="label-en"><?= View::escape($title['en']) ?> – Rs. <?= $amount ?></div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="important-notice alert alert-warning border-0 mt-3 mb-0 py-2 px-3">
                <div class="bilingual-text bilingual-block mb-1">
                    <?php View::text('important_notice', 'strong', true); ?>
                </div>
                <p class="mb-0 bilingual-text bilingual-block small">
                    <span class="label-ta"><?= View::escape(Lang::ui('payment_notice_ta')) ?></span>
                    <span class="label-en"><?= View::escape(Lang::ui('payment_notice_en')) ?></span>
                </p>
            </div>
        </div>
    </div>
</div>
