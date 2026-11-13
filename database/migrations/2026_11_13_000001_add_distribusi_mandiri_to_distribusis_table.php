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
        Schema::table('distribusis', function (Blueprint $table) {
            // Ubah foreign key laporan_item_id menjadi nullable
            $table->unsignedBigInteger('laporan_item_id')->nullable()->change();

            // Ubah kode_pc_tujuan menjadi nullable (karena tidak semua barang lab terikat ke 1 PC spesifik)
            $table->string('kode_pc_tujuan')->nullable()->change();

            // Tambahkan kolom jenis_distribusi (laporan = sesuai alur laporan kerusakan; mandiri = upgrade lab, pemeliharaan, dll)
            $table->enum('jenis_distribusi', ['laporan', 'mandiri'])
                ->default('laporan')
                ->after('asal_barang');

            // Tambahkan kategori alasan untuk distribusi mandiri
            $table->string('kategori_alasan', 100)
                ->nullable()
                ->after('jenis_distribusi');

            // Tambahkan rincian / keterangan alasan distribusi mandiri
            $table->string('alasan_distribusi', 500)
                ->nullable()
                ->after('kategori_alasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribusis', function (Blueprint $table) {
            $table->dropColumn(['alasan_distribusi', 'kategori_alasan', 'jenis_distribusi']);
            $table->string('kode_pc_tujuan')->nullable(false)->change();
            $table->unsignedBigInteger('laporan_item_id')->nullable(false)->change();
        });
    }
};

