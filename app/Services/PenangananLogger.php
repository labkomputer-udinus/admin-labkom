<?php

namespace App\Services;

use App\Constants\JenisTindakan;
use App\Models\LaporanItem;
use App\Models\LaporanItemPenanganan;

class PenangananLogger
{
    /**
     * Catat satu entri riwayat/log penanganan untuk item laporan.
     *
     * @param LaporanItem $item
     * @param string $jenisTindakan
     * @param string $tahap
     * @param string $tindakanAtauHasil
     * @param string|null $aktorPeran
     * @param string|null $statusSebelum
     * @param string|null $statusSesudah
     * @param \DateTimeInterface|string|null $tanggal
     * @param string|null $petugas
     * @return LaporanItemPenanganan
     */
    public static function log(
        LaporanItem $item,
        string $jenisTindakan,
        string $tahap,
        string $tindakanAtauHasil,
        ?string $aktorPeran = null,
        ?string $statusSebelum = null,
        ?string $statusSesudah = null,
        \DateTimeInterface|string|null $tanggal = null,
        ?string $petugas = null
    ): LaporanItemPenanganan {
        if ($tanggal) {
            if (is_string($tanggal)) {
                $trimmed = trim($tanggal);
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $trimmed)) {
                    $waktu = \Carbon\Carbon::parse($trimmed)->setTime(now()->hour, now()->minute, now()->second);
                } else {
                    $waktu = \Carbon\Carbon::parse($trimmed);
                }
            } else {
                $waktu = $tanggal;
            }
        } else {
            $waktu = now();
        }

        return LaporanItemPenanganan::create([
            'laporan_item_id'      => $item->id,
            'tahap'                => $tahap,
            'jenis_tindakan'       => $jenisTindakan,
            'aktor_peran'          => $aktorPeran ?? (auth()->user()->name ?? 'Admin Lab'),
            'tindakan_atau_hasil'  => $tindakanAtauHasil,
            'petugas'              => $petugas ?? (auth()->user()->name ?? 'Admin Lab'),
            'status_sebelum'       => $statusSebelum,
            'status_item_saat_itu' => $statusSesudah ?? $item->status_item,
            'tanggal_tindakan'     => $waktu,
            'tanggal_waktu'        => $waktu,
        ]);
    }
}
