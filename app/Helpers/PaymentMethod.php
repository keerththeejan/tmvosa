<?php

namespace App\Helpers;

class PaymentMethod
{
    public const DEFAULT = 'bank_transfer';

    /** @var list<string> */
    public const ALLOWED = ['bank_transfer', 'cash'];

    public static function isAllowed(string $value): bool
    {
        return in_array(strtolower(trim($value)), self::ALLOWED, true);
    }

    /**
     * @return string|null Normalized value when allowed, null otherwise
     */
    public static function normalize(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $value = strtolower(trim($value));

        return self::isAllowed($value) ? $value : null;
    }

    /**
     * @return list<array{value: string, key: string}>
     */
    public static function options(): array
    {
        return [
            ['value' => 'bank_transfer', 'key' => 'bank_transfer'],
            ['value' => 'cash', 'key' => 'cash'],
        ];
    }

    /**
     * Bilingual label for display (supports legacy stored values).
     */
    public static function display(?string $value): string
    {
        if ($value === null || trim($value) === '') {
            return '-';
        }

        $key = strtolower(trim($value));
        $labels = Lang::ui($key);

        if (is_array($labels) && isset($labels['ta'], $labels['en'])) {
            return $labels['ta'] . ' / ' . $labels['en'];
        }

        return ucwords(str_replace('_', ' ', $key));
    }
}
