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
            $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax',
                'secure' => $isSecure,
            ]);
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'cookie_path' => '/',
                'use_strict_mode' => true,
            ]);
        }

        Session::checkTimeout();
    }

    public static function sessionCookiePath(): string
    {
        return '/';
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
