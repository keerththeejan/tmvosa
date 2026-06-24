<?php

namespace App\Core;

class App
{
    private static array $config = [];

    public static function init(): void
    {
        self::$config['app'] = require dirname(__DIR__, 2) . '/config/app.php';
        self::$config['database'] = require dirname(__DIR__, 2) . '/config/database.php';

        date_default_timezone_set(self::$config['app']['timezone']);
        error_reporting(self::$config['app']['debug'] ? E_ALL : 0);
        ini_set('display_errors', self::$config['app']['debug'] ? '1' : '0');

        if (session_status() === PHP_SESSION_NONE) {
            $cookiePath = self::sessionCookiePath();
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => $cookiePath,
                'httponly' => true,
                'samesite' => 'Lax',
                'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            ]);
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true,
            ]);
        }

        Session::checkTimeout();
    }

    public static function sessionCookiePath(): string
    {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $runtimePath = ($scriptDir === '/' || $scriptDir === '' || $scriptDir === '.')
            ? '/'
            : rtrim($scriptDir, '/');

        $appUrl = $_ENV['APP_URL'] ?? getenv('APP_URL') ?: '';
        if ($appUrl !== '' && preg_match('#https?://[^/]+(/.*)?$#', $appUrl, $matches)) {
            $configPath = $matches[1] ?? '/';
            $configPath = ($configPath === '' || $configPath === '/') ? '/' : rtrim($configPath, '/');

            // Document root is public/ but APP_URL still ends with /public — cookie path must be /
            if ($runtimePath === '/' && preg_match('#/public$#', $configPath)) {
                return '/';
            }

            // Runtime path matches how the site is actually accessed
            if ($runtimePath !== '/' && str_ends_with($configPath, $runtimePath)) {
                return $runtimePath;
            }

            return $configPath;
        }

        if (str_ends_with($runtimePath, '/public')) {
            return dirname($runtimePath) ?: '/';
        }

        return $runtimePath;
    }

    public static function config(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public static function basePath(): string
    {
        return dirname(__DIR__, 2);
    }

    public static function publicPath(): string
    {
        return self::basePath() . '/public';
    }

    public static function baseUrl(): string
    {
        return rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    }

    public static function routeUrl(string $path = ''): string
    {
        $publicBase = self::baseUrl();
        $appBase = dirname($publicBase);
        $base = ($appBase !== '/' && $appBase !== '.') ? $appBase : $publicBase;

        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
}
