<?php

namespace App\Models;

use App\Core\Database;

class MembershipCard
{
    public static function findByMemberId(int $memberId): ?array
    {
        return Database::fetch(
            "SELECT * FROM membership_cards WHERE member_id = ? AND is_active = 1 ORDER BY issued_at DESC LIMIT 1",
            [$memberId]
        );
    }

    public static function create(array $data): int
    {
        return Database::insert('membership_cards', $data);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('membership_cards', $data, 'id = ?', [$id]);
    }
}
