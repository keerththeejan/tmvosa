<?php
use App\Core\View;
use App\Helpers\Lang;

$pageTitle = 'Add Member';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<div class="mb-3"><?php View::heading('add_member', 'h5', 'person-plus'); ?></div>

<form id="memberForm" enctype="multipart/form-data">
    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
    <div class="card mb-3">
        <div class="card-header bilingual-text bilingual-block"><?php View::text('personal_info', 'span', true); ?></div>
        <div class="card-body">
            <div class="mb-3">
                <?php View::label('membership_number', false); ?>
                <small class="bilingual-text bilingual-block d-block mb-1">
                    <span class="label-ta text-muted">(<?= View::escape(Lang::ui('auto_generated')['ta']) ?>)</span>
                    <span class="label-en text-muted">(<?= View::escape(Lang::ui('auto_generated')['en']) ?>)</span>
                </small>
                <input type="text" name="membership_number" class="form-control" placeholder="OSA-2026-0001">
            </div>
            <div class="mb-3">
                <?php View::label('full_name_tamil', true); ?>
                <input type="text" name="full_name_tamil" class="form-control" required>
            </div>
            <div class="mb-3">
                <?php View::label('full_name_english', false); ?>
                <input type="text" name="full_name_english" class="form-control">
            </div>
            <div class="row g-3 mb-3">
                <div class="col-12 col-md-6">
                    <?php View::label('gender', false); ?>
                    <select name="gender" class="form-select">
                        <option value=""><?= View::escape(Lang::ui('select')['ta']) ?> / <?= View::escape(Lang::ui('select')['en']) ?></option>
                        <option value="male"><?= View::escape(Lang::ui('male')['ta']) ?> / <?= View::escape(Lang::ui('male')['en']) ?></option>
                        <option value="female"><?= View::escape(Lang::ui('female')['ta']) ?> / <?= View::escape(Lang::ui('female')['en']) ?></option>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <?php View::label('date_of_birth', false); ?>
                    <input type="date" name="date_of_birth" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <?php View::label('nic_number', false); ?>
                <input type="text" name="nic_number" class="form-control">
            </div>
            <div class="mb-3">
                <?php View::label('photo', false); ?>
                <input type="file" name="photo" class="form-control" accept="image/*" capture="user">
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bilingual-text bilingual-block"><?php View::text('contact_info', 'span', true); ?></div>
        <div class="card-body">
            <div class="mb-3">
                <?php View::label('mobile', true); ?>
                <input type="tel" name="mobile" class="form-control" required>
            </div>
            <div class="mb-3">
                <?php View::label('whatsapp', false); ?>
                <input type="tel" name="whatsapp" class="form-control">
            </div>
            <div class="mb-3">
                <?php View::label('email', true); ?>
                <input type="email" name="email" class="form-control" required autocomplete="email">
            </div>
            <div class="mb-3">
                <?php View::label('country', false); ?>
                <select name="country_id" class="form-select">
                <option value=""><?= View::escape(Lang::ui('select_country')['ta']) ?> / <?= View::escape(Lang::ui('select_country')['en']) ?></option>
                <?php foreach ($countries as $c): ?>
                <option value="<?= $c['id'] ?>"><?= View::escape($c['name']) ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <?php View::label('current_address', false); ?>
                <textarea name="current_address" class="form-control" rows="2"></textarea>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bilingual-text bilingual-block"><?php View::text('membership_info', 'span', true); ?></div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-12 col-md-6">
                    <?php View::label('studied_from_year', false); ?>
                    <input type="number" name="studied_from_year" class="form-control">
                </div>
                <div class="col-12 col-md-6">
                    <?php View::label('studied_to_year', false); ?>
                    <input type="number" name="studied_to_year" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <?php View::label('membership_type', true); ?>
                <select name="membership_type_id" class="form-select" required>
                <?php foreach ($membershipTypes as $t):
                    $fee = Lang::formatFee((float) $t['fee']);
                ?>
                <option value="<?= $t['id'] ?>"><?= View::escape(\App\Helpers\MembershipType::optionLabel($t)) ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <?php View::label('occupation', false); ?>
                <input type="text" name="occupation" class="form-control">
            </div>
            <div class="mb-3">
                <?php View::label('company', false); ?>
                <input type="text" name="company" class="form-control">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100 bilingual-btn">
        <span class="label-ta"><i class="bi bi-person-plus"></i> <?= View::escape(Lang::ui('add_member')['ta']) ?></span>
        <span class="label-en"><?= View::escape(Lang::ui('add_member')['en']) ?></span>
    </button>
</form>
