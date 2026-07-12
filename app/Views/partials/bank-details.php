<?php
use App\Helpers\Lang;
use App\Core\View;
$bank = Lang::bank();
?>
<div class="bank-details-card card mb-4">
    <div class="card-body">
        <?php View::heading('bank_account_details', 'h6', 'bank'); ?>
        <?php
        $rows = [
            ['bank_name', $bank['bank_name']],
            ['branch', $bank['branch']],
            ['account_name', $bank['account_name']],
            ['account_number', $bank['account_number']],
        ];
        foreach ($rows as [$key, $value]):
            $label = Lang::pick(Lang::field($key));
        ?>
        <div class="info-row-bilingual mb-2">
            <div class="info-label">
                <?= View::escape($label) ?>
            </div>
            <div class="info-value fw-semibold"><?= View::escape($value) ?></div>
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
    </div>
</div>
