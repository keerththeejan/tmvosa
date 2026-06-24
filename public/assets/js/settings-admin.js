$(function() {
    $('#settingsForm').on('submit', function(e) {
        e.preventDefault();
        $.post(BASE_URL + '/admin/settings', $(this).serialize(), function(res) {
            Swal.fire(res.success ? 'Saved' : 'Error', res.message, res.success ? 'success' : 'error');
        }).fail(function(xhr) {
            const res = xhr.responseJSON || {};
            Swal.fire('Error', res.message || 'Could not save settings.', 'error');
        });
    });
});
