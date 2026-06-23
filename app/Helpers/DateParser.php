<?php

namespace App\Helpers;

class DateParser
{
    public static function parseDob(string $input, int $minYear = 1940, ?int $maxYear = null): ?string
    {
        $maxYear ??= (int) date('Y');
        $input = trim($input);
        if ($input === '') {
            return null;
        }

        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $input, $m)) {
            return self::formatIfValid((int) $m[1], (int) $m[2], (int) $m[3], $minYear, $maxYear);
        }

        if (preg_match('/^(\d{1,2})[\/\-\s]+(\d{1,2})[\/\-\s]+(\d{4})$/', $input, $m)) {
            return self::formatIfValid((int) $m[3], (int) $m[2], (int) $m[1], $minYear, $maxYear);
        }

        $digits = preg_replace('/\D/', '', $input);
        if (strlen($digits) === 8) {
            return self::formatIfValid(
                (int) substr($digits, 4, 4),
                (int) substr($digits, 2, 2),
                (int) substr($digits, 0, 2),
                $minYear,
                $maxYear
            );
        }

        return null;
    }

    private static function formatIfValid(int $year, int $month, int $day, int $minYear, int $maxYear): ?string
    {
        if ($year < $minYear || $year > $maxYear) {
            return null;
        }
        if (!checkdate($month, $day, $year)) {
            return null;
        }

        return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }
}
