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
                <div class="flex-grow-1">
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
                $label = Lang::pick(Lang::field($key));
                $isAccountNumber = $key === 'account_number';
                $valueId = match ($key) {
                    'account_number' => 'bankAccountNumber',
                    'account_name' => 'bankAccountName',
                    default => 'bank' . ucfirst($key),
                };
                $copyKey = $key === 'account_number' ? 'copy_account_number' : 'copy_account_name';
            ?>
            <div class="info-row-bilingual <?= $isAccountNumber ? 'account-number-row' : '' ?>">
                <div class="info-label">
                    <?= View::escape($label) ?>
                </div>
                <div class="info-value <?= $isAccountNumber ? 'account-number-value' : 'fw-semibold' ?>"
                     id="<?= $valueId ?>"><?= View::escape($value) ?></div>
                <?php if ($copyable): ?>
                <button type="button"
                        class="btn btn-sm btn-outline-primary copy-bank-btn mt-2"
                        data-copy="<?= View::escape($value) ?>"
                        data-label="<?= View::escape(__($copyKey)) ?>">
                    <?= View::escape(__($copyKey)) ?>
                </button>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>

            <div class="info-row-bilingual">
                <div class="info-label">
                    <?= View::escape(Lang::pick(Lang::field('address'))) ?>
                </div>
                <div class="info-value fw-semibold">
                    <?= View::escape(Lang::pick(['ta' => $bank['address_ta'], 'en' => $bank['address_en']])) ?>
                </div>
            </div>

            <hr class="my-3">

            <div class="membership-fee-summary">
                <div class="mb-3">
                    <?php View::text('membership_fee', 'h6', true, 'mb-0'); ?>
                </div>
                <?php foreach ($membershipTypes as $type):
                    $slug = $type['slug'] ?? 'ordinary';
                    $years = (int) ($type['duration_years'] ?? ($slug === 'ten_year' ? 10 : ($slug === 'half_year' ? 0 : 1)));
                    $display = Lang::membershipDisplayFromSlug($slug, $years, $type['name'] ?? null);
                    $fee = Lang::pick(Lang::formatFee((float) $type['fee']));
                    $title = Lang::pick(['ta' => $display['title_ta'], 'en' => $display['title_en']]);
                ?>
                <div class="fee-summary-item mb-2 fw-semibold">
                    <?= View::escape($title) ?> — <?= View::escape($fee) ?>
                </div>
                <?php endforeach; ?>
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
</div>
