<?php

namespace App\Models;

use App\Core\Database;

class Application
{
    public static function findById(int $id): ?array
    {
        return Database::fetch(
            "SELECT a.*, mt.name as membership_type_name, mt.slug as membership_type_slug, mt.fee, c.name as country_name
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
            "SELECT a.*, mt.name as membership_type_name, mt.slug as membership_type_slug
             FROM member_applications a
             LEFT JOIN membership_types mt ON a.membership_type_id = mt.id
             WHERE {$where}
             ORDER BY a.created_at DESC LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return ['data' => $data, 'total' => (int) $total, 'page' => $page, 'total_pages' => (int) ceil($total / $perPage)];
    }

    private static function insertableColumns(): array
    {
        return [
            'application_number', 'member_id', 'full_name_tamil', 'full_name_english',
            'gender', 'date_of_birth', 'nic_number', 'current_address', 'permanent_address',
            'country_id', 'mobile', 'whatsapp', 'email', 'studied_from_year', 'studied_to_year',
            'grade_stream', 'teacher_name', 'occupation', 'company', 'proposer_name',
            'proposer_contact', 'membership_type_id', 'amount_paid', 'payment_method',
            'transaction_number', 'payment_date', 'status', 'rejection_reason', 'reviewed_by',
            'reviewed_at', 'ip_address', 'user_agent', 'created_at',
        ];
    }

    public static function create(array $data): int
    {
        $allowed = array_flip(self::insertableColumns());
        $payload = array_intersect_key($data, $allowed);

        if (!isset($payload['created_at'])) {
            $payload['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert('member_applications', $payload);
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
        $row = Database::fetch(
            "SELECT
                COALESCE(SUM(CASE WHEN status IN ('pending','under_review') THEN 1 ELSE 0 END), 0) as pending,
                COALESCE(SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END), 0) as approved,
                COALESCE(SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END), 0) as rejected
             FROM member_applications"
        );

        return [
            'pending' => (int) ($row['pending'] ?? 0),
            'approved' => (int) ($row['approved'] ?? 0),
            'rejected' => (int) ($row['rejected'] ?? 0),
        ];
    }

    public static function getRecent(int $limit = 5): array
    {
        $limit = max(1, min(20, $limit));

        return Database::fetchAll(
            "SELECT a.id, a.application_number, a.full_name_tamil, a.full_name_english,
                    a.mobile, a.status, a.created_at, mt.name as membership_type_name
             FROM member_applications a
             LEFT JOIN membership_types mt ON a.membership_type_id = mt.id
             ORDER BY a.created_at DESC
             LIMIT {$limit}"
        );
    }
}
