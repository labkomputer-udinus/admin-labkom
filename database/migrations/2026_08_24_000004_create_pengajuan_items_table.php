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
        Schema::create('pengajuan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->cascadeOnDelete();
            $table->foreignId('laporan_item_id')->nullable()->constrained('laporan_items')->nullOnDelete();
            $table->string('nama_barang');
            $table->text('spesifikasi')->nullable();
            $table->integer('jumlah_diajukan')->default(1);
            $table->string('satuan', 50)->default('unit');
            $table->integer('jumlah_diterima')->nullable();
            $table->text('spesifikasi_diterima')->nullable();
            $table->enum('status_kesesuaian', ['sesuai', 'tidak_sesuai'])->nullable();
            $table->text('catatan_selisih')->nullable();
            $table->date('tanggal_terima')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_items');
    }
};
