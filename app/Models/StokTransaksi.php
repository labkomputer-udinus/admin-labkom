<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokTransaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'stok_gudang_id',
        'jenis',
        'jumlah',
        'referensi_type',
        'referensi_id',
        'keterangan',
        'tanggal_transaksi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_transaksi' => 'date',
        ];
    }

    public function stokGudang(): BelongsTo
    {
        return $this->belongsTo(StokGudang::class);
    }
}
