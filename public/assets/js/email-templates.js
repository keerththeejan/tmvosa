$(function() {
    $('.email-template-form').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const id = $form.data('id');
        $.post(BASE_URL + '/admin/email-templates/' + id, {
            _csrf_token: CSRF_TOKEN,
            subject: $form.find('[name=subject]').val(),
            body: $form.find('[name=body]').val(),
            is_active: $form.find('[name=is_active]').is(':checked') ? 1 : 0
        }, function(res) {
            Swal.fire(res.success ? 'Saved' : 'Error', res.message, res.success ? 'success' : 'error');
        }).fail(function(xhr) {
            const res = xhr.responseJSON || {};
            Swal.fire('Error', res.message || 'Could not save template.', 'error');
        });
    });
});
