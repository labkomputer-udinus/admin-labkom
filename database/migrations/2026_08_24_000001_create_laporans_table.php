<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->string('lab_nama', 10); // e.g. D2A, D3N
            $table->string('nama_pelapor')->nullable();
            $table->date('tanggal_laporan');
            $table->date('tanggal_selesai')->nullable();
            $table->text('catatan_umum')->nullable();
            $table->enum('status', [
                'dicatat',
                'ditriase',
                'dalam_proses',
                'menunggu_keputusan_divisi',
                'barang_diterima',
                'siap_distribusi',
                'selesai',
                'ditolak',
                'perlu_revisi'
            ])->default('dicatat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
