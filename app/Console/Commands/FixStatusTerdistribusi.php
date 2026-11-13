<?php

namespace App\Console\Commands;

use App\Models\Laporan;
use Illuminate\Console\Command;

class FixStatusTerdistribusi extends Command
{
    protected $signature = 'laporan:fix-status-terdistribusi';
    protected $description = 'Revert laporan yang salah berstatus selesai padahal ada item masih terdistribusi';

    public function handle(): int
    {
        $laporans = Laporan::with('items')->where('status', 'selesai')->get();
        $fixed = 0;

        foreach ($laporans as $laporan) {
            $hasTerdistribusi = $laporan->items->contains(
                fn($i) => $i->status_item === 'terdistribusi'
            );

            if ($hasTerdistribusi) {
                $id = 'LAP-' . str_pad($laporan->id, 4, '0', STR_PAD_LEFT);
                $this->line("Fixing {$id} (selesai → revert)...");

                $laporan->updateAutoStatus();
                $laporan->refresh();

                $this->info("  → Status: {$laporan->status}, tanggal_selesai: " . ($laporan->tanggal_selesai ?? 'null'));
                $fixed++;
            }
        }

        if ($fixed === 0) {
            $this->info('Tidak ada laporan yang perlu difix. Semua data sudah konsisten.');
        } else {
            $this->info("\n{$fixed} laporan berhasil diperbarui.");
        }

        return Command::SUCCESS;
    }
}
