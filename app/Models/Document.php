<?php

namespace App\Models;

use App\Core\Database;

class Document
{
    public static function getByMember(int $memberId): array
    {
        return Database::fetchAll(
            "SELECT * FROM member_documents WHERE member_id = ? ORDER BY created_at DESC",
            [$memberId]
        );
    }

    public static function getByApplication(int $applicationId): array
    {
        return Database::fetchAll(
            "SELECT * FROM member_documents WHERE application_id = ? ORDER BY created_at DESC",
            [$applicationId]
        );
    }

    public static function create(array $data): int
    {
        return Database::insert('member_documents', $data);
    }
}
