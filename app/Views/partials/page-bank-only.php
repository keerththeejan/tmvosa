<?php
use App\Core\View;
use App\Helpers\Lang;

$bank = Lang::bank();
$membershipTypes = $membershipTypes ?? [];
?>
<div class="page-bank-only bank-compact-card" id="pageBankOnly">
    <div class="card official-bank-card border-0 shadow-sm mb-3">
        <div class="card-header official-bank-card-header py-3">
            <div>
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
                $label = Lang::pick(Lang::field($key));
                $isAccount = $key === 'account_number';
            ?>
            <div class="bank-compact-row<?= $isAccount ? ' bank-compact-row--account' : '' ?>">
                <div class="info-label">
                    <?= View::escape($label) ?>
                </div>
                <div class="info-value fw-semibold<?= $isAccount ? ' account-number-value' : '' ?>"><?= View::escape($value) ?></div>
            </div>
            <?php endforeach; ?>

            <button type="button" class="btn btn-primary copy-bank-btn mt-3 w-100" data-copy="<?= View::escape($bank['account_number']) ?>">
                <?= View::escape(__('copy_account_number')) ?>
            </button>

            <div class="membership-fee-summary mt-3 pt-3 border-top">
                <div class="mb-2">
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

            <div class="important-notice alert alert-warning border-0 mt-3 mb-0 py-2 px-3">
                <div class="mb-1">
                    <?php View::text('important_notice', 'strong', true); ?>
                </div>
                <p class="mb-0 small">
                    <?= View::escape(__('payment_notice')) ?>
                </p>
            </div>
        </div>
    </div>
</div>
