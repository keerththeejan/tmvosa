<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Auth;
use App\Core\Security;

class AuditLog
{
    public static function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        Database::insert('audit_logs', [
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => Security::getClientIp(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);
    }

    public static function getAll(int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        $total = Database::fetch("SELECT COUNT(*) as cnt FROM audit_logs")['cnt'];

        $data = Database::fetchAll(
            "SELECT al.*, u.full_name as user_name
             FROM audit_logs al LEFT JOIN users u ON al.user_id = u.id
             ORDER BY al.created_at DESC LIMIT {$perPage} OFFSET {$offset}"
        );

        return ['data' => $data, 'total' => (int) $total, 'page' => $page];
    }
}
