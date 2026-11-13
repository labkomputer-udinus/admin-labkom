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
        Schema::table('laporans', function (Blueprint $table) {
            $table->string('bentuk_ketidaksesuaian')->nullable()->after('lab_nama');
            $table->date('tanggal_kejadian')->nullable()->after('bentuk_ketidaksesuaian');
            $table->string('bagian')->nullable()->after('nama_pelapor');
            $table->string('jabatan_pelapor')->nullable()->after('bagian');
            $table->text('uraian_pengamatan')->nullable()->after('catatan_umum');
            $table->text('tindakan_langsung')->nullable()->after('uraian_pengamatan');
            $table->text('permintaan_tindakan')->nullable()->after('tindakan_langsung');
            $table->enum('status_akhir', ['closed', 'follow_up'])->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn([
                'bentuk_ketidaksesuaian',
                'tanggal_kejadian',
                'bagian',
                'jabatan_pelapor',
                'uraian_pengamatan',
                'tindakan_langsung',
                'permintaan_tindakan',
                'status_akhir',
            ]);
        });
    }
};
