<?php
use App\Core\View;
use App\Helpers\Lang;
?>
<div class="application-form-scroll" id="applicationFormScroll">
    <div class="wizard-progress mb-3 d-none" id="wizardProgress">
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

    <form id="applicationForm" enctype="multipart/form-data" class="application-form-single pb-3" novalidate>
        <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

        <!-- Bank details at top -->
        <div class="wizard-step wizard-step--always-active active" data-step="1">
            <?php View::partial('page-bank-only', compact('membershipTypes')); ?>
        </div>

        <!-- Card 1: Personal Information -->
        <div class="wizard-step wizard-step--always-active active" data-step="2">
            <div class="form-section-card card border-0 shadow-sm mb-3">
                <div class="card-header form-section-card-header">
                    <?php View::heading('step_personal', 'h5', 'person', 'mb-0 form-section-title'); ?>
                </div>
                <div class="card-body">
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
                        <input type="text" name="nic_number" id="nicNumberInput" class="form-control" required
                               placeholder="<?= View::escape(View::placeholder('nic_number')) ?>"
                               autocomplete="off">
                        <div id="nicValidationFeedback" class="field-validation-feedback mt-1 small d-none" role="alert"></div>
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
                    <div class="row g-3 mb-0">
                        <div class="col-12 col-sm-6">
                            <?php View::label('mobile', true); ?>
                            <input type="tel" name="mobile" id="mobileInput" class="form-control" required
                                   placeholder="<?= View::escape(View::placeholder('mobile')) ?>"
                                   autocomplete="tel">
                            <div id="mobileValidationFeedback" class="field-validation-feedback mt-1 small d-none" role="alert"></div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <?php View::label('whatsapp', false); ?>
                            <input type="tel" name="whatsapp" class="form-control"
                                   placeholder="<?= View::escape(View::placeholder('whatsapp')) ?>">
                        </div>
                        <div class="col-12">
                            <?php View::label('email', false); ?>
                            <input type="email" name="email" id="emailInput" class="form-control"
                                   placeholder="<?= View::escape(View::placeholder('email')) ?>"
                                   autocomplete="email">
                            <div id="emailValidationFeedback" class="field-validation-feedback mt-1 small d-none" role="alert"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2 & 3: Education + Membership (same validation step 3) -->
        <div class="wizard-step wizard-step--always-active active" data-step="3">
            <div class="form-section-card card border-0 shadow-sm mb-3">
                <div class="card-header form-section-card-header">
                    <?php View::heading('step_education_professional', 'h5', 'book', 'mb-0 form-section-title'); ?>
                </div>
                <div class="card-body">
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
                    <div class="row g-3 mb-0">
                        <div class="col-12 col-sm-6">
                            <?php View::label('occupation', false); ?>
                            <input type="text" name="occupation" class="form-control"
                                   placeholder="<?= View::escape(View::placeholder('occupation')) ?>">
                        </div>
                        <div class="col-12 col-sm-6">
                            <?php View::label('company', false); ?>
                            <input type="text" name="company" class="form-control"
                                   placeholder="<?= View::escape(View::placeholder('company')) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section-card card border-0 shadow-sm mb-3">
                <div class="card-header form-section-card-header">
                    <?php View::heading('membership_information', 'h5', 'award', 'mb-0 form-section-title'); ?>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <?php View::label('membership_type', true); ?>
                        <?php View::partial('membership-type-options', compact('membershipTypes')); ?>
                    </div>

                    <?php View::heading('payment_info', 'h6', 'credit-card', 'payment-subheading'); ?>

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
                    <div class="mb-0">
                        <?php View::label('payment_date', false); ?>
                        <input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4 & 5: Documents + Declaration (validation step 4) -->
        <div class="wizard-step wizard-step--always-active active" data-step="4">
            <div class="form-section-card card border-0 shadow-sm mb-3">
                <div class="card-header form-section-card-header">
                    <?php View::heading('step_documents_card', 'h5', 'cloud-upload', 'mb-0 form-section-title'); ?>
                </div>
                <div class="card-body">
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
                                    <div class="preview-filename preview-filename-img small fw-semibold mt-1"></div>
                                </div>
                                <div class="preview-pdf d-none">
                                    <i class="bi bi-file-earmark-pdf fs-1 text-danger"></i>
                                    <div class="preview-filename small fw-semibold mt-1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-section-card card border-0 shadow-sm mb-3">
                <div class="card-header form-section-card-header">
                    <?php View::heading('step_declaration_card', 'h5', 'check2-square', 'mb-0 form-section-title'); ?>
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="agreeTerms" name="agree_declaration" value="1">
                        <label class="form-check-label declaration-text bilingual-label" for="agreeTerms">
                            <?php $decl = Lang::field('declaration'); ?>
                            <span class="label-ta"><?= View::escape($decl['ta']) ?> <span class="text-danger">*</span></span>
                            <span class="label-en"><?= View::escape($decl['en']) ?> <span class="text-danger">*</span></span>
                        </label>
                    </div>
                    <p class="small mt-3 mb-0 bilingual-text bilingual-block declaration-text">
                        <span class="label-ta"><?= View::escape(Lang::ui('declaration_text_ta')) ?></span>
                        <span class="label-en"><?= View::escape(Lang::ui('declaration_text_en')) ?></span>
                    </p>
                </div>
            </div>
        </div>

        <?php View::partial('application-contact-section'); ?>

        <div class="wizard-nav form-submit-area mt-2 mb-4">
            <button type="button" class="btn btn-primary btn-lg w-100 bilingual-btn d-none" id="startBtn">
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
            </div>
            <button type="submit" class="btn btn-success btn-lg w-100 bilingual-btn" id="submitBtn" disabled>
                <span class="label-ta"><i class="bi bi-send"></i> <?= View::escape(Lang::ui('submit')['ta']) ?></span>
                <span class="label-en"><?= View::escape(Lang::ui('submit')['en']) ?></span>
            </button>
        </div>
    </form>

    <div class="track-section py-4 mt-1 border-top" id="track-section">
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
</div>
