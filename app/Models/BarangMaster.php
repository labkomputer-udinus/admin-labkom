<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BarangMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'spesifikasi',
        'kategori',
        'satuan',
        'stok_minimum',
    ];

    /**
     * Dapatkan format nama lengkap barang (Nama Barang - Spesifikasi).
     */
    public function getNamaLengkapAttribute(): string
    {
        return !empty($this->spesifikasi)
            ? "{$this->nama_barang} - {$this->spesifikasi}"
            : $this->nama_barang;
    }

    public function stokGudang(): HasOne
    {
        return $this->hasOne(StokGudang::class);
    }
}
