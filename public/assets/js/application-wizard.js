// 4-page Application Wizard
(function() {
    let currentStep = 1;
    const totalSteps = 4;
    const DOB_MIN_YEAR = 1940;
    const DOB_INVALID_MSG = 'தயவுசெய்து சரியான பிறந்த திகதியை உள்ளிடவும். Please enter a valid Date of Birth.';

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
                    this.setCustomValidity('Please upload this document.');
                    this.reportValidity();
                    valid = false;
                    return false;
                }
                this.setCustomValidity('');
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

    $('.upload-area').on('click', function() {
        $(this).find('input[type="file"]').click();
    });

    $('.upload-area input[type="file"]').on('change', function() {
        const $area = $(this).closest('.upload-area');
        const file = this.files[0];
        if (!file) return;
        this.setCustomValidity('');
        $area.addClass('has-file');
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $area.find('.preview').removeClass('d-none').find('img').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        } else {
            $area.find('.preview').removeClass('d-none').html('<i class="bi bi-file-pdf fs-1 text-danger"></i>');
        }
    });

    $('#applicationForm').on('submit', function(e) {
        e.preventDefault();
        if (!validateDobField()) {
            document.getElementById('dateOfBirthInput').reportValidity();
            currentStep = 2;
            showStep(2);
            return;
        }
        if (!validateStep(4)) return;

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
                        $('.upload-area').removeClass('has-file').find('.preview').addClass('d-none');
                        currentStep = 1;
                        showStep(1);
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
    showStep(1);
})();
