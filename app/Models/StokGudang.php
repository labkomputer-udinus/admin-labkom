<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StokGudang extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_master_id',
        'stok_saat_ini',
        'catatan',
    ];

    public function barangMaster(): BelongsTo
    {
        return $this->belongsTo(BarangMaster::class);
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(StokTransaksi::class);
    }

    public function distribusis(): HasMany
    {
        return $this->hasMany(Distribusi::class);
    }

    public function isKritis(): bool
    {
        return $this->stok_saat_ini <= ($this->barangMaster?->stok_minimum ?? 0);
    }
}
