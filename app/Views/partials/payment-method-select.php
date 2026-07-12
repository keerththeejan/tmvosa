<?php
use App\Core\View;
use App\Helpers\Lang;
use App\Helpers\PaymentMethod;

$name = $name ?? 'payment_method';
$id = $id ?? 'paymentMethodSelect';
$required = $required ?? true;
$selected = $selected ?? PaymentMethod::DEFAULT;
$includeEmpty = $includeEmpty ?? false;
$cssClass = $cssClass ?? 'form-select';
?>
<select
    name="<?= View::escape($name) ?>"
    id="<?= View::escape($id) ?>"
    class="<?= View::escape($cssClass) ?>"
    <?= $required ? 'required' : '' ?>
>
    <?php if ($includeEmpty): ?>
    <option value=""><?= View::escape(__('select')) ?></option>
    <?php endif; ?>
    <?php foreach (PaymentMethod::options() as $option):
        $labels = Lang::ui($option['key']);
    ?>
    <option value="<?= View::escape($option['value']) ?>"<?= $selected === $option['value'] ? ' selected' : '' ?>>
        <?= View::escape(is_array($labels) ? Lang::pick($labels) : (string) $labels) ?>
    </option>
    <?php endforeach; ?>
</select>
