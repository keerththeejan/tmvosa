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
        return true;
    }

    function initSinglePageUI() {
        $('.wizard-step--always-active').addClass('active');
        $('#wizardProgress').addClass('d-none');
        $('#startBtn, #navButtons').addClass('d-none');
        $('#submitBtn').removeClass('d-none').addClass('w-100');
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

    $('input[name="membership_type_id"]').on('change', function() {
        $('#amountPaid').val($(this).data('fee'));
    });

    $('#applicationForm').on('submit', function(e) {
        e.preventDefault();
        if (!validateAllFormSteps()) return;

        const $btn = $('#submitBtn');
        $btn.prop('disabled', true);

        $.ajax({
            url: BASE_URL + '/apply',
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        title: 'விண்ணப்பம் சமர்ப்பிக்கப்பட்டது! / Application Submitted!',
                        html: 'உங்கள் விண்ணப்ப இலக்கம் / Application number:<br><strong>' + res.application_number + '</strong>',
                        icon: 'success',
                        confirmButtonText: 'சரி / OK'
                    }).then(function() {
                        $('#applicationForm')[0].reset();
                        $('#dateOfBirthHidden').val('');
                        resetAllUploads();
                        initSinglePageUI();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    });
                } else {
                    Swal.fire('பிழை / Error', res.message || 'Submission failed.', 'error');
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Submission failed. Please try again.';
                Swal.fire('பிழை / Error', msg, 'error');
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
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
    initSinglePageUI();
})();
