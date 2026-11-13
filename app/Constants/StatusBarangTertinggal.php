<?php

namespace App\Constants;

class StatusBarangTertinggal
{
    public const BELUM_DIAMBIL = 'belum_diambil';
    public const DIUMUMKAN = 'diumumkan';
    public const SUDAH_DIAMBIL = 'sudah_diambil';
    public const DISERAHKAN_KE_SATPAM = 'diserahkan_ke_satpam';

    public const ALL = [
        self::BELUM_DIAMBIL,
        self::DIUMUMKAN,
        self::SUDAH_DIAMBIL,
        self::DISERAHKAN_KE_SATPAM,
    ];

    public static function list(): array
    {
        return self::ALL;
    }

    public static function labels(): array
    {
        return [
            self::BELUM_DIAMBIL => 'Belum Diambil',
            self::DIUMUMKAN => 'Diumumkan',
            self::SUDAH_DIAMBIL => 'Sudah Diambil',
            self::DISERAHKAN_KE_SATPAM => 'Diserahkan ke Satpam',
        ];
    }

    public static function label(string $status): string
    {
        return self::labels()[$status] ?? ucwords(str_replace('_', ' ', $status));
    }
}
