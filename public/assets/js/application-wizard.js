// 4-page Application Wizard
(function() {
    let currentStep = 1;
    const totalSteps = 4;
    const DOB_MIN_YEAR = 1940;
    const DOB_INVALID_MSG = 'தயவுசெய்து சரியான பிறந்த திகதியை உள்ளிடவும். Please enter a valid Date of Birth.';
    const MAX_FILE_SIZE = 10 * 1024 * 1024;
    const FILE_SIZE_ERROR = 'கோப்பின் அளவு 10MB ஐ விட அதிகமாக இருக்கக்கூடாது. File size must be less than 10MB.';
    const FILE_TYPE_ERROR = 'JPG, PNG, WEBP அல்லது PDF மட்டுமே அனுமதிக்கப்படும். Only JPG, PNG, WEBP, or PDF files are allowed.';
    const FILE_REQUIRED_ERROR = 'இந்த ஆவணத்தை பதிவேற்றவும். Please upload this document.';
    const FILE_SUCCESS_MSG = 'கோப்பு வெற்றிகரமாக பதிவேற்றப்பட்டது. File uploaded successfully.';
    const ALLOWED_FILE_EXT = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
    const DECLARATION_REQUIRED_MSG = 'உறுதிமொழியை ஏற்றுக்கொள்ள வேண்டும்.<br>You must accept the declaration before submitting the application.';
    const FIELD_VALIDATE_DELAY = 1000;

    const validationSettings = window.APP_VALIDATION_CONFIG || {
        blockDuplicateMobile: false,
        blockDuplicateEmail: false
    };

    const validationState = {
        nic: { status: 'idle', block: false },
        mobile: { status: 'idle', block: false, warning: false },
        email: { status: 'idle', block: false, warning: false }
    };

    const fieldValidateTimers = {};
    let nicValidateRequest = null;
    let mobileValidateRequest = null;
    let emailValidateRequest = null;

    function formatDobMask(value) {
        const digits = value.replace(/\D/g, '').slice(0, 8);
        let formatted = '';
        if (digits.length > 0) formatted += digits.substring(0, 2);
        if (digits.length > 2) formatted += '/' + digits.substring(2, 4);
        if (digits.length > 4) formatted += '/' + digits.substring(4, 8);
        return formatted;
    }

    function parseDob(value) {
        const raw = (value || '').trim();
        if (!raw) return null;

        let match = raw.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
        if (match) {
            return validateDobParts(+match[1], +match[2], +match[3]);
        }

        match = raw.match(/^(\d{1,2})[\/\-\s]+(\d{1,2})[\/\-\s]+(\d{4})$/);
        if (match) {
            return validateDobParts(+match[3], +match[2], +match[1]);
        }

        const digits = raw.replace(/\D/g, '');
        if (digits.length === 8) {
            return validateDobParts(
                +digits.substring(4, 8),
                +digits.substring(2, 4),
                +digits.substring(0, 2)
            );
        }

        return null;
    }

    function validateDobParts(year, month, day) {
        const maxYear = new Date().getFullYear();
        if (year < DOB_MIN_YEAR || year > maxYear) return null;
        const dt = new Date(year, month - 1, day);
        if (dt.getFullYear() !== year || dt.getMonth() !== month - 1 || dt.getDate() !== day) {
            return null;
        }
        return year + '-' + String(month).padStart(2, '0') + '-' + String(day).padStart(2, '0');
    }

    function displayDob(iso) {
        const match = iso.match(/^(\d{4})-(\d{2})-(\d{2})$/);
        if (!match) return iso;
        return match[3] + '/' + match[2] + '/' + match[1];
    }

    function validateDobField() {
        const input = document.getElementById('dateOfBirthInput');
        const hidden = document.getElementById('dateOfBirthHidden');
        if (!input || !hidden) return true;

        const value = input.value.trim();
        if (!value) {
            input.setCustomValidity('');
            hidden.value = '';
            return !input.required || false;
        }

        const parsed = parseDob(value);
        if (!parsed) {
            input.setCustomValidity(DOB_INVALID_MSG);
            hidden.value = '';
            return false;
        }

        input.setCustomValidity('');
        hidden.value = parsed;
        return true;
    }

    function isAllowedFile(file) {
        if (!file) return false;
        const ext = (file.name.split('.').pop() || '').toLowerCase();
        if (ALLOWED_FILE_EXT.indexOf(ext) === -1) return false;
        if (file.type && file.type.indexOf('image/') === 0) return true;
        if (file.type === 'application/pdf') return true;
        return ALLOWED_FILE_EXT.indexOf(ext) !== -1;
    }

    function resetUploadPreview($area) {
        const $preview = $area.find('.upload-preview');
        $preview.addClass('d-none');
        $preview.find('.preview-image').addClass('d-none').find('img').attr('src', '');
        $preview.find('.preview-filename-img').text('');
        $preview.find('.preview-pdf').addClass('d-none').find('.preview-filename').text('');
    }

    function handleFileSelected(input) {
        const file = input.files && input.files[0];
        const $area = $(input).closest('.upload-area');

        if (!file) {
            $area.removeClass('has-file');
            resetUploadPreview($area);
            input.setCustomValidity('');
            return true;
        }

        if (file.size > MAX_FILE_SIZE) {
            input.value = '';
            $area.removeClass('has-file');
            resetUploadPreview($area);
            input.setCustomValidity(FILE_SIZE_ERROR);
            Swal.fire({
                title: 'பிழை / Error',
                text: FILE_SIZE_ERROR,
                icon: 'error'
            });
            return false;
        }

        if (!isAllowedFile(file)) {
            input.value = '';
            $area.removeClass('has-file');
            resetUploadPreview($area);
            input.setCustomValidity(FILE_TYPE_ERROR);
            Swal.fire({
                title: 'பிழை / Error',
                text: FILE_TYPE_ERROR,
                icon: 'error'
            });
            return false;
        }

        input.setCustomValidity('');
        $area.addClass('has-file');

        const $preview = $area.find('.upload-preview');
        const $imgWrap = $preview.find('.preview-image');
        const $pdfWrap = $preview.find('.preview-pdf');

        $preview.removeClass('d-none');
        $imgWrap.addClass('d-none');
        $pdfWrap.addClass('d-none');

        if (file.type.indexOf('image/') === 0) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $imgWrap.removeClass('d-none').find('img').attr('src', e.target.result);
                $imgWrap.find('.preview-filename-img').text(file.name);
            };
            reader.readAsDataURL(file);
        } else {
            $pdfWrap.removeClass('d-none').find('.preview-filename').text(file.name);
        }

        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'success',
            title: FILE_SUCCESS_MSG,
            showConfirmButton: false,
            timer: 2200
        });

        return true;
    }

    function initUploadAreas() {
        $('.upload-area').each(function() {
            const $area = $(this);
            const inputId = $area.data('upload-for');
            const input = inputId ? document.getElementById(inputId) : $area.find('input[type="file"]')[0];
            if (!input) return;

            $area.on('click', function(e) {
                if (e.target === input) return;
                input.click();
            });

            $area.on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    input.click();
                }
            });

            ['dragenter', 'dragover'].forEach(function(eventName) {
                $area.on(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $area.addClass('is-dragover');
                });
            });

            ['dragleave', 'drop'].forEach(function(eventName) {
                $area.on(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $area.removeClass('is-dragover');
                });
            });

            $area.on('drop', function(e) {
                const dt = e.originalEvent && e.originalEvent.dataTransfer;
                if (!dt || !dt.files || !dt.files.length) return;
                if (typeof DataTransfer !== 'undefined') {
                    const transfer = new DataTransfer();
                    transfer.items.add(dt.files[0]);
                    input.files = transfer.files;
                } else {
                    input.files = dt.files;
                }
                handleFileSelected(input);
            });
        });

        $(document).on('change', '.upload-file-input', function() {
            handleFileSelected(this);
        });
    }

    function resetAllUploads() {
        $('.upload-area').each(function() {
            const $area = $(this);
            $area.removeClass('has-file is-dragover');
            resetUploadPreview($area);
            const input = $area.find('.upload-file-input')[0];
            if (input) {
                input.value = '';
                input.setCustomValidity('');
            }
        });
    }

    function initDobInput() {
        const $input = $('#dateOfBirthInput');
        if (!$input.length) return;

        $input.on('input', function() {
            const val = $(this).val();
            if (/^\d{4}-\d{1,2}-\d{1,2}$/.test(val.trim())) {
                return;
            }
            const cursor = this.selectionStart;
            const prevLen = val.length;
            const formatted = formatDobMask(val);
            $(this).val(formatted);
            const newLen = formatted.length;
            const nextPos = Math.max(0, (cursor || 0) + (newLen - prevLen));
            if (this.setSelectionRange) {
                this.setSelectionRange(nextPos, nextPos);
            }
            validateDobField();
        });

        $input.on('blur', function() {
            const parsed = parseDob($(this).val());
            if (parsed) {
                $(this).val(displayDob(parsed));
            }
            validateDobField();
        });
    }

    function scrollToFirstInvalidInStep(step) {
        const $step = $('.wizard-step[data-step="' + step + '"]');
        let $target = $step.find(':invalid').filter(':visible').first();
        if (!$target.length && step === 2) {
            $target = $('#dateOfBirthInput');
        }
        if ($target.length && $target[0]) {
            $('html, body').animate({ scrollTop: $target.offset().top - 88 }, 400);
            if ($target[0].focus) {
                $target[0].focus();
            }
        }
    }

    function validateAllFormSteps() {
        if (!validateDobField()) {
            const dobInput = document.getElementById('dateOfBirthInput');
            if (dobInput) dobInput.reportValidity();
            scrollToFirstInvalidInStep(2);
            return false;
        }
        for (let s = 2; s <= 4; s++) {
            if (!validateStep(s)) {
                scrollToFirstInvalidInStep(s);
                return false;
            }
        }
        if (!validateDeclaration(false)) {
            return false;
        }
        if (!isSubmissionAllowed()) {
            Swal.fire({
                title: 'சரிபார்ப்பு தேவை / Validation Required',
                html: 'தயவுசெய்து படிவத்தின் சரிபார்ப்பு பிழைகளை சரிசெய்யவும்.<br>Please resolve the validation messages before submitting.',
                icon: 'warning'
            });
            return false;
        }
        return true;
    }

    function validateDeclaration(showAlert) {
        if ($('#agreeTerms').is(':checked')) {
            return true;
        }
        if (showAlert !== false) {
            Swal.fire({
                title: 'தேவை / Required',
                html: DECLARATION_REQUIRED_MSG,
                icon: 'warning'
            });
        }
        return false;
    }

    function isSubmissionAllowed() {
        if (validationState.nic.status === 'duplicate' || validationState.nic.block) {
            return false;
        }
        if (validationState.nic.status === 'checking') {
            return false;
        }
        if (validationState.mobile.block) {
            return false;
        }
        if (validationState.email.block) {
            return false;
        }
        return true;
    }

    function updateSubmitButtonState() {
        const declarationOk = $('#agreeTerms').is(':checked');
        const allowed = declarationOk && isSubmissionAllowed();
        $('#submitBtn').prop('disabled', !allowed);
    }

    function renderFieldFeedback($input, $feedback, result) {
        if (!$feedback.length) return;

        $input.removeClass('is-valid is-invalid');
        $feedback.removeClass('text-success text-danger text-warning').addClass('d-none').empty();

        if (!result || result.status === 'idle' || result.status === 'empty' || result.status === 'checking') {
            if (result && result.status === 'checking') {
                $feedback.removeClass('d-none').addClass('text-muted').html(
                    '<span class="label-ta">சரிபார்க்கப்படுகிறது...</span><br>' +
                    '<span class="label-en">Checking...</span>'
                );
            }
            return;
        }

        const isSuccess = result.status === 'available';
        const isWarning = result.status === 'duplicate' && result.warning;
        const isError = result.status === 'duplicate' && !result.warning || result.status === 'invalid';

        $feedback.removeClass('d-none');
        if (isSuccess) {
            $input.addClass('is-valid');
            $feedback.addClass('text-success').html(
                '<span class="label-ta">&#10003; ' + escapeHtml(result.message_ta || '') + '</span><br>' +
                '<span class="label-en">&#10003; ' + escapeHtml(result.message_en || '') + '</span>'
            );
        } else if (isWarning) {
            $input.addClass('is-invalid');
            $feedback.addClass('text-warning').html(
                '<span class="label-ta">&#9888; ' + escapeHtml(result.message_ta || '') + '</span><br>' +
                '<span class="label-en">&#9888; ' + escapeHtml(result.message_en || '') + '</span>'
            );
        } else if (isError) {
            $input.addClass('is-invalid');
            $feedback.addClass('text-danger').html(
                '<span class="label-ta">&#10007; ' + escapeHtml(result.message_ta || '') + '</span><br>' +
                '<span class="label-en">&#10007; ' + escapeHtml(result.message_en || '') + '</span>'
            );
        }
    }

    function escapeHtml(text) {
        return $('<div>').text(text || '').html();
    }

    function setValidationState(field, result) {
        validationState[field] = {
            status: result.status || 'idle',
            block: !!result.block,
            warning: !!result.warning
        };
        updateSubmitButtonState();
    }

    function checkFieldRemote(field, value, $input, $feedback) {
        const apiField = field === 'nic' ? 'nic_number' : field;
        let activeRequest = null;

        if (field === 'nic' && nicValidateRequest) {
            nicValidateRequest.abort();
        } else if (field === 'mobile' && mobileValidateRequest) {
            mobileValidateRequest.abort();
        } else if (field === 'email' && emailValidateRequest) {
            emailValidateRequest.abort();
        }

        const checking = { status: 'checking' };
        setValidationState(field, checking);
        renderFieldFeedback($input, $feedback, checking);

        activeRequest = $.ajax({
            url: BASE_URL + '/apply/validate-field',
            method: 'POST',
            dataType: 'json',
            data: {
                field: apiField,
                value: value,
                _csrf_token: CSRF_TOKEN
            }
        });

        if (field === 'nic') {
            nicValidateRequest = activeRequest;
        } else if (field === 'mobile') {
            mobileValidateRequest = activeRequest;
        } else if (field === 'email') {
            emailValidateRequest = activeRequest;
        }

        activeRequest.done(function(res) {
            if (!res || !res.success) return;
            setValidationState(field, res);
            renderFieldFeedback($input, $feedback, res);
        }).fail(function(_xhr, status) {
            if (status === 'abort') return;
            const failed = { status: 'idle', block: false };
            setValidationState(field, failed);
            renderFieldFeedback($input, $feedback, null);
        }).always(function() {
            if (field === 'nic' && nicValidateRequest === activeRequest) {
                nicValidateRequest = null;
            } else if (field === 'mobile' && mobileValidateRequest === activeRequest) {
                mobileValidateRequest = null;
            } else if (field === 'email' && emailValidateRequest === activeRequest) {
                emailValidateRequest = null;
            }
        });

        return activeRequest;
    }

    function scheduleFieldValidation(field, $input, $feedback) {
        const value = ($input.val() || '').trim();
        clearTimeout(fieldValidateTimers[field]);
        fieldValidateTimers[field] = setTimeout(function() {
            if (!value) {
                setValidationState(field, { status: 'idle', block: false });
                renderFieldFeedback($input, $feedback, null);
                return;
            }
            checkFieldRemote(field, value, $input, $feedback);
        }, FIELD_VALIDATE_DELAY);
    }

    function initFieldValidation() {
        const $nic = $('#nicNumberInput');
        const $mobile = $('#mobileInput');
        const $email = $('#emailInput');

        $nic.on('blur', function() {
            const value = ($(this).val() || '').trim();
            clearTimeout(fieldValidateTimers.nic);
            if (!value) {
                setValidationState('nic', { status: 'idle', block: false });
                renderFieldFeedback($nic, $('#nicValidationFeedback'), null);
                return;
            }
            checkFieldRemote('nic', value, $nic, $('#nicValidationFeedback'));
        }).on('input', function() {
            scheduleFieldValidation('nic', $nic, $('#nicValidationFeedback'));
        });

        $mobile.on('blur', function() {
            const value = ($(this).val() || '').trim();
            clearTimeout(fieldValidateTimers.mobile);
            if (!value) {
                setValidationState('mobile', { status: 'idle', block: false });
                renderFieldFeedback($mobile, $('#mobileValidationFeedback'), null);
                return;
            }
            checkFieldRemote('mobile', value, $mobile, $('#mobileValidationFeedback'));
        }).on('input', function() {
            scheduleFieldValidation('mobile', $mobile, $('#mobileValidationFeedback'));
        });

        $email.on('blur', function() {
            const value = ($(this).val() || '').trim();
            clearTimeout(fieldValidateTimers.email);
            if (!value) {
                setValidationState('email', { status: 'idle', block: false });
                renderFieldFeedback($email, $('#emailValidationFeedback'), null);
                return;
            }
            checkFieldRemote('email', value, $email, $('#emailValidationFeedback'));
        }).on('input', function() {
            scheduleFieldValidation('email', $email, $('#emailValidationFeedback'));
        });

        $('#agreeTerms').on('change', updateSubmitButtonState);
    }

    function ensureRemoteValidationBeforeSubmit() {
        const deferred = $.Deferred();
        const checks = [];
        const $nic = $('#nicNumberInput');
        const nicVal = ($nic.val() || '').trim();
        if (nicVal) {
            checks.push(checkFieldRemote('nic', nicVal, $nic, $('#nicValidationFeedback')));
        }
        const $mobile = $('#mobileInput');
        const mobileVal = ($mobile.val() || '').trim();
        if (mobileVal) {
            checks.push(checkFieldRemote('mobile', mobileVal, $mobile, $('#mobileValidationFeedback')));
        }
        const $email = $('#emailInput');
        const emailVal = ($email.val() || '').trim();
        if (emailVal) {
            checks.push(checkFieldRemote('email', emailVal, $email, $('#emailValidationFeedback')));
        }

        if (!checks.length) {
            deferred.resolve();
            return deferred.promise();
        }

        $.when.apply($, checks).always(function() {
            setTimeout(function() {
                deferred.resolve();
            }, 50);
        });
        return deferred.promise();
    }

    function initSinglePageUI() {
        $('.wizard-step--always-active').addClass('active');
        $('#wizardProgress').addClass('d-none');
        $('#startBtn, #navButtons').addClass('d-none');
        $('#submitBtn').removeClass('d-none').addClass('w-100');
        updateSubmitButtonState();
    }

    function showStep(step) {
        $('.wizard-step').removeClass('active');
        $('.wizard-step[data-step="' + step + '"]').addClass('active');
        $('#progressBar').css('width', ((step / totalSteps) * 100) + '%');
        $('#pageIndicatorTa').text(step + ' / ' + totalSteps);
        $('#pageIndicatorEn').text('Page ' + step + ' of ' + totalSteps);

        $('#startBtn').toggleClass('d-none', step !== 1);
        $('#navButtons').toggleClass('d-none', step <= 1);
        $('#prevBtn').toggleClass('d-none', step <= 1);
        $('#nextBtn').toggleClass('d-none', step <= 1 || step >= totalSteps);
        $('#submitBtn').toggleClass('d-none', step !== totalSteps);
        updateSubmitButtonState();

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(step) {
        if (step === 1) return true;

        const $step = $('.wizard-step[data-step="' + step + '"]');
        let valid = true;

        $step.find('[required]').each(function() {
            if (this.id === 'dateOfBirthInput') return;
            if (this.type === 'radio') return;
            if (this.type === 'checkbox') {
                if (!this.checked) {
                    this.reportValidity();
                    valid = false;
                }
                return;
            }
            if (this.type === 'file') {
                if (!this.files || !this.files.length) {
                    this.setCustomValidity(FILE_REQUIRED_ERROR);
                    this.reportValidity();
                    valid = false;
                    return false;
                }
                if (!handleFileSelected(this)) {
                    this.reportValidity();
                    valid = false;
                    return false;
                }
                return;
            }
            this.setCustomValidity('');
            if (!this.checkValidity()) {
                this.reportValidity();
                valid = false;
                return false;
            }
        });

        if (step === 2) {
            const dobInput = document.getElementById('dateOfBirthInput');
            if (dobInput) {
                if (!dobInput.value.trim()) {
                    dobInput.setCustomValidity('');
                    if (!dobInput.checkValidity()) {
                        dobInput.reportValidity();
                        valid = false;
                    }
                } else if (!validateDobField()) {
                    dobInput.reportValidity();
                    valid = false;
                }
            }
        }

        if (step === 3) {
            if (!$('input[name="membership_type_id"]:checked').length) {
                Swal.fire({
                    title: 'தேவை / Required',
                    text: 'உறுப்பினர் வகையைத் தேர்வு செய்யவும் / Please select a membership type.',
                    icon: 'warning'
                });
                return false;
            }
            if (parseFloat($('#amountPaid').val() || '0') <= 0) {
                Swal.fire({
                    title: 'தேவை / Required',
                    text: 'உறுப்பினர் வகையைத் தேர்வு செய்யவும் / Please select a membership type.',
                    icon: 'warning'
                });
                return false;
            }
        }

        return valid;
    }

    $('#startBtn').on('click', function() {
        currentStep = 2;
        showStep(currentStep);
    });

    $('#nextBtn').on('click', function() {
        if (!validateStep(currentStep)) return;
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    });

    $('#prevBtn').on('click', function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    function updateMembershipSelection($input) {
        if (!$input || !$input.length) return;
        const fee = $input.data('fee');
        const slug = $input.data('slug') || '';
        const years = $input.data('years') || '';
        $('#amountPaid').val(fee);
        $('#membershipTypeSlug').val(slug);
        $('#membershipFeeValue').val(fee);
        $('#membershipValidityYears').val(years);
    }

    $('input[name="membership_type_id"]').on('change', function() {
        updateMembershipSelection($(this));
    });

    const $selectedMembership = $('input[name="membership_type_id"]:checked');
    if ($selectedMembership.length) {
        updateMembershipSelection($selectedMembership);
    }

    $('#applicationForm').on('submit', function(e) {
        e.preventDefault();
        if (!validateAllFormSteps()) return;

        const $btn = $('#submitBtn');
        const $form = $(this);
        $btn.prop('disabled', true);

        ensureRemoteValidationBeforeSubmit().done(function() {
            if (!validateDeclaration() || !isSubmissionAllowed()) {
                updateSubmitButtonState();
                return;
            }

            $.ajax({
                url: BASE_URL + '/apply',
                method: 'POST',
                data: (function() {
                    const fd = new FormData($form[0]);
                    const token = $form.find('input[name="_csrf_token"]').val() || CSRF_TOKEN;
                    if (token && !fd.get('_csrf_token')) {
                        fd.set('_csrf_token', token);
                    }
                    return fd;
                })(),
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': $form.find('input[name="_csrf_token"]').val() || CSRF_TOKEN
                },
                success: function(res) {
                    if (res.success) {
                        const redirectUrl = res.redirect
                            || (BASE_URL + '/apply/success');
                        window.location.href = redirectUrl;
                        return;
                    }
                    Swal.fire('பிழை / Error', res.message || 'Submission failed.', 'error');
                },
                error: function(xhr) {
                    let msg = 'Submission failed. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (xhr.status === 403) {
                        msg = 'Your session has expired. Please refresh the page and submit again.';
                    } else if (xhr.status === 422) {
                        msg = xhr.responseJSON && xhr.responseJSON.message
                            ? xhr.responseJSON.message
                            : 'Please check the form and try again.';
                    }
                    Swal.fire('பிழை / Error', msg, 'error');
                },
                complete: function() {
                    updateSubmitButtonState();
                }
            });
        });
    });

    $('#trackBtn').on('click', function() {
        const num = $('#trackNumber').val().trim();
        if (!num) return;
        $.post(BASE_URL + '/track', { application_number: num, _csrf_token: CSRF_TOKEN }, function(res) {
            if (res.success && res.application) {
                const colors = { pending: 'warning', approved: 'success', rejected: 'danger' };
                const color = colors[res.application.status] || 'info';
                $('#trackResult').html(
                    '<div class="alert alert-' + color + ' mt-2">' +
                    '<span class="label-ta">நிலை: ' + res.application.status.replace('_', ' ').toUpperCase() + '</span><br>' +
                    '<span class="label-en">Status: ' + res.application.status.replace('_', ' ').toUpperCase() + '</span>' +
                    '<br><small>' + res.application.created_at + '</small></div>'
                );
            } else {
                $('#trackResult').html('<div class="alert alert-danger mt-2"><span class="label-ta">விண்ணப்பம் கிடைக்கவில்லை</span><br><span class="label-en">Application not found.</span></div>');
            }
        });
    });

    $(document).on('click', '.copy-bank-btn', function() {
        const text = $(this).data('copy');
        const copyDone = function() {
            Swal.fire({
                toast: true,
                position: 'top',
                icon: 'success',
                title: 'நகலெடுக்கப்பட்டது! / Copied!',
                showConfirmButton: false,
                timer: 1800
            });
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(copyDone);
        } else {
            const $tmp = $('<textarea>').val(text).appendTo('body').select();
            document.execCommand('copy');
            $tmp.remove();
            copyDone();
        }
    });

    initDobInput();
    initUploadAreas();
    initFieldValidation();
    initSinglePageUI();
})();
