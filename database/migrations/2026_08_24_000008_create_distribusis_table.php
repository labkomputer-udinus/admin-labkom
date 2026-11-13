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
        Schema::create('distribusis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_item_id')->constrained('laporan_items')->cascadeOnDelete();
            $table->foreignId('stok_gudang_id')->constrained('stok_gudangs')->cascadeOnDelete();
            $table->integer('jumlah')->default(1);
            $table->enum('asal_barang', ['pengajuan', 'stok_lama'])->default('stok_lama');
            $table->foreignId('pengajuan_item_id')->nullable()->constrained('pengajuan_items')->nullOnDelete();
            $table->string('lab_tujuan', 10);
            $table->string('kode_pc_tujuan');
            $table->string('diterima_oleh')->nullable();
            $table->date('tanggal_distribusi');
            $table->date('tanggal_terima_lab')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusis');
    }
};
