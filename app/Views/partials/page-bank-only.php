<?php
use App\Core\View;
use App\Helpers\Lang;

$bank = Lang::bank();
$membershipTypes = $membershipTypes ?? [];
?>
<div class="page-bank-only" id="pageBankOnly">
    <div class="card official-bank-card border-0 shadow-sm mb-3">
        <div class="card-header official-bank-card-header py-3">
            <div class="bilingual-text bilingual-block">
                <?php View::text('bank_account_details', 'h5', true, 'mb-0'); ?>
            </div>
        </div>
        <div class="card-body">
            <?php
            $rows = [
                ['bank_name', $bank['bank_name']],
                ['branch', $bank['branch']],
                ['account_name', $bank['account_name']],
            ];
            foreach ($rows as [$key, $value]):
                $label = Lang::field($key);
            ?>
            <div class="info-row-bilingual">
                <div class="info-label bilingual-text bilingual-block">
                    <span class="label-ta"><?= View::escape($label['ta']) ?></span>
                    <span class="label-en"><?= View::escape($label['en']) ?></span>
                </div>
                <div class="info-value fw-semibold"><?= View::escape($value) ?></div>
            </div>
            <?php endforeach; ?>

            <div class="info-row-bilingual account-number-row">
                <?php $acct = Lang::field('account_number'); ?>
                <div class="info-label bilingual-text bilingual-block">
                    <span class="label-ta"><?= View::escape($acct['ta']) ?></span>
                    <span class="label-en"><?= View::escape($acct['en']) ?></span>
                </div>
                <div class="info-value account-number-value" id="bankAccountNumber"><?= View::escape($bank['account_number']) ?></div>
                <button type="button" class="btn btn-primary copy-bank-btn mt-2 w-100" data-copy="<?= View::escape($bank['account_number']) ?>">
                    <span class="label-ta"><?= View::escape(Lang::ui('copy_account_number')['ta']) ?></span>
                    <span class="label-en"><?= View::escape(Lang::ui('copy_account_number')['en']) ?></span>
                </button>
            </div>

            <hr class="my-3">

            <div class="membership-fee-summary">
                <div class="bilingual-text bilingual-block mb-3">
                    <?php View::text('membership_fee', 'h6', true, 'mb-0'); ?>
                </div>
                <?php foreach ($membershipTypes as $type):
                    $isTenYear = ($type['slug'] ?? '') === 'ten_year';
                    $title = Lang::ui($isTenYear ? 'ten_year_member' : 'ordinary_member');
                    $amount = number_format((float) $type['fee'], 0);
                ?>
                <div class="fee-summary-item mb-2">
                    <div class="label-ta fw-semibold"><?= View::escape($title['ta']) ?> – ரூ. <?= $amount ?></div>
                    <div class="label-en"><?= View::escape($title['en']) ?> – Rs. <?= $amount ?></div>
                </div>
                <?php endforeach; ?>
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
</div>
