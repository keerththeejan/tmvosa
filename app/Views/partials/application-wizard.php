<?php
use App\Core\View;
use App\Helpers\Lang;
use App\Helpers\PaymentMethod;

$contactHelp = Lang::applicationContact();
?>
<div class="application-form-scroll osa-apply" id="applicationFormScroll">
    <div class="container osa-apply-container">
        <header class="osa-apply-hero" data-aos="fade-up">
            <div class="osa-apply-hero-inner">
                <span class="osa-apply-badge"><i class="bi bi-mortarboard-fill" aria-hidden="true"></i> OSA Alumni</span>
                <h1 class="osa-apply-title">
                    <?= View::escape(__('begin_application')) ?>
                </h1>
                <p class="osa-apply-subtitle">
                    <?= View::escape(__('welcome_title')) ?>
                </p>
                <p class="osa-apply-hint mb-0">
                    <?= Lang::pick([
                        'ta' => 'விண்ணப்பப் படிவத்தை கவனமாக நிரப்பவும். <span class="text-danger fw-semibold">*</span> குறியிடப்பட்டவை கட்டாயம்.',
                        'en' => 'Please complete the application form carefully. Fields marked with <span class="text-danger fw-semibold">*</span> are required.',
                    ]) ?>
                </p>
            </div>
        </header>

        <nav class="osa-apply-stepper" id="osaApplyStepper" aria-label="Application progress">
            <ol class="osa-apply-steps">
                <li class="osa-apply-step is-active" data-target="osaApplyPersonal">
                    <span class="osa-apply-step-num">1</span>
                    <span class="osa-apply-step-label">Personal Information</span>
                </li>
                <li class="osa-apply-step" data-target="osaApplyContact">
                    <span class="osa-apply-step-num">2</span>
                    <span class="osa-apply-step-label">Contact Information</span>
                </li>
                <li class="osa-apply-step" data-target="osaApplyMembership">
                    <span class="osa-apply-step-num">3</span>
                    <span class="osa-apply-step-label">Membership Details</span>
                </li>
                <li class="osa-apply-step" data-target="osaApplyDocuments">
                    <span class="osa-apply-step-num">4</span>
                    <span class="osa-apply-step-label">Documents</span>
                </li>
                <li class="osa-apply-step" data-target="osaApplyReview">
                    <span class="osa-apply-step-num">5</span>
                    <span class="osa-apply-step-label">Review &amp; Submit</span>
                </li>
            </ol>
        </nav>

        <div class="wizard-progress mb-3 d-none" id="wizardProgress">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="page-indicator">
                    <span class="fw-bold<?= Lang::isEnglish() ? ' d-none' : '' ?>" id="pageIndicatorTa">1 / 4</span>
                    <span class="text-muted<?= Lang::isTamil() ? ' d-none' : '' ?>" id="pageIndicatorEn">Page 1 of 4</span>
                </span>
            </div>
            <div class="progress" style="height:6px;">
                <div class="progress-bar" id="progressBar" style="width:25%"></div>
            </div>
        </div>

        <div class="row g-4 g-xl-5 align-items-start">
            <div class="col-lg-8">
                <form id="applicationForm" enctype="multipart/form-data" class="application-form-single osa-apply-form pb-3 needs-validation" novalidate>
                    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">

                    <!-- Bank details at top (wizard step 1) -->
                    <div class="wizard-step wizard-step--always-active active" data-step="1" id="osaApplyBank">
                        <?php View::partial('page-bank-only', compact('membershipTypes')); ?>
                    </div>

                    <!-- Wizard step 2: Personal + Contact -->
                    <div class="wizard-step wizard-step--always-active active" data-step="2">
                        <section class="osa-apply-panel form-section-card card border-0 mb-4" id="osaApplyPersonal" aria-labelledby="osaApplyPersonalTitle">
                            <div class="card-header form-section-card-header osa-apply-panel-header">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="osa-apply-panel-icon" aria-hidden="true"><i class="bi bi-person-vcard"></i></span>
                                    <div>
                                        <h2 class="h5 mb-0" id="osaApplyPersonalTitle">
                                            <?= View::escape(__('step_personal')) ?>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <?php
                                    $labelKey = 'passport_photo';
                                    $docRequired = false;
                                    $inputId = 'passport_photo';
                                    $accept = 'image/jpeg,image/jpg,image/png,image/webp,application/pdf,.pdf';
                                    ?>
                                    <?php View::label($labelKey, $docRequired, $inputId); ?>
                                    <div class="upload-box">
                                        <div class="upload-area osa-upload-card" data-upload-for="<?= View::escape($inputId) ?>" tabindex="0" role="button" aria-label="<?= View::escape(Lang::pick(Lang::field($labelKey))) ?>">
                                            <div class="upload-area-body">
                                                <i class="bi bi-person-bounding-box fs-1 text-primary" aria-hidden="true"></i>
                                                <div class="mt-2">
                                                    <?= View::escape(__('upload_hint')) ?>
                                                </div>
                                                <small class="text-muted d-block mt-1">Passport photo — JPG, PNG, WEBP (max 10MB)</small>
                                            </div>
                                            <input type="file" id="<?= View::escape($inputId) ?>" name="<?= View::escape($labelKey) ?>" class="upload-file-input" accept="<?= View::escape($accept) ?>">
                                            <div class="upload-preview d-none">
                                                <div class="preview-image d-none">
                                                    <img src="" alt="Passport photo preview" class="img-thumbnail upload-thumb">
                                                    <div class="preview-filename preview-filename-img small fw-semibold mt-1"></div>
                                                </div>
                                                <div class="preview-pdf d-none">
                                                    <i class="bi bi-file-earmark-pdf fs-1 text-danger" aria-hidden="true"></i>
                                                    <div class="preview-filename small fw-semibold mt-1"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <?php View::label('full_name_tamil', true); ?>
                                        <input type="text" name="full_name_tamil" id="fullNameTamil" class="form-control form-control-lg" required
                                               placeholder="<?= View::escape(View::placeholder('full_name_tamil')) ?>">
                                        <?php View::partial('field-required-feedback'); ?>
                                    </div>
                                    <div class="col-12">
                                        <?php View::label('full_name_english', false); ?>
                                        <input type="text" name="full_name_english" id="fullNameEnglish" class="form-control form-control-lg"
                                               placeholder="<?= View::escape(View::placeholder('full_name_english')) ?>">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php View::label('gender', true); ?>
                                        <select name="gender" id="genderSelect" class="form-select form-select-lg" required>
                                            <option value=""><?= View::escape(__('select')) ?></option>
                                            <option value="male"><?= View::escape(__('male')) ?></option>
                                            <option value="female"><?= View::escape(__('female')) ?></option>
                                            <option value="other"><?= View::escape(__('other')) ?></option>
                                        </select>
                                        <?php View::partial('field-required-feedback'); ?>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php View::label('date_of_birth', true); ?>
                                        <input type="text"
                                               id="dateOfBirthInput"
                                               class="form-control form-control-lg dob-input"
                                               required
                                               inputmode="numeric"
                                               autocomplete="bday"
                                               maxlength="14"
                                               placeholder="<?= View::escape(View::placeholder('date_of_birth')) ?>"
                                               aria-describedby="dobHelp">
                                        <input type="hidden" name="date_of_birth" id="dateOfBirthHidden">
                                        <div id="dobHelp" class="form-text">Format: DD/MM/YYYY</div>
                                        <?php View::partial('field-required-feedback', ['ta' => 'சரியான பிறந்த திகதியை உள்ளிடவும்.', 'en' => 'Please enter a valid date of birth.']); ?>
                                    </div>
                                    <div class="col-12">
                                        <?php View::label('nic_number', true); ?>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text" aria-hidden="true"><i class="bi bi-credit-card-2-front"></i></span>
                                            <input type="text" name="nic_number" id="nicNumberInput" class="form-control" required
                                                   placeholder="<?= View::escape(View::placeholder('nic_number')) ?>"
                                                   autocomplete="off">
                                        </div>
                                        <div id="nicValidationFeedback" class="field-validation-feedback mt-1 small d-none" role="alert"></div>
                                        <?php View::partial('field-required-feedback', ['ta' => 'தேசிய அடையாள அட்டை இலக்கம் அவசியம்.', 'en' => 'NIC number is required.']); ?>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="osa-apply-panel form-section-card card border-0 mb-4" id="osaApplyContact" aria-labelledby="osaApplyContactTitle">
                            <div class="card-header form-section-card-header osa-apply-panel-header">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="osa-apply-panel-icon" aria-hidden="true"><i class="bi bi-telephone"></i></span>
                                    <div>
                                        <h2 class="h5 mb-0" id="osaApplyContactTitle">Contact Information</h2>
                                        <p class="mb-0 small text-muted">Phone, email, and address details</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <?php View::label('mobile', true); ?>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text" aria-hidden="true"><i class="bi bi-phone"></i></span>
                                            <input type="tel" name="mobile" id="mobileInput" class="form-control" required
                                                   placeholder="<?= View::escape(View::placeholder('mobile')) ?>"
                                                   autocomplete="tel">
                                        </div>
                                        <div id="mobileValidationFeedback" class="field-validation-feedback mt-1 small d-none" role="alert"></div>
                                        <?php View::partial('field-required-feedback', ['ta' => 'கைத்தொலைபேசி இலக்கம் அவசியம்.', 'en' => 'Mobile number is required.']); ?>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php View::label('whatsapp', false); ?>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text" aria-hidden="true"><i class="bi bi-whatsapp"></i></span>
                                            <input type="tel" name="whatsapp" id="whatsappInput" class="form-control"
                                                   placeholder="<?= View::escape(View::placeholder('whatsapp')) ?>">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <?php View::labelRaw('மின்னஞ்சல் முகவரி (விருப்பம்)', 'Email Address (Optional)', false); ?>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text" aria-hidden="true"><i class="bi bi-envelope"></i></span>
                                            <input type="email" name="email" id="emailInput" class="form-control"
                                                   placeholder="<?= View::escape(View::placeholder('email')) ?>"
                                                   autocomplete="email">
                                        </div>
                                        <div id="emailValidationFeedback" class="field-validation-feedback mt-1 small d-none" role="alert"></div>
                                    </div>
                                    <div class="col-12">
                                        <?php View::label('current_address', true); ?>
                                        <textarea name="current_address" id="currentAddress" class="form-control" rows="2" required
                                                  placeholder="<?= View::escape(View::placeholder('current_address')) ?>"></textarea>
                                        <?php View::partial('field-required-feedback'); ?>
                                    </div>
                                    <div class="col-12">
                                        <?php View::label('permanent_address', false); ?>
                                        <textarea name="permanent_address" id="permanentAddress" class="form-control" rows="2"
                                                  placeholder="<?= View::escape(View::placeholder('permanent_address')) ?>"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <?php View::label('country', true); ?>
                                        <select name="country_id" id="countrySelect" class="form-select form-select-lg" required>
                                            <option value=""><?= View::escape(__('select_country')) ?></option>
                                            <?php foreach ($countries as $c): ?>
                                            <option value="<?= $c['id'] ?>"><?= View::escape($c['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php View::partial('field-required-feedback'); ?>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Wizard step 3: Education extras + Membership + Payment -->
                    <div class="wizard-step wizard-step--always-active active" data-step="3">
                        <section class="osa-apply-panel form-section-card card border-0 mb-4" id="osaApplyEducation" aria-labelledby="osaApplyEducationTitle">
                            <div class="card-header form-section-card-header osa-apply-panel-header">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="osa-apply-panel-icon" aria-hidden="true"><i class="bi bi-book"></i></span>
                                    <div>
                                        <h2 class="h5 mb-0" id="osaApplyEducationTitle">Education &amp; Profession</h2>
                                        <?php View::heading('step_education_professional', 'p', '', 'mb-0 small text-muted form-section-title'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <?php View::label('studied_from_year', true); ?>
                                        <input type="number" name="studied_from_year" id="studiedFromYear" class="form-control form-control-lg" min="1950" max="2030" required
                                               placeholder="<?= View::escape(View::placeholder('studied_from_year')) ?>">
                                        <?php View::partial('field-required-feedback'); ?>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php View::label('studied_to_year', true); ?>
                                        <input type="number" name="studied_to_year" id="studiedToYear" class="form-control form-control-lg" min="1950" max="2030" required
                                               placeholder="<?= View::escape(View::placeholder('studied_to_year')) ?>">
                                        <?php View::partial('field-required-feedback'); ?>
                                    </div>
                                    <div class="col-12">
                                        <?php View::label('grade_stream', false); ?>
                                        <input type="text" name="grade_stream" id="gradeStream" class="form-control form-control-lg"
                                               placeholder="<?= View::escape(View::placeholder('grade_stream')) ?>">
                                    </div>
                                    <div class="col-12">
                                        <?php View::label('teacher_name', false); ?>
                                        <input type="text" name="teacher_name" id="teacherName" class="form-control form-control-lg"
                                               placeholder="<?= View::escape(View::placeholder('teacher_name')) ?>">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php View::label('occupation', false); ?>
                                        <input type="text" name="occupation" id="occupationInput" class="form-control form-control-lg"
                                               placeholder="<?= View::escape(View::placeholder('occupation')) ?>">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php View::label('company', false); ?>
                                        <input type="text" name="company" id="companyInput" class="form-control form-control-lg"
                                               placeholder="<?= View::escape(View::placeholder('company')) ?>">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="osa-apply-panel form-section-card card border-0 mb-4" id="osaApplyMembership" aria-labelledby="osaApplyMembershipTitle">
                            <div class="card-header form-section-card-header osa-apply-panel-header">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="osa-apply-panel-icon" aria-hidden="true"><i class="bi bi-award"></i></span>
                                    <div>
                                        <h2 class="h5 mb-0" id="osaApplyMembershipTitle">Membership Details</h2>
                                        <?php View::heading('membership_information', 'p', '', 'mb-0 small text-muted form-section-title'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <?php View::label('membership_type', true); ?>
                                    <?php View::partial('membership-type-options', compact('membershipTypes')); ?>
                                </div>

                                <?php View::heading('payment_info', 'h3', 'credit-card', 'h6 payment-subheading'); ?>

                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <?php View::label('amount_paid', true); ?>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text"><?= View::escape(__('currency_rs')) ?></span>
                                            <input type="number" name="amount_paid" id="amountPaid" class="form-control" step="0.01" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php View::label('payment_method', true); ?>
                                        <?php View::partial('payment-method-select', [
                                            'selected' => PaymentMethod::DEFAULT,
                                            'includeEmpty' => false,
                                            'cssClass' => 'form-select form-select-lg',
                                        ]); ?>
                                        <?php View::partial('field-required-feedback', [
                                            'ta' => 'கட்டணம் செலுத்திய முறையைத் தேர்வு செய்யவும்.',
                                            'en' => 'Please select a payment method.',
                                        ]); ?>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php View::label('transaction_number', false); ?>
                                        <input type="text" name="transaction_number" id="transactionNumber" class="form-control form-control-lg"
                                               placeholder="<?= View::escape(View::placeholder('transaction_number')) ?>">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <?php View::label('payment_date', false); ?>
                                        <input type="date" name="payment_date" id="paymentDate" class="form-control form-control-lg" value="<?= date('Y-m-d') ?>">
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Wizard step 4: Documents + Review + Declaration -->
                    <div class="wizard-step wizard-step--always-active active" data-step="4">
                        <section class="osa-apply-panel form-section-card card border-0 mb-4" id="osaApplyDocuments" aria-labelledby="osaApplyDocumentsTitle">
                            <div class="card-header form-section-card-header osa-apply-panel-header">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="osa-apply-panel-icon" aria-hidden="true"><i class="bi bi-cloud-upload"></i></span>
                                    <div>
                                        <h2 class="h5 mb-0" id="osaApplyDocumentsTitle">Documents</h2>
                                        <?php View::heading('step_documents_card', 'p', '', 'mb-0 small text-muted form-section-title'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <?php
                                    $docItems = [
                                        ['payment_slip', true, 'receipt', 'Payment slip / bank receipt'],
                                        ['nic_copy', false, 'card-image', 'NIC copy (front or back)'],
                                    ];
                                    foreach ($docItems as [$labelKey, $docRequired, $icon, $hintEn]):
                                        $inputId = $labelKey;
                                        $accept = 'image/jpeg,image/jpg,image/png,image/webp,application/pdf,.pdf';
                                        $captureAttr = $labelKey === 'payment_slip' ? ' capture="environment"' : '';
                                    ?>
                                    <div class="col-12 col-md-6">
                                        <div class="upload-box h-100">
                                            <?php View::label($labelKey, $docRequired, $inputId); ?>
                                            <div class="upload-area osa-upload-card h-100" data-upload-for="<?= View::escape($inputId) ?>" tabindex="0" role="button" aria-label="<?= View::escape(Lang::pick(Lang::field($labelKey))) ?>">
                                                <div class="upload-area-body">
                                                    <i class="bi bi-<?= $icon ?> fs-1 text-primary" aria-hidden="true"></i>
                                                    <div class="mt-2">
                                                        <?= View::escape(__('upload_hint')) ?>
                                                    </div>
                                                    <small class="text-muted d-block mt-1"><?= View::escape($hintEn) ?> — JPG, PNG, WEBP, PDF</small>
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
                                                        <img src="" alt="<?= View::escape($hintEn) ?> preview" class="img-thumbnail upload-thumb">
                                                        <div class="preview-filename preview-filename-img small fw-semibold mt-1"></div>
                                                    </div>
                                                    <div class="preview-pdf d-none">
                                                        <i class="bi bi-file-earmark-pdf fs-1 text-danger" aria-hidden="true"></i>
                                                        <div class="preview-filename small fw-semibold mt-1"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>

                        <section class="osa-apply-panel form-section-card card border-0 mb-4" id="osaApplyReview" aria-labelledby="osaApplyReviewTitle">
                            <div class="card-header form-section-card-header osa-apply-panel-header">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="osa-apply-panel-icon" aria-hidden="true"><i class="bi bi-clipboard-check"></i></span>
                                    <div>
                                        <h2 class="h5 mb-0" id="osaApplyReviewTitle">Review &amp; Submit</h2>
                                        <p class="mb-0 small text-muted">Confirm your details before submitting</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="osa-apply-review" id="osaApplyReviewSummary" aria-live="polite">
                                    <p class="text-muted small mb-0">Fill the form above, then click <strong>Preview</strong> to refresh this summary.</p>
                                </div>

                                <div class="declaration-agreement-section mt-4">
                                    <div class="declaration-card border rounded-4 p-3 p-md-4 bg-light">
                                        <?php View::heading('step_declaration_card', 'h3', 'check2-square', 'h6 mb-3 form-section-title'); ?>
                                        <p class="small mb-3 declaration-agreement-text">
                                            <?= View::escape(__('declaration_text')) ?>
                                        </p>
                                        <div class="declaration-check declaration-check--centered">
                                            <input type="checkbox" class="form-check-input declaration-checkbox" id="agreeTerms" name="agree_declaration" value="1" aria-describedby="declarationValidationFeedback">
                                            <label class="form-check-label declaration-text mb-0" for="agreeTerms">
                                                <?= View::escape(__('field_declaration')) ?> <span class="text-danger fw-bold">*</span>
                                                <span class="required-hint text-danger" aria-hidden="true">(<?= View::escape(__('required')) ?>)</span>
                                            </label>
                                        </div>
                                        <div id="declarationValidationFeedback" class="declaration-invalid-feedback small text-danger mt-2 d-none" role="alert"></div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="osa-apply-actions" id="osaApplyActions" role="toolbar" aria-label="Form actions">
                            <button type="button" class="btn btn-osa-apply-outline" id="osaApplyBackBtn">
                                <i class="bi bi-arrow-left" aria-hidden="true"></i> Back
                            </button>
                            <button type="button" class="btn btn-osa-apply-outline" id="osaApplyDraftBtn" title="Draft saving is not available in this version">
                                <i class="bi bi-save" aria-hidden="true"></i> Save Draft
                            </button>
                            <button type="button" class="btn btn-osa-apply-secondary" id="osaApplyPreviewBtn">
                                <i class="bi bi-eye" aria-hidden="true"></i> Preview
                            </button>
                            <button type="submit" class="btn btn-osa-apply-primary" id="submitBtn" disabled>
                                <span class="submit-btn-content">
                                    <i class="bi bi-send" aria-hidden="true"></i> <?= View::escape(__('submit')) ?>
                                </span>
                                <span class="submit-btn-loading d-none" aria-hidden="true">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    <?= View::escape(Lang::pick(['ta' => 'சமர்ப்பிக்கிறது…', 'en' => 'Submitting…'])) ?>
                                </span>
                            </button>
                        </div>
                    </div>

                    <?php View::partial('application-contact-section'); ?>

                    <div class="wizard-nav form-submit-area mt-2 mb-4 d-none" id="wizardNavLegacy">
                        <button type="button" class="btn btn-primary btn-lg w-100 d-none" id="startBtn">
                            <?= View::escape(__('start_application')) ?> <i class="bi bi-arrow-right"></i>
                        </button>
                        <div class="d-flex gap-2 w-100 d-none" id="navButtons">
                            <button type="button" class="btn btn-outline-secondary btn-lg flex-fill" id="prevBtn">
                                <i class="bi bi-arrow-left"></i> <?= View::escape(__('previous')) ?>
                            </button>
                            <button type="button" class="btn btn-primary btn-lg flex-fill d-none" id="nextBtn">
                                <?= View::escape(__('next')) ?> <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="track-section osa-apply-track py-4 mt-2" id="track-section">
                    <?php View::heading('track_application', 'h6'); ?>
                    <div class="input-group input-group-lg">
                        <input type="text" id="trackNumber" class="form-control" placeholder="APP-2026-0001" aria-label="Application number">
                        <button class="btn btn-outline-primary" id="trackBtn" type="button">
                            <?= View::escape(__('track')) ?>
                        </button>
                    </div>
                    <div id="trackResult" class="mt-2"></div>
                </div>
            </div>

            <aside class="col-lg-4">
                <div class="osa-apply-aside">
                    <div class="osa-apply-side-card card border-0 mb-3">
                        <div class="card-body">
                            <h2 class="h5 mb-3"><i class="bi bi-stars text-primary me-1" aria-hidden="true"></i> Why Join?</h2>
                            <p class="small text-muted mb-3">Benefits of OSA Alumni membership</p>
                            <ul class="osa-apply-benefits list-unstyled mb-0">
                                <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Networking</li>
                                <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Scholarships</li>
                                <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Events</li>
                                <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Digital Membership Card</li>
                                <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Alumni Directory</li>
                                <li><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Career Support</li>
                            </ul>
                        </div>
                    </div>
                    <div class="osa-apply-side-card osa-apply-help card border-0">
                        <div class="card-body">
                            <h2 class="h5 mb-3"><i class="bi bi-headset text-primary me-1" aria-hidden="true"></i> Need Help?</h2>
                            <p class="small mb-2"><i class="bi bi-telephone me-1" aria-hidden="true"></i>
                                <a href="tel:<?= View::escape($contactHelp['phone_tel']) ?>"><?= View::escape($contactHelp['phone_display']) ?></a>
                            </p>
                            <p class="small mb-2"><i class="bi bi-envelope me-1" aria-hidden="true"></i>
                                <a href="mailto:<?= View::escape($contactHelp['email']) ?>"><?= View::escape($contactHelp['email']) ?></a>
                            </p>
                            <p class="small text-muted mb-0"><i class="bi bi-clock me-1" aria-hidden="true"></i> Office Hours: Mon–Fri, 9:00 AM – 4:00 PM</p>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
