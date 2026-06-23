<?php
use App\Core\View;

$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$pageTitleMap = [
    'Dashboard' => 'dashboard',
    'Members' => 'member_management',
    'Applications' => 'application_management',
    'Payments' => 'payment_management',
    'Reports' => 'reports',
    'Membership Card' => 'membership_card',
    'Login' => 'sign_in',
    'Add Member' => 'add_member',
    'Edit Member' => 'edit_member',
    'Member Details' => 'member_details',
    'Users' => 'user_management',
    'Settings' => 'settings',
    'Audit Logs' => 'audit_logs',
    'Application Details' => 'application_details',
];
$titleKey = $pageTitleMap[$pageTitle ?? ''] ?? null;
?>
<header class="topbar d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-link text-dark d-lg-none p-0" id="sidebarToggle"><i class="bi bi-list fs-4"></i></button>
        <?php if ($titleKey): ?>
            <div class="page-title bilingual-text bilingual-block mb-0">
                <?php View::text($titleKey, 'h5', true, 'mb-0'); ?>
            </div>
        <?php else: ?>
            <h5 class="mb-0 page-title"><?= View::escape($pageTitle ?? 'Dashboard') ?></h5>
        <?php endif; ?>
    </div>
    <div class="dropdown">
        <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle"></i> <?= View::escape($user['full_name'] ?? '') ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><span class="dropdown-item-text text-muted small"><?= View::escape($user['role_name'] ?? '') ?></span></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item bilingual-text" href="<?= $base ?>/logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <?php View::text('logout', 'span', true); ?>
                </a>
            </li>
        </ul>
    </div>
</header>
