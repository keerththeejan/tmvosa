$(function() {
    const $page = $('#applicationPage');
    if (!$page.length) return;

    const appId = $page.attr('data-app-id');
    const appNumber = $page.attr('data-app-number') || '';
    const canDelete = $page.attr('data-can-delete') === '1';
    const canReview = $page.attr('data-can-review') === '1';

    $('.admin-doc-upload').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const fd = new FormData();
        fd.append('document_type', $form.data('type'));
        fd.append('document', $form.find('input[type="file"]')[0].files[0]);
        fd.append('_csrf_token', CSRF_TOKEN);

        $.ajax({
            url: BASE_URL + '/applications/' + appId + '/documents',
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    Swal.fire('Uploaded', res.message, 'success').then(function() { location.reload(); });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Upload failed.';
                Swal.fire('Error', msg, 'error');
            }
        });
    });

    if (canReview) {
        $('#approveBtn').on('click', function() {
            Swal.fire({ title: 'Approve Application?', icon: 'question', showCancelButton: true, confirmButtonText: 'Approve' })
                .then(function(r) {
                    if (!r.isConfirmed) return;
                    $.post(BASE_URL + '/applications/' + appId + '/approve', { _csrf_token: CSRF_TOKEN }, function(res) {
                        if (res.success) {
                            Swal.fire('Approved!', 'Membership: ' + res.membership_number, 'success').then(function() { location.reload(); });
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    });
                });
        });

        $('#rejectBtn').on('click', function() {
            Swal.fire({ title: 'Reject Application', input: 'textarea', inputLabel: 'Reason', showCancelButton: true })
                .then(function(r) {
                    if (!r.isConfirmed) return;
                    $.post(BASE_URL + '/applications/' + appId + '/reject', { _csrf_token: CSRF_TOKEN, reason: r.value }, function(res) {
                        if (res.success) {
                            Swal.fire('Rejected', res.message, 'info').then(function() { location.reload(); });
                        }
                    });
                });
        });
    }

    if (canDelete) {
        $('#deleteAppBtn').on('click', function() {
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
                            window.location.href = res.redirect || (BASE_URL + '/applications');
                        });
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                }).fail(function(xhr) {
                    const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Delete failed.';
                    Swal.fire('Error', msg, 'error');
                });
            });
        });
    }
});
