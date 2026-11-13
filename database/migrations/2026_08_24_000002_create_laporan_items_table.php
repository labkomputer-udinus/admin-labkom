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
        Schema::create('laporan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporans')->cascadeOnDelete();
            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->integer('jumlah')->default(1);
            $table->string('satuan', 50)->default('unit');
            $table->enum('jenis_tindak_lanjut', ['pending', 'perbaikan', 'penggantian'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->enum('status_item', [
                'menunggu_triase',
                'proses_perbaikan',
                'selesai_perbaikan',
                'menunggu_pengadaan',
                'terdistribusi',
                'selesai'
            ])->default('menunggu_triase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_items');
    }
};
