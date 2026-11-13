<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_pengajuan',
        'divisi_tujuan',
        'tanggal_ajuan',
        'status',
        'tanggal_keputusan',
        'catatan_keputusan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_ajuan' => 'date',
            'tanggal_keputusan' => 'date',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PengajuanItem::class);
    }

    public static function generateNomorPengajuan(): string
    {
        $year = date('Y');
        $month = date('m');
        $prefix = "PGD/{$year}/{$month}/";

        $lastPengajuan = self::where('nomor_pengajuan', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPengajuan) {
            $lastNumber = (int) substr($lastPengajuan->nomor_pengajuan, strrpos($lastPengajuan->nomor_pengajuan, '/') + 1);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }
}
