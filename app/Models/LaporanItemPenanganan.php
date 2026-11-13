<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanItemPenanganan extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_item_id',
        'tahap',
        'jenis_tindakan',
        'aktor_peran',
        'tindakan_atau_hasil',
        'petugas',
        'status_sebelum',
        'status_item_saat_itu',
        'tanggal_tindakan',
        'tanggal_hasil',
        'tanggal_waktu',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_tindakan' => 'datetime',
            'tanggal_hasil'    => 'datetime',
            'tanggal_waktu'    => 'datetime',
        ];
    }

    public function laporanItem(): BelongsTo
    {
        return $this->belongsTo(LaporanItem::class);
    }
}
