<?php

namespace App\Helpers;

use App\Core\Database;
use App\Models\Setting;

class ApplicationValidation
{
    public static function normalizeNic(string $nic): string
    {
        return strtoupper(preg_replace('/\s+/', '', trim($nic)));
    }

    public static function normalizeMobile(string $mobile): string
    {
        $digits = preg_replace('/\D/', '', $mobile);
        if (str_starts_with($digits, '94') && strlen($digits) >= 11) {
            $digits = substr($digits, 2);
        }

        return ltrim($digits, '0');
    }

    public static function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    public static function mobileDigitVariants(string $mobile): array
    {
        $digits = self::normalizeMobile($mobile);
        if ($digits === '') {
            return [];
        }

        return array_values(array_unique([
            $digits,
            '0' . $digits,
            '94' . $digits,
        ]));
    }

    public static function checkField(string $field, string $value): array
    {
        $value = trim($value);
        if ($value === '') {
            return [
                'status' => 'empty',
                'available' => true,
                'block' => false,
            ];
        }

        return match ($field) {
            'nic_number' => self::checkNic($value),
            'mobile' => self::checkMobile($value),
            'email' => self::checkEmail($value),
            default => ['status' => 'invalid_field', 'available' => true, 'block' => false],
        };
    }

    public static function checkNic(string $nic): array
    {
        $normalized = self::normalizeNic($nic);
        if ($normalized === '') {
            return ['status' => 'empty', 'available' => true, 'block' => false];
        }

        $exists = self::nicExists($normalized);

        return [
            'status' => $exists ? 'duplicate' : 'available',
            'available' => !$exists,
            'block' => $exists,
            'normalized' => $normalized,
            'message_ta' => $exists
                ? 'இந்த தேசிய அடையாள அட்டை இலக்கத்துடன் ஏற்கனவே ஒரு விண்ணப்பம் பதிவு செய்யப்பட்டுள்ளது.'
                : 'தேசிய அடையாள அட்டை இலக்கம் பயன்படுத்தக் கிடைக்கிறது.',
            'message_en' => $exists
                ? 'An application already exists with this NIC Number.'
                : 'NIC Number is available.',
        ];
    }

    public static function checkMobile(string $mobile): array
    {
        $normalized = self::normalizeMobile($mobile);
        if ($normalized === '') {
            return ['status' => 'empty', 'available' => true, 'block' => false];
        }

        $exists = self::mobileExists($normalized);
        $block = $exists && Setting::get('block_duplicate_mobile', '0') === '1';

        return [
            'status' => $exists ? 'duplicate' : 'available',
            'available' => !$exists,
            'block' => $block,
            'warning' => $exists && !$block,
            'normalized' => $normalized,
            'message_ta' => $exists
                ? 'இந்த கைத்தொலைபேசி இலக்கம் ஏற்கனவே பதிவு செய்யப்பட்டுள்ளது.'
                : 'கைத்தொலைபேசி இலக்கம் பயன்படுத்தக் கிடைக்கிறது.',
            'message_en' => $exists
                ? 'This mobile number is already registered.'
                : 'Mobile number is available.',
        ];
    }

    public static function checkEmail(string $email): array
    {
        $normalized = self::normalizeEmail($email);
        if ($normalized === '') {
            return ['status' => 'empty', 'available' => true, 'block' => false];
        }

        if (!filter_var($normalized, FILTER_VALIDATE_EMAIL)) {
            return [
                'status' => 'invalid',
                'available' => false,
                'block' => false,
                'message_ta' => 'சரியான மின்னஞ்சல் முகவரியை உள்ளிடவும்.',
                'message_en' => 'Please enter a valid email address.',
            ];
        }

        $exists = self::emailExists($normalized);
        $block = $exists && Setting::get('block_duplicate_email', '0') === '1';

        return [
            'status' => $exists ? 'duplicate' : 'available',
            'available' => !$exists,
            'block' => $block,
            'warning' => $exists && !$block,
            'normalized' => $normalized,
            'message_ta' => $exists
                ? 'இந்த மின்னஞ்சல் முகவரி ஏற்கனவே பயன்படுத்தப்பட்டுள்ளது.'
                : 'மின்னஞ்சல் முகவரி பயன்படுத்தக் கிடைக்கிறது.',
            'message_en' => $exists
                ? 'This email address is already in use.'
                : 'Email address is available.',
        ];
    }

    public static function nicExists(string $normalizedNic, ?int $excludeApplicationId = null): bool
    {
        $member = Database::fetch(
            "SELECT id FROM members
             WHERE nic_number IS NOT NULL AND nic_number != ''
               AND UPPER(REPLACE(nic_number, ' ', '')) = ?
             LIMIT 1",
            [$normalizedNic]
        );
        if ($member) {
            return true;
        }

        $sql = "SELECT id FROM member_applications
                WHERE nic_number IS NOT NULL AND nic_number != ''
                  AND UPPER(REPLACE(nic_number, ' ', '')) = ?
                  AND status NOT IN ('rejected')";
        $params = [$normalizedNic];
        if ($excludeApplicationId) {
            $sql .= ' AND id != ?';
            $params[] = $excludeApplicationId;
        }

        return (bool) Database::fetch($sql . ' LIMIT 1', $params);
    }

    public static function mobileExists(string $normalized): bool
    {
        return self::matchNormalizedMobile('members', $normalized)
            || self::matchNormalizedMobile('member_applications', $normalized, true);
    }

    public static function emailExists(string $normalized): bool
    {
        $member = Database::fetch(
            "SELECT id FROM members WHERE email IS NOT NULL AND email != '' AND LOWER(TRIM(email)) = ? LIMIT 1",
            [$normalized]
        );
        if ($member) {
            return true;
        }

        return (bool) Database::fetch(
            "SELECT id FROM member_applications
             WHERE email IS NOT NULL AND email != ''
               AND LOWER(TRIM(email)) = ?
               AND status NOT IN ('rejected')
             LIMIT 1",
            [$normalized]
        );
    }

    public static function findDuplicatesForNic(string $nic): array
    {
        $normalized = self::normalizeNic($nic);
        if ($normalized === '') {
            return ['members' => [], 'applications' => []];
        }

        $members = Database::fetchAll(
            "SELECT id, membership_number, full_name_english, mobile, status
             FROM members
             WHERE nic_number IS NOT NULL AND nic_number != ''
               AND UPPER(REPLACE(nic_number, ' ', '')) = ?",
            [$normalized]
        );

        $applications = Database::fetchAll(
            "SELECT id, application_number, full_name_english, mobile, status, created_at
             FROM member_applications
             WHERE nic_number IS NOT NULL AND nic_number != ''
               AND UPPER(REPLACE(nic_number, ' ', '')) = ?
             ORDER BY created_at DESC",
            [$normalized]
        );

        return ['members' => $members, 'applications' => $applications];
    }

    public static function getDuplicateNicSummary(): array
    {
        return Database::fetchAll(
            "SELECT nic_key, COUNT(*) AS record_count
             FROM (
                 SELECT UPPER(REPLACE(nic_number, ' ', '')) AS nic_key
                 FROM members
                 WHERE nic_number IS NOT NULL AND nic_number != ''
                 UNION ALL
                 SELECT UPPER(REPLACE(nic_number, ' ', '')) AS nic_key
                 FROM member_applications
                 WHERE nic_number IS NOT NULL AND nic_number != ''
                   AND status NOT IN ('rejected')
             ) combined
             GROUP BY nic_key
             HAVING COUNT(*) > 1
             ORDER BY record_count DESC, nic_key ASC"
        );
    }

    private static function matchNormalizedMobile(string $table, string $normalized, bool $excludeRejected = false): bool
    {
        $variants = self::mobileDigitVariants($normalized);
        if (!$variants) {
            return false;
        }

        $placeholders = implode(',', array_fill(0, count($variants), '?'));
        $sql = "SELECT id FROM {$table}
                WHERE mobile IS NOT NULL AND mobile != ''
                  AND REGEXP_REPLACE(mobile, '[^0-9]', '') IN ({$placeholders})";
        if ($excludeRejected) {
            $sql .= " AND status NOT IN ('rejected')";
        }

        return (bool) Database::fetch($sql . ' LIMIT 1', $variants);
    }
}
