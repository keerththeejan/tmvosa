<?php

namespace App\Core;

class Security
{
    private const CSRF_TOKEN_TTL = 14400; // 4 hours — long forms on mobile

    public static function generateCsrf(): string
    {
        $existing = Session::get('csrf_token');
        if (is_string($existing) && self::verifySignedToken($existing)) {
            return $existing;
        }

        $token = self::createSignedToken();
        Session::set('csrf_token', $token);
        return $token;
    }

    public static function validateCsrf(?string $token): bool
    {
        if (!$token || !is_string($token)) {
            return false;
        }

        // Signed token validates even when the PHP session cookie is missing.
        if (self::verifySignedToken($token)) {
            return true;
        }

        // Legacy 64-char hex tokens (older cached pages).
        if (preg_match('/^[a-f0-9]{64}$/i', $token)) {
            $sessionToken = Session::get('csrf_token');
            return is_string($sessionToken) && hash_equals($sessionToken, $token);
        }

        return false;
    }

    private static function createSignedToken(): string
    {
        $time = time();
        $nonce = bin2hex(random_bytes(16));
        $payload = $nonce . '.' . $time;
        $signature = hash_hmac('sha256', $payload, self::csrfSecret());

        return self::base64UrlEncode($payload . '.' . $signature);
    }

    private static function verifySignedToken(string $token): bool
    {
        $raw = self::base64UrlDecode($token);
        if ($raw === null) {
            return false;
        }

        $parts = explode('.', $raw);
        if (count($parts) !== 3) {
            return false;
        }

        [$nonce, $time, $signature] = $parts;
        if (!ctype_xdigit($nonce) || !ctype_digit($time)) {
            return false;
        }

        $age = time() - (int) $time;
        if ($age > self::CSRF_TOKEN_TTL || $age < -120) {
            return false;
        }

        $expected = hash_hmac('sha256', $nonce . '.' . $time, self::csrfSecret());

        return hash_equals($expected, $signature);
    }

    private static function csrfSecret(): string
    {
        static $secret = null;
        if ($secret !== null) {
            return $secret;
        }

        $secret = $_ENV['APP_KEY'] ?? getenv('APP_KEY') ?: '';
        if ($secret === '') {
            $secret = $_ENV['APP_URL'] ?? getenv('APP_URL') ?: 'osa-membership-csrf';
        }

        return $secret;
    }

    private static function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $value): ?string
    {
        $b64 = strtr($value, '-_', '+/');
        $pad = strlen($b64) % 4;
        if ($pad > 0) {
            $b64 .= str_repeat('=', 4 - $pad);
        }

        $decoded = base64_decode($b64, true);

        return $decoded === false ? null : $decoded;
    }

    public static function sanitize(string $input): string
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function getClientIp(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['HTTP_CLIENT_IP']
            ?? $_SERVER['REMOTE_ADDR']
            ?? '0.0.0.0';
    }

    public static function validateUpload(array $file): array
    {
        $errors = [];
        $maxSize = App::config('app.max_upload_size');
        $allowed = App::config('app.allowed_extensions');

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'File upload failed.';
            return $errors;
        }

        if ($file['size'] > $maxSize) {
            $errors[] = 'கோப்பின் அளவு 10MB ஐ விட அதிகமாக இருக்கக்கூடாது. File size must be less than 10MB.';
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = 'File type not allowed. Allowed: ' . implode(', ', $allowed);
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
        if (!in_array($mime, $allowedMimes)) {
            $errors[] = 'Invalid file content.';
        }

        return $errors;
    }

    public static function generateSecureFilename(string $originalName): string
    {
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        return bin2hex(random_bytes(16)) . '.' . $ext;
    }
}
