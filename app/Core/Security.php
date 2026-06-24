<?php

namespace App\Core;

class Security
{
    public static function generateCsrf(): string
    {
        if (!Session::has('csrf_token')) {
            Session::set('csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::get('csrf_token');
    }

    public static function validateCsrf(?string $token): bool
    {
        $sessionToken = Session::get('csrf_token');
        return $sessionToken && $token && hash_equals($sessionToken, $token);
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
