<?php

namespace App\Models;

use App\Core\Database;

class Payment
{
    public static function findById(int $id): ?array
    {
        return Database::fetch(
            "SELECT p.*, m.full_name_english, m.membership_number
             FROM payments p JOIN members m ON p.member_id = m.id WHERE p.id = ?",
            [$id]
        );
    }

    public static function getAll(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['status'])) {
            $where[] = 'p.status = ?';
            $params[] = $filters['status'];
        }
        if (!empty($filters['from_date'])) {
            $where[] = 'p.payment_date >= ?';
            $params[] = $filters['from_date'];
        }
        if (!empty($filters['to_date'])) {
            $where[] = 'p.payment_date <= ?';
            $params[] = $filters['to_date'];
        }

        $whereClause = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $total = Database::fetch("SELECT COUNT(*) as cnt FROM payments p WHERE {$whereClause}", $params)['cnt'];

        $data = Database::fetchAll(
            "SELECT p.*, m.full_name_english, m.membership_number
             FROM payments p JOIN members m ON p.member_id = m.id
             WHERE {$whereClause} ORDER BY p.created_at DESC LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return ['data' => $data, 'total' => (int) $total, 'page' => $page, 'total_pages' => (int) ceil($total / $perPage)];
    }

    public static function create(array $data): int
    {
        return Database::insert('payments', $data);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('payments', $data, 'id = ?', [$id]);
    }

    public static function getRevenueStats(): array
    {
        return Database::fetch(
            "SELECT
                COALESCE(SUM(CASE WHEN status = 'verified' THEN amount ELSE 0 END), 0) as total_revenue,
                COALESCE(SUM(CASE WHEN status = 'verified' AND MONTH(payment_date) = MONTH(CURDATE()) AND YEAR(payment_date) = YEAR(CURDATE()) THEN amount ELSE 0 END), 0) as monthly_revenue,
                COALESCE(SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END), 0) as outstanding
             FROM payments"
        );
    }

    public static function getRevenueGrowth(int $months = 12): array
    {
        return Database::fetchAll(
            "SELECT DATE_FORMAT(payment_date, '%Y-%m') as month, SUM(amount) as total
             FROM payments WHERE status = 'verified' AND payment_date >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
             GROUP BY month ORDER BY month",
            [$months]
        );
    }

    public static function getRecent(int $limit = 5): array
    {
        return Database::fetchAll(
            "SELECT p.*, m.full_name_english, m.membership_number
             FROM payments p
             JOIN members m ON p.member_id = m.id
             ORDER BY p.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }
}
