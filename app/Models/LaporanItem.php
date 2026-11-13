<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaporanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'nama_barang',
        'deskripsi',
        'jumlah',
        'satuan',
        'jenis_tindak_lanjut',
        'keterangan',
        'status_item',
    ];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class);
    }

    public function pengajuanItems(): HasMany
    {
        return $this->hasMany(PengajuanItem::class);
    }

    public function distribusis(): HasMany
    {
        return $this->hasMany(Distribusi::class);
    }

    public function penanganans(): HasMany
    {
        return $this->hasMany(LaporanItemPenanganan::class)->orderBy('id', 'asc');
    }
}
