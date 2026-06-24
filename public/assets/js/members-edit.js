$('#editMemberForm').on('submit', function(e) {
    e.preventDefault();

    const $form = $(this);
    const email = ($form.find('[name="email"]').val() || '').trim();

    if (!email) {
        Swal.fire('Validation Error', 'Email address is required.', 'error');
        return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        Swal.fire('Validation Error', 'Please enter a valid email address.', 'error');
        return;
    }

    const fd = new FormData(this);
    const token = $form.find('[name="_csrf_token"]').val() || CSRF_TOKEN;
    if (token && !fd.get('_csrf_token')) {
        fd.set('_csrf_token', token);
    }

    const memberId = $form.data('member-id');
    const $btn = $form.find('[type="submit"]');
    $btn.prop('disabled', true);

    $.ajax({
        url: BASE_URL + '/members/' + memberId + '/update',
        method: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-Token': token
        },
        success: function(res) {
            if (res.success) {
                let html = res.message || 'Member updated.';
                if (res.email_notice) {
                    html += '<br><small>' + res.email_notice + '</small>';
                }
                Swal.fire('Saved', html, 'success').then(function() {
                    window.location.href = BASE_URL + '/members/' + memberId;
                });
                return;
            }
            Swal.fire('Error', res.message || 'Could not save member.', 'error');
        },
        error: function(xhr) {
            const msg = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : 'Could not save member. Please try again.';
            Swal.fire('Error', msg, 'error');
        },
        complete: function() {
            $btn.prop('disabled', false);
        }
    });
});
