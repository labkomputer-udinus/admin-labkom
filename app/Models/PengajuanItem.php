<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'laporan_item_id',
        'nama_barang',
        'spesifikasi',
        'jumlah_diajukan',
        'satuan',
        'jumlah_diterima',
        'spesifikasi_diterima',
        'status_kesesuaian',
        'catatan_selisih',
        'tanggal_terima',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_terima' => 'date',
        ];
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function laporanItem(): BelongsTo
    {
        return $this->belongsTo(LaporanItem::class);
    }

    public function distribusis(): HasMany
    {
        return $this->hasMany(Distribusi::class);
    }
}
