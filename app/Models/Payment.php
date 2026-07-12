<?php

namespace App\Models;

use App\Core\Database;

class Payment
{
    public static function findById(int $id): ?array
    {
        return Database::fetch(
            "SELECT p.*, m.full_name_english, m.membership_number, m.status as member_status,
                    pr.id as receipt_id, pr.receipt_number
             FROM payments p
             JOIN members m ON p.member_id = m.id
             LEFT JOIN payment_receipts pr ON pr.payment_id = p.id
             WHERE p.id = ?",
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
        if (!empty($filters['search'])) {
            $where[] = '(m.full_name_english LIKE ? OR m.membership_number LIKE ? OR p.transaction_number LIKE ?)';
            $term = '%' . $filters['search'] . '%';
            array_push($params, $term, $term, $term);
        }

        $whereClause = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $total = Database::fetch(
            "SELECT COUNT(*) as cnt FROM payments p JOIN members m ON p.member_id = m.id WHERE {$whereClause}",
            $params
        )['cnt'];

        $data = Database::fetchAll(
            "SELECT p.*, m.full_name_english, m.membership_number,
                    pr.id as receipt_id, pr.receipt_number
             FROM payments p
             JOIN members m ON p.member_id = m.id
             LEFT JOIN payment_receipts pr ON pr.payment_id = p.id
             WHERE {$whereClause}
             ORDER BY p.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return [
            'data' => $data,
            'total' => (int) $total,
            'page' => $page,
            'total_pages' => (int) ceil($total / $perPage),
        ];
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
                COALESCE(SUM(CASE WHEN status = 'verified' AND payment_date = CURDATE() THEN amount ELSE 0 END), 0) as today_revenue,
                COALESCE(SUM(CASE WHEN status = 'verified' AND MONTH(payment_date) = MONTH(CURDATE()) AND YEAR(payment_date) = YEAR(CURDATE()) THEN amount ELSE 0 END), 0) as monthly_revenue,
                COALESCE(SUM(CASE WHEN status = 'verified' AND YEAR(payment_date) = YEAR(CURDATE()) THEN amount ELSE 0 END), 0) as annual_revenue,
                COALESCE(SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END), 0) as outstanding,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count
             FROM payments"
        ) ?: [
            'total_revenue' => 0,
            'today_revenue' => 0,
            'monthly_revenue' => 0,
            'annual_revenue' => 0,
            'outstanding' => 0,
            'pending_count' => 0,
        ];
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

    public static function getOutstandingMembers(int $limit = 20): array
    {
        return Database::fetchAll(
            "SELECT m.id, m.full_name_english, m.membership_number, m.membership_expiry_date, m.status, mt.fee
             FROM members m
             LEFT JOIN membership_types mt ON m.membership_type_id = mt.id
             WHERE m.status IN ('expired', 'active')
               AND (
                    m.membership_expiry_date IS NOT NULL AND m.membership_expiry_date < CURDATE()
                    OR m.status = 'expired'
               )
             ORDER BY m.membership_expiry_date ASC
             LIMIT ?",
            [$limit]
        );
    }
}
