<?php
use App\Core\View;

$pageTitle = 'Membership Cards';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<h5 class="mb-3"><i class="bi bi-person-vcard"></i> Membership Cards</h5>

<form method="get" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search members..." value="<?= View::escape($search ?? '') ?>">
        <button class="btn btn-outline-primary" type="submit">Search</button>
    </div>
</form>

<form method="post" action="<?= $base ?>/membership-cards/bulk-print" target="_blank" id="bulkPrintForm">
    <input type="hidden" name="_csrf_token" value="<?= $csrfToken ?>">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <small class="text-muted"><?= (int) ($members['total'] ?? 0) ?> active members</small>
        <button type="submit" class="btn btn-sm btn-outline-dark">Bulk Print Selected</button>
    </div>

    <div class="member-cards">
        <?php if (empty($members['data'])): ?>
        <div class="text-center py-5 text-muted">No active members found.</div>
        <?php else: ?>
        <?php foreach ($members['data'] as $m): ?>
        <div class="member-card">
            <div class="form-check me-2">
                <input class="form-check-input" type="checkbox" name="member_ids[]" value="<?= (int) $m['id'] ?>" onclick="event.stopPropagation()">
            </div>
            <div class="card-body-content flex-fill" onclick="location.href='<?= $base ?>/card/<?= $m['id'] ?>'" style="cursor:pointer">
                <h6 class="mb-0"><?= View::escape($m['full_name_english']) ?></h6>
                <small class="text-primary"><?= View::escape($m['membership_number']) ?></small>
                <div class="mt-1">
                    <span class="badge bg-light text-dark"><?= View::escape(\App\Helpers\MembershipType::bilingualLabel($m['membership_type_name'] ?? '', $m['membership_type_slug'] ?? null)) ?></span>
                </div>
            </div>
            <i class="bi bi-chevron-right text-muted"></i>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</form>

<?php if (($members['total_pages'] ?? 1) > 1): ?>
<nav class="mt-3"><ul class="pagination pagination-sm justify-content-center">
    <?php for ($i = 1; $i <= $members['total_pages']; $i++): ?>
    <li class="page-item <?= $i === (int)$members['page'] ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>"><?= $i ?></a>
    </li>
    <?php endfor; ?>
</ul></nav>
<?php endif; ?>
