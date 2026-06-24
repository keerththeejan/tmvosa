<?php
use App\Core\View;
use App\Helpers\Lang;

$pageTitle = 'Members';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<h5 class="mb-3"><i class="bi bi-people"></i> Member Management</h5>

<div class="search-bar mb-3">
    <form id="searchForm" class="row g-2">
        <div class="col-12">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Search name, NIC, mobile, membership number..." value="<?= View::escape($filters['search'] ?? '') ?>">
            </div>
        </div>
        <div class="col-6">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Status</option>
                <?php foreach (['active','pending','under_review','approved','suspended','expired'] as $s): ?>
                <option value="<?= $s ?>" <?= ($filters['status'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$s)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6">
            <select name="membership_type_id" class="form-select form-select-sm">
                <option value="">All Types</option>
                <?php foreach ($membershipTypes as $t): ?>
                <option value="<?= $t['id'] ?>" <?= ($filters['membership_type_id'] ?? '') == $t['id'] ? 'selected' : '' ?>><?= View::escape($t['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <small class="text-muted"><?= number_format($members['total']) ?> members found</small>
    <a href="<?= $base ?>/members/create" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Add Member
    </a>
</div>

<div class="member-cards" id="memberCards">
    <?php foreach ($members['data'] as $m): ?>
    <div class="member-card" onclick="location.href='<?= $base ?>/members/<?= $m['id'] ?>'">
        <div class="card-avatar">
            <?php if ($m['photo']): ?>
            <img src="<?= $base ?>/../storage/uploads/<?= $m['photo'] ?>" alt="">
            <?php else: ?>
            <div class="avatar-placeholder"><?= strtoupper(substr($m['full_name_english'], 0, 1)) ?></div>
            <?php endif; ?>
        </div>
        <div class="card-body-content">
            <h6 class="mb-0"><?= View::escape($m['full_name_english']) ?></h6>
            <small class="text-primary"><?= View::escape($m['membership_number']) ?></small>
            <div class="card-meta mt-1">
                <span><i class="bi bi-telephone"></i> <?= View::escape($m['mobile']) ?></span>
                <?php if ($m['country_name']): ?><span><i class="bi bi-geo-alt"></i> <?= View::escape($m['country_name']) ?></span><?php endif; ?>
            </div>
            <div class="mt-1">
                <span class="badge bg-<?= $m['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($m['status']) ?></span>
                <span class="badge bg-light text-dark"><?= View::escape($m['membership_type_name'] ?? '') ?></span>
            </div>
        </div>
        <i class="bi bi-chevron-right text-muted"></i>
    </div>
    <?php endforeach; ?>
</div>

<?php if ($members['total_pages'] > 1): ?>
<nav class="mt-3"><ul class="pagination pagination-sm justify-content-center">
    <?php for ($i = 1; $i <= $members['total_pages']; $i++): ?>
    <li class="page-item <?= $i === $members['page'] ? 'active' : '' ?>">
        <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>"><?= $i ?></a>
    </li>
    <?php endfor; ?>
</ul></nav>
<?php endif; ?>

<script>
$('#searchForm select, #searchForm input').on('change input', function() {
    clearTimeout(window.searchTimer);
    window.searchTimer = setTimeout(() => $('#searchForm').submit(), 500);
});
</script>
