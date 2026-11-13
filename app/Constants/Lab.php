<?php

namespace App\Constants;

class Lab
{
    public const ALL = [
        'D2A',
        'D2B',
        'D2C',
        'D2D',
        'D2E',
        'D2F',
        'D2G',
        'D2H',
        'D2I',
        'D2J',
        'D2K',
        'D3L',
        'D3M',
        'D3N',
    ];

    public static function list(): array
    {
        return self::ALL;
    }

    public static function isValid(string $lab): bool
    {
        return in_array(strtoupper($lab), self::ALL, true);
    }
}
