<?php

namespace App\Models;

use App\Core\Database;

class Member
{
    public static function findById(int $id): ?array
    {
        return Database::fetch(
            "SELECT m.*, mt.name as membership_type_name, mt.fee, c.name as country_name
             FROM members m
             LEFT JOIN membership_types mt ON m.membership_type_id = mt.id
             LEFT JOIN countries c ON m.country_id = c.id
             WHERE m.id = ?",
            [$id]
        );
    }

    public static function findByNumber(string $number): ?array
    {
        return Database::fetch("SELECT * FROM members WHERE membership_number = ?", [$number]);
    }

    public static function search(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['search'])) {
            $where[] = "(m.full_name_english LIKE ? OR m.full_name_tamil LIKE ? OR m.membership_number LIKE ? OR m.nic_number LIKE ? OR m.mobile LIKE ?)";
            $term = '%' . $filters['search'] . '%';
            array_push($params, $term, $term, $term, $term, $term);
        }
        if (!empty($filters['status'])) {
            $where[] = "m.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['country_id'])) {
            $where[] = "m.country_id = ?";
            $params[] = $filters['country_id'];
        }
        if (!empty($filters['membership_type_id'])) {
            $where[] = "m.membership_type_id = ?";
            $params[] = $filters['membership_type_id'];
        }
        if (!empty($filters['batch'])) {
            $where[] = "m.studied_to_year = ?";
            $params[] = $filters['batch'];
        }
        if (!empty($filters['occupation'])) {
            $where[] = "m.occupation LIKE ?";
            $params[] = '%' . $filters['occupation'] . '%';
        }

        $whereClause = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $total = Database::fetch(
            "SELECT COUNT(*) as cnt FROM members m WHERE {$whereClause}",
            $params
        )['cnt'];

        $members = Database::fetchAll(
            "SELECT m.*, mt.name as membership_type_name, c.name as country_name
             FROM members m
             LEFT JOIN membership_types mt ON m.membership_type_id = mt.id
             LEFT JOIN countries c ON m.country_id = c.id
             WHERE {$whereClause}
             ORDER BY m.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        return [
            'data' => $members,
            'total' => (int) $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int) ceil($total / $perPage),
        ];
    }

    public static function create(array $data): int
    {
        return Database::insert('members', $data);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('members', $data, 'id = ?', [$id]);
    }

    public static function getStats(): array
    {
        return Database::fetch(
            "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status IN ('pending','under_review') THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'expired' OR (membership_expiry_date IS NOT NULL AND membership_expiry_date < CURDATE()) THEN 1 ELSE 0 END) as expired,
                SUM(CASE WHEN membership_expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as expiring,
                SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as new_this_month
             FROM members"
        );
    }

    public static function getGrowthData(int $months = 12): array
    {
        return Database::fetchAll(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count
             FROM members WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
             GROUP BY month ORDER BY month",
            [$months]
        );
    }

    public static function getCountryDistribution(): array
    {
        return Database::fetchAll(
            "SELECT c.name as country, COUNT(m.id) as count
             FROM members m JOIN countries c ON m.country_id = c.id
             WHERE m.status = 'active' GROUP BY c.name ORDER BY count DESC LIMIT 10"
        );
    }

    public static function getExpiringWithinDays(int $days = 30): array
    {
        return Database::fetchAll(
            "SELECT m.*, mt.name as membership_type_name
             FROM members m
             LEFT JOIN membership_types mt ON m.membership_type_id = mt.id
             WHERE m.status = 'active'
               AND m.email IS NOT NULL AND m.email != ''
               AND m.membership_expiry_date IS NOT NULL
               AND m.membership_expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
             ORDER BY m.membership_expiry_date ASC",
            [$days]
        );
    }

    public static function getTypeDistribution(): array
    {
        return Database::fetchAll(
            "SELECT mt.name as type, COUNT(m.id) as count
             FROM members m JOIN membership_types mt ON m.membership_type_id = mt.id
             WHERE m.status = 'active' GROUP BY mt.name"
        );
    }
}
