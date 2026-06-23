<?php use App\Helpers\Lang; ?>
<div class="payment-instructions card mb-4">
    <div class="card-body">
        <?php \App\Core\View::heading('payment_instructions', 'h6', 'list-ol'); ?>
        <ol class="payment-steps-list mb-0 ps-3">
            <?php foreach (Lang::paymentSteps() as $i => $step): ?>
            <li class="mb-3">
                <span class="label-ta d-block fw-semibold"><?= ($i + 1) ?>. <?= \App\Core\View::escape($step['ta']) ?></span>
                <span class="label-en d-block"><?= \App\Core\View::escape($step['en']) ?></span>
            </li>
            <?php endforeach; ?>
        </ol>
    </div>
</div>
