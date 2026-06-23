<?php
use App\Helpers\Lang;
$bank = Lang::bank();
?>
<div class="bank-details-card card mb-4">
    <div class="card-body">
        <?php \App\Core\View::heading('bank_account_details', 'h6', 'bank'); ?>
        <?php
        $rows = [
            ['bank_name', $bank['bank_name']],
            ['branch', $bank['branch']],
            ['account_name', $bank['account_name']],
            ['account_number', $bank['account_number']],
        ];
        foreach ($rows as [$key, $value]):
            $label = Lang::field($key);
        ?>
        <div class="info-row-bilingual mb-2">
            <div class="info-label bilingual-text bilingual-block">
                <span class="label-ta"><?= \App\Core\View::escape($label['ta']) ?></span>
                <span class="label-en"><?= \App\Core\View::escape($label['en']) ?></span>
            </div>
            <div class="info-value fw-semibold"><?= \App\Core\View::escape($value) ?></div>
        </div>
        <?php endforeach; ?>
        <div class="info-row-bilingual">
            <div class="info-label bilingual-text bilingual-block">
                <?php $addr = Lang::field('address'); ?>
                <span class="label-ta"><?= \App\Core\View::escape($addr['ta']) ?></span>
                <span class="label-en"><?= \App\Core\View::escape($addr['en']) ?></span>
            </div>
            <div class="info-value">
                <div class="label-ta fw-semibold"><?= \App\Core\View::escape($bank['address_ta']) ?></div>
                <div class="label-en small text-muted"><?= \App\Core\View::escape($bank['address_en']) ?></div>
            </div>
        </div>
    </div>
</div>
