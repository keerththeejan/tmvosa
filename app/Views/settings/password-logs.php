<?php
use App\Core\View;

$pageTitle = 'Password Reset Logs';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-shield-exclamation"></i> Password Reset Logs</h5>
    <a href="<?= $base ?>/admin/users" class="btn btn-outline-secondary btn-sm">Back to Users</a>
</div>

<div class="table-responsive card border-0 shadow-sm">
    <table class="table table-sm table-striped mb-0" id="passwordLogsTable">
        <thead>
            <tr>
                <th>Date &amp; Time</th>
                <th>User</th>
                <th>Action</th>
                <th>Target User ID</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs['data'] as $log): ?>
            <tr>
                <td><small><?= date('d M Y H:i', strtotime($log['created_at'])) ?></small></td>
                <td><?= View::escape($log['user_name'] ?? 'System') ?></td>
                <td><span class="badge bg-<?= $log['action'] === 'password_reset' ? 'warning' : 'success' ?>"><?= View::escape($log['action']) ?></span></td>
                <td><?= (int) ($log['entity_id'] ?? 0) ?></td>
                <td><small><?= View::escape($log['ip_address'] ?? '') ?></small></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#passwordLogsTable').DataTable({ order: [[0, 'desc']], pageLength: 25 });
});
</script>
