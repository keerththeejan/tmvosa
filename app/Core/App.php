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

        $debug = self::$config['app']['debug'];
        if ($debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
            ini_set('display_errors', '0');
            ini_set('log_errors', '1');
            $logDir = self::basePath() . '/storage/logs';
            if (is_dir($logDir) && is_writable($logDir)) {
                ini_set('error_log', $logDir . '/php-errors.log');
            }
            set_exception_handler([self::class, 'handleException']);
            set_error_handler([self::class, 'handleError']);
        }

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

    public static function handleException(\Throwable $e): void
    {
        error_log('Uncaught exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: text/html; charset=UTF-8');
        }
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Error</title></head><body>'
            . '<p>Something went wrong. Please try again later.</p></body></html>';
        exit(1);
    }

    public static function handleError(int $severity, string $message, string $file, int $line): bool
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        error_log("PHP error [{$severity}]: {$message} in {$file}:{$line}");
        return true;
    }

    public static function assetVersion(string $relativePath): string
    {
        $full = self::publicPath() . '/' . ltrim($relativePath, '/');
        $v = is_file($full) ? (string) filemtime($full) : '1';
        return ltrim($relativePath, '/') . '?v=' . $v;
    }
}
