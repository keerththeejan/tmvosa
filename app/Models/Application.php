<?php

namespace App\Models;

use App\Core\Database;

class Application
{
    public static function findById(int $id): ?array
    {
        return Database::fetch(
            "SELECT a.*, mt.name as membership_type_name, mt.fee, c.name as country_name
             FROM member_applications a
             LEFT JOIN membership_types mt ON a.membership_type_id = mt.id
             LEFT JOIN countries c ON a.country_id = c.id
             WHERE a.id = ?",
            [$id]
        );
    }

    public static function getAll(string $status = '', int $page = 1, int $perPage = 20): array
    {
        $where = '1=1';
        $params = [];

        if ($status) {
            $where = 'a.status = ?';
            $params[] = $status;
        }

        $offset = ($page - 1) * $perPage;
        $total = Database::fetch(
            "SELECT COUNT(*) as cnt FROM member_applications a WHERE {$where}",
            $params
        )['cnt'];

        $data = Database::fetchAll(
            "SELECT a.*, mt.name as membership_type_name
             FROM member_applications a
             LEFT JOIN membership_types mt ON a.membership_type_id = mt.id
             WHERE {$where}
             ORDER BY a.created_at DESC LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return ['data' => $data, 'total' => (int) $total, 'page' => $page, 'total_pages' => (int) ceil($total / $perPage)];
    }

    public static function create(array $data): int
    {
        return Database::insert('member_applications', $data);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('member_applications', $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete('member_applications', 'id = ?', [$id]);
    }

    public static function getPendingCount(): int
    {
        return (int) Database::fetch(
            "SELECT COUNT(*) as cnt FROM member_applications WHERE status IN ('pending','under_review')"
        )['cnt'];
    }

    public static function getStatusCounts(): array
    {
        return Database::fetch(
            "SELECT
                SUM(CASE WHEN status IN ('pending','under_review') THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
             FROM member_applications"
        ) ?: ['pending' => 0, 'approved' => 0, 'rejected' => 0];
    }
}
