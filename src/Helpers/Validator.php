<?php

declare(strict_types=1);

namespace App\Helpers;

final class Validator
{
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function notEmptyString(string $value): bool
    {
        return trim($value) !== '';
    }

    public static function minLength(string $value, int $min): bool
    {
        return mb_strlen($value) >= $min;
    }

    public static function maxLength(string $value, int $max): bool
    {
        return mb_strlen($value) <= $max;
    }
}