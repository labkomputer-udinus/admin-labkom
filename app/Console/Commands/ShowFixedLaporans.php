<?php

namespace App\Console\Commands;

use App\Models\Laporan;
use Illuminate\Console\Command;

class ShowFixedLaporans extends Command
{
    protected $signature = 'laporan:show-fixed';
    protected $description = 'Tampilkan detail 5 laporan yang baru saja difix statusnya';

    public function handle(): int
    {
        $laporans = Laporan::with('items')->whereIn('id', [2, 7, 15, 25, 30])->get();

        foreach ($laporans as $l) {
            $id = 'LAP-' . str_pad($l->id, 4, '0', STR_PAD_LEFT);
            $this->line('');
            $this->info("┌─ {$id} │ Lab: {$l->lab_nama} │ Status: {$l->status}");
            $this->line("│  Pelapor  : {$l->nama_pelapor} ({$l->jabatan_pelapor})");
            $this->line("│  Tgl Masuk: {$l->tanggal_laporan}");
            $this->line('│  Items:');
            foreach ($l->items as $i) {
                $badge = match($i->status_item) {
                    'terdistribusi'     => '[MENUNGGU KONFIRMASI]',
                    'selesai'           => '[SELESAI]',
                    'selesai_perbaikan' => '[SELESAI PERBAIKAN]',
                    default             => "[{$i->status_item}]",
                };
                $this->line("│    {$badge} {$i->nama_barang} x{$i->jumlah} {$i->satuan}");
            }
            $this->line('└────────────────────────────────────────');
        }

        return Command::SUCCESS;
    }
}
