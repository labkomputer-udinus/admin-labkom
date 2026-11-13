<?php

namespace App\Constants;

class JenisTindakan
{
    public const LAPORAN_DICATAT         = 'laporan_dicatat';
    public const TINDAKAN_LANGSUNG       = 'tindakan_langsung';
    public const DITERUSKAN_KOORDINATOR  = 'diteruskan_koordinator';
    public const KEPUTUSAN_KOORDINATOR   = 'keputusan_koordinator';
    public const INSTRUKSI_LABORAN       = 'instruksi_laboran';
    public const HASIL_TINDAK_ULANG      = 'hasil_tindak_ulang';
    public const DITUGASKAN_TEKNISI      = 'ditugaskan_teknisi';
    public const HASIL_PERBAIKAN         = 'hasil_perbaikan';
    public const CEK_STOK                = 'cek_stok';
    public const PENGAJUAN_BIUM          = 'pengajuan_bium';
    public const KEPUTUSAN_BIUM          = 'keputusan_bium';
    public const VERIFIKASI_PENERIMAAN   = 'verifikasi_penerimaan';
    public const DISTRIBUSI_BARANG       = 'distribusi_barang';
    public const DISTRIBUSI_MANDIRI      = 'distribusi_mandiri';
    public const KONFIRMASI_LABORAN      = 'konfirmasi_laboran';
    public const LAPORAN_DITUTUP         = 'laporan_ditutup';
    public const LAINNYA                 = 'lainnya';

    public static function labels(): array
    {
        return [
            self::LAPORAN_DICATAT        => 'Laporan Dicatat',
            self::TINDAKAN_LANGSUNG      => 'Tindakan Langsung (Laboran)',
            self::DITERUSKAN_KOORDINATOR => 'Diteruskan ke Koordinator',
            self::KEPUTUSAN_KOORDINATOR  => 'Keputusan Koordinator',
            self::INSTRUKSI_LABORAN      => 'Instruksi ke Laboran',
            self::HASIL_TINDAK_ULANG     => 'Hasil Tindak Ulang Laboran',
            self::DITUGASKAN_TEKNISI     => 'Ditugaskan ke Teknisi',
            self::HASIL_PERBAIKAN        => 'Hasil Perbaikan Teknisi',
            self::CEK_STOK               => 'Cek Stok Gudang',
            self::PENGAJUAN_BIUM         => 'Pengajuan ke BIUM',
            self::KEPUTUSAN_BIUM         => 'Keputusan BIUM',
            self::VERIFIKASI_PENERIMAAN  => 'Verifikasi Penerimaan Barang',
            self::DISTRIBUSI_BARANG      => 'Distribusi Barang ke Lab',
            self::DISTRIBUSI_MANDIRI     => 'Distribusi Mandiri (Tanpa Laporan)',
            self::KONFIRMASI_LABORAN     => 'Konfirmasi ke Laboran',
            self::LAPORAN_DITUTUP        => 'Laporan Ditutup (Closed)',
            self::LAINNYA                => 'Lainnya / Catatan Khusus',
        ];
    }

    public static function manualInputLabels(): array
    {
        return [
            self::DITUGASKAN_TEKNISI => 'Ditugaskan ke Teknisi',
            self::HASIL_PERBAIKAN    => 'Hasil Perbaikan / Troubleshooting',
            self::INSTRUKSI_LABORAN  => 'Instruksi Tindak Ulang ke Laboran',
            self::HASIL_TINDAK_ULANG => 'Hasil Tindak Ulang Laboran',
            self::CEK_STOK           => 'Pemeriksaan Stok Gudang',
            self::DISTRIBUSI_BARANG  => 'Distribusi ke Lab',
            self::LAPORAN_DITUTUP    => 'Penyelesaian / Penutupan Masalah',
            self::LAINNYA            => 'Lainnya / Catatan Khusus',
        ];
    }

    public static function label(?string $key): string
    {
        if (!$key) {
            return '-';
        }
        return self::labels()[$key] ?? ucwords(str_replace('_', ' ', $key));
    }
}
