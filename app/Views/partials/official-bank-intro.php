<?php
use App\Core\View;
use App\Helpers\Lang;

$bank = Lang::bank();
$membershipTypes = $membershipTypes ?? [];
?>
<div class="official-bank-intro sticky-bank-card mb-4" id="officialBankCard">
    <div class="card official-bank-card border-0 shadow-sm">
        <div class="card-header official-bank-card-header">
            <div class="d-flex align-items-start gap-2">
                <span class="bank-icon" aria-hidden="true">🏦</span>
                <div class="bilingual-text bilingual-block flex-grow-1">
                    <?php View::text('official_bank_details', 'h5', true, 'mb-0'); ?>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php
            $rows = [
                ['bank_name', $bank['bank_name'], false],
                ['branch', $bank['branch'], false],
                ['account_name', $bank['account_name'], true],
                ['account_number', $bank['account_number'], true],
            ];
            foreach ($rows as [$key, $value, $copyable]):
                $label = Lang::field($key);
                $isAccountNumber = $key === 'account_number';
                $valueId = match ($key) {
                    'account_number' => 'bankAccountNumber',
                    'account_name' => 'bankAccountName',
                    default => 'bank' . ucfirst($key),
                };
            ?>
            <div class="info-row-bilingual <?= $isAccountNumber ? 'account-number-row' : '' ?>">
                <div class="info-label bilingual-text bilingual-block">
                    <span class="label-ta"><?= View::escape($label['ta']) ?></span>
                    <span class="label-en"><?= View::escape($label['en']) ?></span>
                </div>
                <div class="info-value <?= $isAccountNumber ? 'account-number-value' : 'fw-semibold' ?>"
                     id="<?= $valueId ?>"><?= View::escape($value) ?></div>
                <?php if ($copyable): ?>
                <button type="button"
                        class="btn btn-sm btn-outline-primary copy-bank-btn mt-2"
                        data-copy="<?= View::escape($value) ?>"
                        data-label="<?= View::escape(Lang::ui($key === 'account_number' ? 'copy_account_number' : 'copy_account_name')['ta']) ?>">
                    <span class="label-ta"><?= View::escape(Lang::ui($key === 'account_number' ? 'copy_account_number' : 'copy_account_name')['ta']) ?></span>
                    <span class="label-en"><?= View::escape(Lang::ui($key === 'account_number' ? 'copy_account_number' : 'copy_account_name')['en']) ?></span>
                </button>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>

            <div class="info-row-bilingual">
                <div class="info-label bilingual-text bilingual-block">
                    <?php $addr = Lang::field('address'); ?>
                    <span class="label-ta"><?= View::escape($addr['ta']) ?></span>
                    <span class="label-en"><?= View::escape($addr['en']) ?></span>
                </div>
                <div class="info-value">
                    <div class="label-ta fw-semibold"><?= View::escape($bank['address_ta']) ?></div>
                    <div class="label-en small text-muted"><?= View::escape($bank['address_en']) ?></div>
                </div>
            </div>

            <hr class="my-3">

            <div class="membership-fee-summary">
                <div class="bilingual-text bilingual-block mb-3">
                    <?php View::text('membership_fee', 'h6', true, 'mb-0'); ?>
                </div>
                <?php foreach ($membershipTypes as $type):
                    $slug = $type['slug'] ?? 'ordinary';
                    $years = (int) ($type['duration_years'] ?? ($slug === 'ten_year' ? 10 : 1));
                    $display = Lang::membershipDisplayFromSlug($slug, $years);
                    $fee = Lang::formatFee((float) $type['fee']);
                ?>
                <div class="fee-summary-item mb-2">
                    <div class="label-ta fw-semibold"><?= View::escape($display['title_ta']) ?> (<?= View::escape($display['validity_ta']) ?>) — <?= View::escape($fee['ta']) ?></div>
                    <div class="label-en"><?= View::escape($display['title_en']) ?> (<?= View::escape($display['validity_en']) ?>) — <?= View::escape($fee['en']) ?></div>
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
