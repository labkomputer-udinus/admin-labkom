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
        Schema::create('laporan_item_penanganans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_item_id')->constrained('laporan_items')->cascadeOnDelete();
            $table->string('tahap');
            $table->text('tindakan_atau_saran');
            $table->text('hasil')->nullable();
            $table->string('petugas')->nullable();
            $table->string('status_item_saat_itu')->nullable();
            $table->timestamp('tanggal_waktu')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_item_penanganans');
    }
};
