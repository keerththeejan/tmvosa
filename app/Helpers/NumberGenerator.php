<?php

namespace App\Helpers;

use App\Core\Database;

class NumberGenerator
{
    public static function membershipNumber(): string
    {
        return self::next('membership', 'OSA');
    }

    public static function receiptNumber(): string
    {
        return self::next('receipt', 'REC');
    }

    public static function applicationNumber(): string
    {
        return self::next('application', 'APP');
    }

    private static function next(string $type, string $defaultPrefix): string
    {
        $year = (int) date('Y');
        $pdo = Database::getInstance();
        $ownsTransaction = !$pdo->inTransaction();

        if ($ownsTransaction) {
            Database::beginTransaction();
        }

        try {
            $seq = Database::fetch(
                "SELECT * FROM number_sequences WHERE sequence_type = ? AND year = ? FOR UPDATE",
                [$type, $year]
            );

            if (!$seq) {
                Database::insert('number_sequences', [
                    'sequence_type' => $type,
                    'year' => $year,
                    'last_number' => 1,
                    'prefix' => $defaultPrefix,
                ]);
                $number = 1;
                $prefix = $defaultPrefix;
            } else {
                $number = $seq['last_number'] + 1;
                $prefix = $seq['prefix'];
                Database::update('number_sequences', ['last_number' => $number], 'id = ?', [$seq['id']]);
            }

            if ($ownsTransaction) {
                Database::commit();
            }

            return sprintf('%s-%d-%04d', $prefix, $year, $number);
        } catch (\Exception $e) {
            if ($ownsTransaction && $pdo->inTransaction()) {
                Database::rollback();
            }
            throw $e;
        }
    }
}
