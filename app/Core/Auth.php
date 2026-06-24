<?php

namespace App\Core;

use App\Models\User;
use App\Models\AuditLog;

class Auth
{
    public static function attempt(string $username, string $password): bool
    {
        $user = User::findByUsernameOrEmail($username);

        if (!$user || !$user['is_active']) {
            return false;
        }

        if (!Security::verifyPassword($password, $user['password'])) {
            return false;
        }

        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role_slug']);
        Session::set('user_name', $user['full_name']);
        Session::regenerate();

        User::updateLastLogin($user['id'], Security::getClientIp());
        AuditLog::log('login', 'users', $user['id']);

        return true;
    }

    public static function logout(): void
    {
        $userId = Session::get('user_id');
        if ($userId) {
            AuditLog::log('logout', 'users', $userId);
        }
        Session::destroy();
    }

    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        return User::findById(Session::get('user_id'));
    }

    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    public static function role(): ?string
    {
        return Session::get('user_role');
    }

    public static function hasRole(string ...$roles): bool
    {
        return in_array(self::role(), $roles);
    }

    public static function can(string $permission): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }

        if ($user['role_slug'] === 'super_admin') {
            return true;
        }

        $permissions = json_decode($user['permissions'] ?? '[]', true);
        return in_array($permission, $permissions) || in_array('*', $permissions);
    }
}
