<?php

namespace App\Helpers;

class MemberEmail
{
    /**
     * @return array{value?: ?string, error?: string}
     */
    public static function parse(string $raw, bool $required = true): array
    {
        $email = strtolower(trim($raw));

        if ($email === '') {
            if ($required) {
                return ['error' => 'Email address is required.'];
            }

            return ['value' => null];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['error' => 'Please enter a valid email address.'];
        }

        return ['value' => $email];
    }
}
