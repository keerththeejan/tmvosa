$(document).ready(function() {
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $btn = $form.find('button[type=submit]').prop('disabled', true);

        $.ajax({
            url: BASE_URL + '/settings/password',
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(res) {
            if (res && res.success) {
                Swal.fire('Success', res.message, 'success').then(function() {
                    window.location.href = res.redirect || (BASE_URL + '/dashboard');
                });
            } else {
                Swal.fire('Error', (res && res.message) ? res.message : 'Unable to update password.', 'error');
            }
        }).fail(function(xhr) {
            var res = xhr.responseJSON || {};
            var msg = res.message || 'Unable to update password. Please use the form and click Update Password (do not open a URL with passwords in the address bar).';
            Swal.fire('Error', msg, 'error');
        }).always(function() {
            $btn.prop('disabled', false);
        });
    });
});
