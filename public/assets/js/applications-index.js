$(function() {
    $('.application-delete-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $card = $(this).closest('.application-card');
        const appId = $card.attr('data-app-id');
        const appNumber = $card.attr('data-app-number');

        if (!appId) {
            Swal.fire('Error', 'Application ID not found.', 'error');
            return;
        }

        Swal.fire({
            title: 'Delete Application?',
            html: 'This will permanently delete <strong>' + appNumber + '</strong> and its uploaded documents.<br>This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then(function(r) {
            if (!r.isConfirmed) return;

            $.ajax({
                url: BASE_URL + '/applications/' + appId + '/delete',
                method: 'POST',
                dataType: 'json',
                data: { _csrf_token: CSRF_TOKEN }
            }).done(function(res) {
                if (res.success) {
                    Swal.fire('Deleted', res.message, 'success').then(function() {
                        $card.fadeOut(200, function() { $(this).remove(); });
                    });
                } else {
                    Swal.fire('Error', res.message || 'Delete failed.', 'error');
                }
            }).fail(function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Delete failed.';
                Swal.fire('Error', msg, 'error');
            });
        });
    });
});
