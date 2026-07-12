<?php $pageTitle = 'Audit Logs'; ?>
<div class="table-responsive">
    <table class="table table-sm table-striped" id="auditTable">
        <thead><tr><th>Date</th><th>User</th><th>Action</th><th>Entity</th><th>IP</th></tr></thead>
        <tbody>
            <?php foreach ($logs['data'] as $log): ?>
            <tr>
                <td><small><?= date('d M Y H:i', strtotime($log['created_at'])) ?></small></td>
                <td><?= \App\Core\View::escape($log['user_name'] ?? 'System') ?></td>
                <td><span class="badge bg-secondary"><?= \App\Core\View::escape($log['action']) ?></span></td>
                <td><small><?= \App\Core\View::escape($log['entity_type'] ?? '') ?> #<?= $log['entity_id'] ?? '' ?></small></td>
                <td><small><?= \App\Core\View::escape($log['ip_address'] ?? '') ?></small></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>$(document).ready(function() { $('#auditTable').DataTable({ order: [[0,'desc']], pageLength: 25, scrollX: true, autoWidth: false }); });</script>
