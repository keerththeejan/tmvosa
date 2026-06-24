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

    $('#applyCpanelSmtpBtn').on('click', function() {
        const $form = $('#emailSettingsForm');
        $form.find('[name=smtp_host]').val('localhost');
        $form.find('[name=smtp_port]').val('587');
        $form.find('[name=smtp_encryption]').val('tls');

        Swal.fire({
            title: 'cPanel SMTP preset applied',
            html: 'Host: <strong>localhost</strong><br>Port: <strong>587</strong><br>Encryption: <strong>TLS</strong><br><br>'
                + 'Also ensure your server <code>.env</code> has:<br>'
                + '<code>SMTP_HOST=localhost</code><br>'
                + '<code>SMTP_PORT=587</code><br>'
                + '<code>SMTP_ENCRYPTION=tls</code><br>'
                + '<code>SMTP_PASSWORD="your-mailbox-password"</code>',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Save & test',
            cancelButtonText: 'Close'
        }).then(function(result) {
            if (!result.isConfirmed) return;
            const $btn = $('#saveEmailSettingsBtn').prop('disabled', true);
            $.post(BASE_URL + '/admin/email-settings', collectEmailSettings(), function(res) {
                if (res.success) {
                    Swal.fire('Saved', res.message, 'success');
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }).always(function() {
                $btn.prop('disabled', false);
            });
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
            Swal.fire(res.success ? 'Sent' : 'Failed', res.message || res.error || 'Unknown error.', res.success ? 'success' : 'error');
        }).fail(function(xhr) {
            let msg = 'Test email failed.';
            if (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error)) {
                msg = xhr.responseJSON.message || xhr.responseJSON.error;
            } else if (xhr.responseText && xhr.responseText.indexOf('{') !== -1) {
                try {
                    const parsed = JSON.parse(xhr.responseText);
                    msg = parsed.message || parsed.error || msg;
                } catch (e) {}
            } else if (xhr.status === 403) {
                msg = 'Session expired. Please log in again and retry.';
            } else if (xhr.status === 500) {
                msg = 'Server error (500). Check that .env exists on the server with SMTP_PASSWORD set.';
            }
            Swal.fire('Failed', msg, 'error');
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
