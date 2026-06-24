<?php

namespace App\Core;

class Session
{
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        $_SESSION = [];
        $name = session_name();
        $params = session_get_cookie_params();

        setcookie($name, '', [
            'expires' => time() - 42000,
            'path' => $params['path'] ?: App::sessionCookiePath(),
            'domain' => $params['domain'] ?? '',
            'secure' => $params['secure'] ?? false,
            'httponly' => $params['httponly'] ?? true,
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);

        session_destroy();
    }

    public static function regenerate(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function flash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    public static function getFlash(): array
    {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }

    public static function checkTimeout(): void
    {
        $timeout = App::config('app.session_timeout', 3600);
        $lastActivity = self::get('last_activity');

        if ($lastActivity && (time() - $lastActivity > $timeout)) {
            self::destroy();
            if (!str_contains($_SERVER['REQUEST_URI'] ?? '', '/login')) {
                header('Location: ' . self::baseUrl() . '/login?timeout=1');
                exit;
            }
        }

        self::set('last_activity', time());
    }

    private static function baseUrl(): string
    {
        return App::sessionCookiePath() === '/'
            ? ''
            : App::sessionCookiePath();
    }
}
