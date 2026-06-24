<?php

namespace App\Models;

use App\Core\Database;

class EmailTemplate
{
    public static function getAll(): array
    {
        return Database::fetchAll("SELECT * FROM email_templates ORDER BY name ASC");
    }

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM email_templates WHERE id = ?", [$id]);
    }

    public static function findByName(string $name): ?array
    {
        return Database::fetch("SELECT * FROM email_templates WHERE name = ?", [$name]);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('email_templates', $data, 'id = ?', [$id]);
    }
}
