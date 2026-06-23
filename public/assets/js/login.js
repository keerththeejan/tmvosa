$('#loginForm').on('submit', function(e) {
    e.preventDefault();
    const btn = $('#loginBtn');
    btn.prop('disabled', true).find('.btn-text').addClass('d-none');
    btn.find('.spinner-border').removeClass('d-none');
    $.post(BASE_URL + '/login', $(this).serialize(), function(res) {
        if (res.success) {
            window.location.href = res.redirect;
            return;
        }
        Swal.fire('Error', res.message, 'error');
    }, 'json').fail(function(xhr) {
        let message = 'Login failed. Please try again.';
        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        }
        Swal.fire('Error', message, 'error');
    }).always(function() {
        btn.prop('disabled', false).find('.btn-text').removeClass('d-none');
        btn.find('.spinner-border').addClass('d-none');
    });
});
