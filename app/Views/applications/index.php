<?php
use App\Core\View;

$pageTitle = 'Applications';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$statuses = [
    '' => 'All',
    'pending' => 'Pending',
    'under_review' => 'Under Review',
    'approved' => 'Approved',
    'rejected' => 'Rejected',
];
?>
<h5 class="mb-3"><i class="bi bi-file-earmark-text"></i> Application Management</h5>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="btn-group btn-group-sm flex-wrap">
        <?php foreach ($statuses as $val => $label): ?>
        <a href="?status=<?= $val ?>" class="btn btn-outline-primary <?= ($currentStatus ?? '') === $val ? 'active' : '' ?>">
            <?= View::escape($label) ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="member-cards">
    <?php if (empty($applications['data'])): ?>
    <div class="text-center py-5 text-muted">No applications found.</div>
    <?php else: ?>
    <?php foreach ($applications['data'] as $app):
        $canDeleteApp = !($app['status'] === 'approved' && !empty($app['member_id']));
    ?>
    <div class="member-card application-card" data-app-id="<?= (int) $app['id'] ?>" data-app-number="<?= View::escape($app['application_number']) ?>" data-can-delete="<?= $canDeleteApp ? '1' : '0' ?>">
        <div class="card-top">
            <span class="badge bg-<?= match($app['status']) { 'approved' => 'success', 'rejected' => 'danger', 'pending' => 'warning', default => 'info' } ?>">
                <?= ucfirst(str_replace('_', ' ', $app['status'])) ?>
            </span>
            <div class="d-flex align-items-center gap-2">
                <?php if ($canDeleteApp): ?>
                <button type="button" class="btn btn-sm btn-outline-danger application-delete-btn" title="Delete application" aria-label="Delete application">
                    <i class="bi bi-trash"></i>
                </button>
                <?php endif; ?>
                <small class="text-muted"><?= date('d M Y', strtotime($app['created_at'])) ?></small>
            </div>
        </div>
        <a href="<?= $base ?>/applications/<?= $app['id'] ?>" class="application-card-link text-decoration-none text-body">
        <h6 class="mb-1"><?= View::escape($app['full_name_tamil'] ?: $app['full_name_english']) ?></h6>
        <?php if ($app['full_name_english'] && $app['full_name_tamil']): ?>
        <p class="text-muted small mb-1"><?= View::escape($app['full_name_english']) ?></p>
        <?php endif; ?>
        <p class="text-primary small mb-1"><?= View::escape($app['application_number']) ?></p>
        <div class="card-meta">
            <span><i class="bi bi-telephone"></i> <?= View::escape($app['mobile']) ?></span>
            <span><i class="bi bi-card-checklist"></i> <?= View::escape($app['membership_type_name'] ?? '') ?></span>
        </div>
        </a>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if (($applications['total_pages'] ?? 1) > 1): ?>
<nav class="mt-3">
    <ul class="pagination pagination-sm justify-content-center">
        <?php for ($i = 1; $i <= $applications['total_pages']; $i++): ?>
        <li class="page-item <?= $i === $applications['page'] ? 'active' : '' ?>">
            <a class="page-link" href="?status=<?= $currentStatus ?? '' ?>&page=<?= $i ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>
