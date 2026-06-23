<?php $pageTitle = 'Edit Member'; $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>
<form id="editMemberForm" enctype="multipart/form-data">
    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
    <div class="card mb-3"><div class="card-body">
        <div class="mb-3"><label class="form-label">Full Name (English)</label><input type="text" name="full_name_english" class="form-control" value="<?= \App\Core\View::escape($member['full_name_english']) ?>" required></div>
        <div class="mb-3"><label class="form-label">Full Name (Tamil)</label><input type="text" name="full_name_tamil" class="form-control" value="<?= \App\Core\View::escape($member['full_name_tamil'] ?? '') ?>"></div>
        <div class="row g-3 mb-3">
            <div class="col-6"><label class="form-label">Gender</label><select name="gender" class="form-select"><option value="male" <?= $member['gender']==='male'?'selected':'' ?>>Male</option><option value="female" <?= $member['gender']==='female'?'selected':'' ?>>Female</option></select></div>
            <div class="col-6"><label class="form-label">Status</label><select name="status" class="form-select"><?php foreach (['active','pending','suspended','expired'] as $s): ?><option value="<?= $s ?>" <?= $member['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="mb-3"><label class="form-label">NIC</label><input type="text" name="nic_number" class="form-control" value="<?= \App\Core\View::escape($member['nic_number'] ?? '') ?>"></div>
        <div class="mb-3"><label class="form-label">Mobile</label><input type="tel" name="mobile" class="form-control" value="<?= \App\Core\View::escape($member['mobile']) ?>"></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= \App\Core\View::escape($member['email'] ?? '') ?>"></div>
        <div class="mb-3"><label class="form-label">Country</label><select name="country_id" class="form-select"><option value="">Select</option><?php foreach ($countries as $c): ?><option value="<?= $c['id'] ?>" <?= $member['country_id']==$c['id']?'selected':'' ?>><?= \App\Core\View::escape($c['name']) ?></option><?php endforeach; ?></select></div>
        <div class="mb-3"><label class="form-label">Occupation</label><input type="text" name="occupation" class="form-control" value="<?= \App\Core\View::escape($member['occupation'] ?? '') ?>"></div>
        <div class="mb-3"><label class="form-label">Membership Type</label><select name="membership_type_id" class="form-select"><?php foreach ($membershipTypes as $t): ?><option value="<?= $t['id'] ?>" <?= $member['membership_type_id']==$t['id']?'selected':'' ?>><?= \App\Core\View::escape($t['name']) ?></option><?php endforeach; ?></select></div>
        <div class="mb-3"><label class="form-label">Update Photo</label><input type="file" name="photo" class="form-control" accept="image/*"></div>
    </div></div>
    <button type="submit" class="btn btn-primary btn-lg w-100">Save Changes</button>
</form>
<script>
$('#editMemberForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({ url: BASE_URL + '/members/<?= $member['id'] ?>/update', method: 'POST', data: new FormData(this), processData: false, contentType: false,
        success: function(res) { if (res.success) Swal.fire('Saved', res.message, 'success').then(() => location.href = BASE_URL + '/members/<?= $member['id'] ?>'); }
    });
});
</script>
