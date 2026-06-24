<?php
use App\Core\View;

$pageTitle = 'Membership Cards';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<h5 class="mb-3"><i class="bi bi-person-vcard"></i> Membership Cards</h5>

<div class="member-cards">
    <?php if (empty($members['data'])): ?>
    <div class="text-center py-5 text-muted">No active members found.</div>
    <?php else: ?>
    <?php foreach ($members['data'] as $m): ?>
    <div class="member-card" onclick="location.href='<?= $base ?>/card/<?= $m['id'] ?>'">
        <div class="card-body-content">
            <h6 class="mb-0"><?= View::escape($m['full_name_english']) ?></h6>
            <small class="text-primary"><?= View::escape($m['membership_number']) ?></small>
            <div class="mt-1">
                <span class="badge bg-light text-dark"><?= View::escape($m['membership_type_name'] ?? '') ?></span>
            </div>
        </div>
        <i class="bi bi-chevron-right text-muted"></i>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
