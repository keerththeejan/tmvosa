<?php
use App\Core\View;
use App\Helpers\Lang;
?>
    <div class="wizard-progress mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="page-indicator bilingual-text">
                <span class="label-ta fw-bold" id="pageIndicatorTa">1 / 4</span>
                <span class="label-en text-muted" id="pageIndicatorEn">Page 1 of 4</span>
            </span>
        </div>
        <div class="progress" style="height:6px;">
            <div class="progress-bar" id="progressBar" style="width:25%"></div>
        </div>
    </div>

    <form id="applicationForm" enctype="multipart/form-data" class="pb-3" novalidate>
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <div class="wizard-step active" data-step="1">
            <?php View::partial('page-bank-only', compact('membershipTypes')); ?>
        </div>

        <div class="wizard-step" data-step="2">
            <?php View::heading('step_personal', 'h5', 'person'); ?>

            <div class="mb-3">
                <?php View::label('full_name_tamil', true); ?>
                <input type="text" name="full_name_tamil" class="form-control" required
                       placeholder="<?= View::escape(View::placeholder('full_name_tamil')) ?>">
            </div>
            <div class="mb-3">
                <?php View::label('full_name_english', false); ?>
                <input type="text" name="full_name_english" class="form-control"
                       placeholder="<?= View::escape(View::placeholder('full_name_english')) ?>">
            </div>
            <div class="row g-3 mb-3">
                <div class="col-12 col-sm-6">
                    <?php View::label('gender', true); ?>
                    <select name="gender" class="form-select" required>
                        <option value=""><?= View::escape(Lang::ui('select')['ta']) ?> / <?= View::escape(Lang::ui('select')['en']) ?></option>
                        <option value="male"><?= View::escape(Lang::ui('male')['ta']) ?> / <?= View::escape(Lang::ui('male')['en']) ?></option>
                        <option value="female"><?= View::escape(Lang::ui('female')['ta']) ?> / <?= View::escape(Lang::ui('female')['en']) ?></option>
                        <option value="other"><?= View::escape(Lang::ui('other')['ta']) ?> / <?= View::escape(Lang::ui('other')['en']) ?></option>
                    </select>
                </div>
                <div class="col-12 col-sm-6">
                    <?php View::label('date_of_birth', true); ?>
                    <input type="text"
                           id="dateOfBirthInput"
                           class="form-control dob-input"
                           required
                           inputmode="numeric"
                           autocomplete="bday"
                           maxlength="14"
                           placeholder="<?= View::escape(View::placeholder('date_of_birth')) ?>">
                    <input type="hidden" name="date_of_birth" id="dateOfBirthHidden">
                </div>
            </div>
            <div class="mb-3">
                <?php View::label('nic_number', true); ?>
                <input type="text" name="nic_number" class="form-control" required
                       placeholder="<?= View::escape(View::placeholder('nic_number')) ?>">
            </div>
            <div class="mb-3">
                <?php View::label('current_address', true); ?>
                <textarea name="current_address" class="form-control" rows="2" required
                          placeholder="<?= View::escape(View::placeholder('current_address')) ?>"></textarea>
            </div>
            <div class="mb-3">
                <?php View::label('permanent_address', false); ?>
                <textarea name="permanent_address" class="form-control" rows="2"
                          placeholder="<?= View::escape(View::placeholder('permanent_address')) ?>"></textarea>
            </div>
            <div class="mb-3">
                <?php View::label('country', true); ?>
                <select name="country_id" class="form-select" required>
                    <option value=""><?= View::escape(Lang::ui('select_country')['ta']) ?> / <?= View::escape(Lang::ui('select_country')['en']) ?></option>
                    <?php foreach ($countries as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= View::escape($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-12 col-sm-6">
                    <?php View::label('mobile', true); ?>
                    <input type="tel" name="mobile" class="form-control" required
                           placeholder="<?= View::escape(View::placeholder('mobile')) ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <?php View::label('whatsapp', false); ?>
                    <input type="tel" name="whatsapp" class="form-control"
                           placeholder="<?= View::escape(View::placeholder('whatsapp')) ?>">
                </div>
            </div>
            <div class="mb-3">
                <?php View::label('email', false); ?>
                <input type="email" name="email" class="form-control"
                       placeholder="<?= View::escape(View::placeholder('email')) ?>">
            </div>
        </div>

        <div class="wizard-step" data-step="3">
            <?php View::heading('step_education', 'h5', 'book'); ?>

            <div class="row g-3 mb-3">
                <div class="col-12 col-sm-6">
                    <?php View::label('studied_from_year', true); ?>
                    <input type="number" name="studied_from_year" class="form-control" min="1950" max="2030" required
                           placeholder="<?= View::escape(View::placeholder('studied_from_year')) ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <?php View::label('studied_to_year', true); ?>
                    <input type="number" name="studied_to_year" id="studiedToYear" class="form-control" min="1950" max="2030" required
                           placeholder="<?= View::escape(View::placeholder('studied_to_year')) ?>">
                </div>
            </div>
            <div class="mb-3">
                <?php View::label('grade_stream', false); ?>
                <input type="text" name="grade_stream" class="form-control"
                       placeholder="<?= View::escape(View::placeholder('grade_stream')) ?>">
            </div>
            <div class="mb-3">
                <?php View::label('teacher_name', false); ?>
                <input type="text" name="teacher_name" class="form-control"
                       placeholder="<?= View::escape(View::placeholder('teacher_name')) ?>">
            </div>
            <div class="mb-3">
                <?php View::label('occupation', false); ?>
                <input type="text" name="occupation" class="form-control"
                       placeholder="<?= View::escape(View::placeholder('occupation')) ?>">
            </div>
            <div class="mb-3">
                <?php View::label('company', false); ?>
                <input type="text" name="company" class="form-control"
                       placeholder="<?= View::escape(View::placeholder('company')) ?>">
            </div>

            <div class="mb-3">
                <?php View::label('membership_type', true); ?>
                <div class="membership-options">
                    <?php foreach ($membershipTypes as $type):
                        $isTenYear = $type['slug'] === 'ten_year';
                        $title = Lang::ui($isTenYear ? 'ten_year_member' : 'ordinary_member');
                        $amount = number_format((float) $type['fee'], 0);
                    ?>
                    <label class="membership-option">
                        <input type="radio" name="membership_type_id" value="<?= $type['id'] ?>" data-fee="<?= $type['fee'] ?>">
                        <div class="option-card">
                            <span class="label-ta"><?= View::escape($title['ta']) ?></span>
                            <span class="label-en"><?= View::escape($title['en']) ?></span>
                            <div class="option-fee-ta">ரூ. <?= $amount ?></div>
                            <div class="option-fee-en">Rs. <?= $amount ?></div>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php View::heading('payment_info', 'h6', 'credit-card'); ?>

            <div class="mb-3">
                <?php View::label('amount_paid', true); ?>
                <input type="number" name="amount_paid" id="amountPaid" class="form-control" step="0.01" readonly required>
            </div>
            <div class="mb-3">
                <?php View::label('payment_method', true); ?>
                <select name="payment_method" class="form-select" required>
                    <option value=""><?= View::escape(Lang::ui('select')['ta']) ?> / <?= View::escape(Lang::ui('select')['en']) ?></option>
                    <option value="bank_transfer"><?= View::escape(Lang::ui('bank_transfer')['ta']) ?> / <?= View::escape(Lang::ui('bank_transfer')['en']) ?></option>
                    <option value="cash"><?= View::escape(Lang::ui('cash')['ta']) ?> / <?= View::escape(Lang::ui('cash')['en']) ?></option>
                    <option value="online"><?= View::escape(Lang::ui('online')['ta']) ?> / <?= View::escape(Lang::ui('online')['en']) ?></option>
                    <option value="cheque"><?= View::escape(Lang::ui('cheque')['ta']) ?> / <?= View::escape(Lang::ui('cheque')['en']) ?></option>
                </select>
            </div>
            <div class="mb-3">
                <?php View::label('transaction_number', false); ?>
                <input type="text" name="transaction_number" class="form-control"
                       placeholder="<?= View::escape(View::placeholder('transaction_number')) ?>">
            </div>
            <div class="mb-3">
                <?php View::label('payment_date', false); ?>
                <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>">
            </div>
        </div>

        <div class="wizard-step" data-step="4">
            <?php View::heading('step_documents', 'h5', 'cloud-upload'); ?>

            <?php foreach (['payment_slip', 'nic_copy', 'passport_photo'] as $labelKey):
                $docRequired = $labelKey === 'payment_slip';
                $inputId = $labelKey;
                $accept = 'image/jpeg,image/jpg,image/png,image/webp,application/pdf,.pdf';
                $captureAttr = $labelKey === 'payment_slip' ? ' capture="environment"' : '';
            ?>
            <div class="upload-box mb-3">
                <?php View::label($labelKey, $docRequired, $inputId); ?>
                <div class="upload-area" data-upload-for="<?= View::escape($inputId) ?>" tabindex="0" role="button" aria-label="<?= View::escape(Lang::field($labelKey)['ta']) ?>">
                    <div class="upload-area-body">
                        <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                        <div class="bilingual-text bilingual-block mt-2">
                            <span class="label-ta"><?= View::escape(Lang::ui('upload_hint')['ta']) ?></span>
                            <span class="label-en"><?= View::escape(Lang::ui('upload_hint')['en']) ?></span>
                        </div>
                        <small class="text-muted d-block mt-1">JPG, PNG, WEBP, PDF — max 10MB</small>
                    </div>
                    <input
                        type="file"
                        id="<?= View::escape($inputId) ?>"
                        name="<?= View::escape($labelKey) ?>"
                        class="upload-file-input"
                        accept="<?= View::escape($accept) ?>"
                        <?= $captureAttr ?>
                        <?= $docRequired ? 'required' : '' ?>>
                    <div class="upload-preview d-none">
                        <div class="preview-image d-none">
                            <img src="" alt="" class="img-thumbnail upload-thumb">
                        </div>
                        <div class="preview-pdf d-none">
                            <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                            <div class="preview-filename small fw-semibold mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="form-check mt-3">
                <input type="checkbox" class="form-check-input" id="agreeTerms" required>
                <label class="form-check-label declaration-text bilingual-label" for="agreeTerms">
                    <?php $decl = Lang::field('declaration'); ?>
                    <span class="label-ta"><?= View::escape($decl['ta']) ?> <span class="text-danger">*</span></span>
                    <span class="label-en"><?= View::escape($decl['en']) ?> <span class="text-danger">*</span></span>
                </label>
            </div>
            <p class="small mt-2 bilingual-text bilingual-block declaration-text">
                <span class="label-ta"><?= View::escape(Lang::ui('declaration_text_ta')) ?></span>
                <span class="label-en"><?= View::escape(Lang::ui('declaration_text_en')) ?></span>
            </p>
        </div>

        <div class="wizard-nav d-flex flex-column gap-2 mt-4">
            <button type="button" class="btn btn-primary btn-lg w-100 bilingual-btn" id="startBtn">
                <span class="label-ta"><?= View::escape(Lang::ui('start_application')['ta']) ?> <i class="bi bi-arrow-right"></i></span>
                <span class="label-en"><?= View::escape(Lang::ui('start_application')['en']) ?></span>
            </button>
            <div class="d-flex gap-2 w-100 d-none" id="navButtons">
                <button type="button" class="btn btn-outline-secondary btn-lg flex-fill bilingual-btn" id="prevBtn">
                    <span class="label-ta"><i class="bi bi-arrow-left"></i> <?= View::escape(Lang::ui('previous')['ta']) ?></span>
                    <span class="label-en"><?= View::escape(Lang::ui('previous')['en']) ?></span>
                </button>
                <button type="button" class="btn btn-primary btn-lg flex-fill bilingual-btn d-none" id="nextBtn">
                    <span class="label-ta"><?= View::escape(Lang::ui('next')['ta']) ?> <i class="bi bi-arrow-right"></i></span>
                    <span class="label-en"><?= View::escape(Lang::ui('next')['en']) ?></span>
                </button>
                <button type="submit" class="btn btn-success btn-lg flex-fill bilingual-btn d-none" id="submitBtn">
                    <span class="label-ta"><i class="bi bi-send"></i> <?= View::escape(Lang::ui('submit')['ta']) ?></span>
                    <span class="label-en"><?= View::escape(Lang::ui('submit')['en']) ?></span>
                </button>
            </div>
        </div>
    </form>

    <div class="track-section py-4 mt-3 border-top" id="track-section">
        <?php View::heading('track_application', 'h6'); ?>
        <div class="input-group">
            <input type="text" id="trackNumber" class="form-control" placeholder="APP-2026-0001">
            <button class="btn btn-outline-primary bilingual-btn" id="trackBtn" type="button">
                <span class="label-ta"><?= View::escape(Lang::ui('track')['ta']) ?></span>
                <span class="label-en"><?= View::escape(Lang::ui('track')['en']) ?></span>
            </button>
        </div>
        <div id="trackResult" class="mt-2"></div>
    </div>
