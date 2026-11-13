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
        Schema::table('laporan_item_penanganans', function (Blueprint $table) {
            $table->string('jenis_tindakan', 100)->nullable()->after('tahap');
            $table->string('aktor_peran', 100)->nullable()->after('jenis_tindakan');
            $table->string('status_sebelum', 100)->nullable()->after('status_item_saat_itu');
        });

        Schema::table('laporan_items', function (Blueprint $table) {
            $table->string('jenis_tindak_lanjut', 50)->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_item_penanganans', function (Blueprint $table) {
            $table->dropColumn(['jenis_tindakan', 'aktor_peran', 'status_sebelum']);
        });
    }
};
