<?php

namespace App\Constants;

class JabatanPelapor
{
    public const KEPALA_LAB = 'Kepala Lab';
    public const SEKRETARIS_LAB = 'Sekretaris Lab';
    public const KOORDINATOR_JARINGAN = 'Koordinator Jaringan';
    public const KOORDINATOR_SOFTWARE_HARDWARE = 'Koordinator Software Hardware';
    public const ADMIN_LAB = 'Admin Lab';
    public const LABORAN_JARINGAN = 'Laboran Jaringan';
    public const LABORAN_TETAP = 'Laboran Tetap';
    public const LABORAN_KONTRAK = 'Laboran Kontrak';
    public const LABORAN_MAGANG = 'Laboran Magang';

    public const ALL = [
        self::KEPALA_LAB,
        self::SEKRETARIS_LAB,
        self::KOORDINATOR_JARINGAN,
        self::KOORDINATOR_SOFTWARE_HARDWARE,
        self::ADMIN_LAB,
        self::LABORAN_JARINGAN,
        self::LABORAN_TETAP,
        self::LABORAN_KONTRAK,
        self::LABORAN_MAGANG,
    ];

    public static function list(): array
    {
        return self::ALL;
    }
}
