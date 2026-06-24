$(function() {
    function collectEmailSettings() {
        const $form = $('#emailSettingsForm');
        return {
            _csrf_token: CSRF_TOKEN,
            smtp_host: $form.find('[name=smtp_host]').val(),
            smtp_port: $form.find('[name=smtp_port]').val(),
            smtp_encryption: $form.find('[name=smtp_encryption]').val(),
            smtp_username: $form.find('[name=smtp_username]').val(),
            from_name: $('[name=from_name]').val(),
            from_email: $('[name=from_email]').val(),
            admin_notification_email: $('[name=admin_notification_email]').val()
        };
    }

    $('#saveEmailSettingsBtn').on('click', function() {
        const $btn = $(this).prop('disabled', true);
        $.post(BASE_URL + '/admin/email-settings', collectEmailSettings(), function(res) {
            Swal.fire(res.success ? 'Saved' : 'Error', res.message, res.success ? 'success' : 'error');
        }).fail(function(xhr) {
            const res = xhr.responseJSON || {};
            Swal.fire('Error', res.message || 'Could not save email settings.', 'error');
        }).always(function() {
            $btn.prop('disabled', false);
        });
    });

    $('#sendTestEmailBtn').on('click', function() {
        const $btn = $(this).prop('disabled', true);
        $.ajax({
            url: BASE_URL + '/admin/email-settings/test',
            method: 'POST',
            dataType: 'json',
            data: {
                _csrf_token: CSRF_TOKEN,
                test_email: $('#testEmailAddress').val()
            }
        }).done(function(res) {
            Swal.fire(res.success ? 'Sent' : 'Failed', res.message, res.success ? 'success' : 'error');
        }).fail(function(xhr) {
            const res = xhr.responseJSON || {};
            Swal.fire('Failed', res.message || res.error || 'Test email failed.', 'error');
        }).always(function() {
            $btn.prop('disabled', false);
        });
    });

    $('#sendExpiryRemindersBtn').on('click', function() {
        Swal.fire({
            title: 'Send expiry reminders?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Send'
        }).then(function(result) {
            if (!result.isConfirmed) return;
            $.post(BASE_URL + '/admin/email/send-expiry-reminders', {
                _csrf_token: CSRF_TOKEN,
                days: $('#expiryReminderDays').val()
            }, function(res) {
                Swal.fire(res.success ? 'Done' : 'Error', res.message, res.success ? 'success' : 'error');
            });
        });
    });
});
