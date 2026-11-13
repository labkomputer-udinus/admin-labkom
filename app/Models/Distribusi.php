<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distribusi extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_item_id',
        'stok_gudang_id',
        'jumlah',
        'asal_barang',
        'pengajuan_item_id',
        'lab_tujuan',
        'kode_pc_tujuan',
        'diterima_oleh',
        'tanggal_distribusi',
        'tanggal_terima_lab',
        'jenis_distribusi',
        'kategori_alasan',
        'alasan_distribusi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_distribusi' => 'datetime',
            'tanggal_terima_lab' => 'date',
        ];
    }

    public function isMandiri(): bool
    {
        return $this->jenis_distribusi === 'mandiri';
    }

    public function isLaporan(): bool
    {
        return $this->jenis_distribusi !== 'mandiri';
    }

    public function getAlasanLengkapAttribute(): string
    {
        if ($this->isMandiri()) {
            $parts = [];
            if (!empty($this->kategori_alasan)) {
                $parts[] = "[{$this->kategori_alasan}]";
            }
            if (!empty($this->alasan_distribusi)) {
                $parts[] = $this->alasan_distribusi;
            }
            return count($parts) ? implode(' ', $parts) : 'Distribusi Mandiri';
        }

        return $this->laporanItem?->deskripsi ?? 'Berdasarkan Laporan';
    }

    public function laporanItem(): BelongsTo
    {
        return $this->belongsTo(LaporanItem::class);
    }

    public function stokGudang(): BelongsTo
    {
        return $this->belongsTo(StokGudang::class);
    }

    public function pengajuanItem(): BelongsTo
    {
        return $this->belongsTo(PengajuanItem::class);
    }
}
