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
        Schema::create('barang_tertinggals', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_ditemukan');
            $table->string('lab_nama', 100);
            $table->string('penemu');
            $table->string('nama_barang');
            $table->text('keterangan')->nullable();
            $table->string('pemilik')->nullable();
            $table->string('no_hp', 30)->nullable();
            $table->date('tanggal_pengambilan')->nullable();
            $table->string('penyerah')->nullable();
            $table->string('status')->default('belum_diambil');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_tertinggals');
    }
};
