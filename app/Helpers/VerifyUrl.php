<?php

namespace App\Helpers;

/**
 * Builds absolute public verification URLs for membership QR codes.
 */
class VerifyUrl
{
    public static function forMembershipNumber(string $membershipNumber): string
    {
        $scheme = 'http';
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $scheme = 'https';
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $scheme = strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https' ? 'https' : 'http';
        }

        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
        $number = rawurlencode($membershipNumber);

        return $scheme . '://' . $host . $base . '/verify/' . $number;
    }

    public static function isAbsoluteVerifyUrl(string $data): bool
    {
        return (bool) preg_match('#^https?://[^\\s]+/verify/[^\\s]+$#i', trim($data));
    }
}
