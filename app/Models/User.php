<?php

namespace App\Models;

use App\Core\Database;

class User
{
    public static function findById(int $id): ?array
    {
        return Database::fetch(
            "SELECT u.*, r.name as role_name, r.slug as role_slug, r.permissions
             FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = ?",
            [$id]
        );
    }

    public static function findByUsernameOrEmail(string $value): ?array
    {
        return Database::fetch(
            "SELECT u.*, r.slug as role_slug, r.permissions
             FROM users u JOIN roles r ON u.role_id = r.id
             WHERE u.username = ? OR u.email = ?",
            [$value, $value]
        );
    }

    public static function getAll(): array
    {
        return Database::fetchAll(
            "SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id ORDER BY u.created_at DESC"
        );
    }

    public static function create(array $data): int
    {
        return Database::insert('users', $data);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('users', $data, 'id = ?', [$id]);
    }

    public static function updatePassword(int $id, string $hash): void
    {
        Database::update('users', [
            'password' => $hash,
            'password_changed_at' => date('Y-m-d H:i:s'),
            'force_password_change' => 0,
        ], 'id = ?', [$id]);
    }

    public static function updateLastLogin(int $id, string $ip): void
    {
        Database::update('users', [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $ip,
        ], 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete('users', 'id = ?', [$id]);
    }
}
