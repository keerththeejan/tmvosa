<?php

namespace App\Models;

use App\Core\Database;

class Setting
{
    public static function get(string $key, mixed $default = null): mixed
    {
        $row = Database::fetch("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
        return $row ? $row['setting_value'] : $default;
    }

    public static function set(string $key, string $value): void
    {
        $exists = Database::fetch("SELECT id FROM settings WHERE setting_key = ?", [$key]);
        if ($exists) {
            Database::update('settings', ['setting_value' => $value], 'setting_key = ?', [$key]);
        } else {
            Database::insert('settings', ['setting_key' => $key, 'setting_value' => $value]);
        }
    }

    public static function getAll(): array
    {
        $rows = Database::fetchAll("SELECT * FROM settings ORDER BY setting_group, setting_key");
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['setting_group']][] = $row;
        }
        return $grouped;
    }
}
