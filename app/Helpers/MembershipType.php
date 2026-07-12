<?php

namespace App\Helpers;

use App\Core\Database;

/**
 * Membership type helpers (duration, ordering, bilingual labels).
 * Half-year uses slug-based 180-day duration without a schema change.
 */
class MembershipType
{
    public const SLUG_HALF_YEAR = 'half_year';
    public const SLUG_ORDINARY = 'ordinary';
    public const SLUG_TEN_YEAR = 'ten_year';

    public const HALF_YEAR_DAYS = 180;

    public static function orderSql(string $alias = 'mt'): string
    {
        $col = $alias !== '' ? "{$alias}.slug" : 'slug';

        return "CASE {$col}
            WHEN 'half_year' THEN 1
            WHEN 'ordinary' THEN 2
            WHEN 'ten_year' THEN 3
            ELSE 99
        END, {$col}";
    }

    public static function allActive(): array
    {
        return Database::fetchAll(
            'SELECT * FROM membership_types WHERE is_active = 1 ORDER BY ' . self::orderSql('')
        );
    }

    public static function findById(int $id): ?array
    {
        return Database::fetch('SELECT * FROM membership_types WHERE id = ?', [$id]);
    }

    public static function durationDays(array $type): int
    {
        $slug = $type['slug'] ?? '';
        if ($slug === self::SLUG_HALF_YEAR) {
            return self::HALF_YEAR_DAYS;
        }

        $years = max(1, (int) ($type['duration_years'] ?? 1));

        return $years * 365;
    }

    public static function calculateExpiryDate(array $type, ?string $fromDate = null): string
    {
        $from = $fromDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)
            ? $fromDate
            : date('Y-m-d');

        $days = self::durationDays($type);

        return date('Y-m-d', strtotime($from . " +{$days} days"));
    }

    public static function extendExpiryDate(array $type, ?string $currentExpiry = null): string
    {
        $base = ($currentExpiry && $currentExpiry > date('Y-m-d'))
            ? $currentExpiry
            : date('Y-m-d');

        return self::calculateExpiryDate($type, $base);
    }

    public static function bilingualLabel(?string $name = null, ?string $slug = null): string
    {
        $resolvedSlug = $slug ?: self::slugFromName((string) $name);
        $display = \App\Helpers\Lang::membershipDisplayFromSlug($resolvedSlug, null, $name);

        return $display['bilingual'];
    }

    public static function slugFromName(string $typeName): string
    {
        $lower = strtolower($typeName);
        if (str_contains($lower, 'half') || str_contains($typeName, 'அரை')) {
            return self::SLUG_HALF_YEAR;
        }
        if (str_contains($lower, '10') || str_contains($lower, 'ten')) {
            return self::SLUG_TEN_YEAR;
        }

        return self::SLUG_ORDINARY;
    }

    public static function optionLabel(array $type): string
    {
        $fee = number_format((float) ($type['fee'] ?? 0), 2);
        $label = self::bilingualLabel($type['name'] ?? '', $type['slug'] ?? null);

        return $label . ' — Rs. ' . $fee;
    }
}
