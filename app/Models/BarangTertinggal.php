<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTertinggal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_ditemukan',
        'lab_nama',
        'penemu',
        'nama_barang',
        'keterangan',
        'pemilik',
        'no_hp',
        'tanggal_pengambilan',
        'penyerah',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_ditemukan' => 'date',
            'tanggal_pengambilan' => 'date',
        ];
    }

    public function scopeBelumDiambil(Builder $query): Builder
    {
        return $query->where('status', 'belum_diambil');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->whereIn('status', ['belum_diambil', 'diumumkan']);
    }
}
