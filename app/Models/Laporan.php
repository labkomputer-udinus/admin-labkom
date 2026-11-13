<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_nama',
        'bentuk_ketidaksesuaian',
        'tanggal_kejadian',
        'nama_pelapor',
        'bagian',
        'jabatan_pelapor',
        'tanggal_laporan',
        'tanggal_selesai',
        'catatan_umum',
        'uraian_pengamatan',
        'tindakan_langsung',
        'permintaan_tindakan',
        'status',
        'status_akhir',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kejadian' => 'date',
            'tanggal_laporan' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(LaporanItem::class);
    }

    public function updateAutoStatus(): void
    {
        $items = $this->items;
        if ($items->isEmpty()) {
            return;
        }

        // Laporan selesai hanya jika SEMUA item sudah dikonfirmasi 'selesai' oleh laboran.
        // 'terdistribusi' saja belum cukup — laboran masih perlu verifikasi barang berfungsi baik.
        $allSelesai = $items->every(fn($item) => in_array($item->status_item, ['selesai', 'selesai_perbaikan']));
        if ($allSelesai) {
            $this->status = 'selesai';
            if (!$this->tanggal_selesai) {
                $this->tanggal_selesai = now()->toDateString();
            }
            $this->save();
            return;
        }

        // Jika laporan sebelumnya sudah 'selesai' tapi ternyata ada item yang belum benar-benar selesai,
        // revert status laporan ke tahap yang sesuai dengan kondisi item saat ini.
        if ($this->status === 'selesai') {
            $this->tanggal_selesai = null;

            $hasDistributed = $items->contains(fn($i) => $i->status_item === 'terdistribusi');
            $hasPengadaan   = $items->contains(fn($i) => in_array($i->status_item, ['menunggu_pengadaan']));
            $hasProses      = $items->contains(fn($i) => in_array($i->status_item, ['proses_perbaikan', 'selesai_perbaikan']));
            $hasTriase      = $items->contains(fn($i) => $i->jenis_tindak_lanjut !== 'pending');

            if ($hasDistributed) {
                $this->status = 'siap_distribusi'; // barang sudah di lab, menunggu konfirmasi
            } elseif ($hasPengadaan) {
                $this->status = 'barang_diterima';
            } elseif ($hasProses) {
                $this->status = 'dalam_proses';
            } elseif ($hasTriase) {
                $this->status = 'ditriase';
            } else {
                $this->status = 'dicatat';
            }

            $this->save();
            return;
        }

        // Naikan status ke ditriase jika belum
        $hasTriase = $items->contains(fn($item) => $item->jenis_tindak_lanjut !== 'pending');
        if ($hasTriase && in_array($this->status, ['dicatat'])) {
            $this->status = 'ditriase';
            $this->save();
        }
    }
}

