<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE pengajuan_items MODIFY COLUMN status_kesesuaian ENUM('sesuai', 'sesuai_beda_spesifikasi', 'tidak_sesuai') NULL");
        } else {
            Schema::table('pengajuan_items', function (Blueprint $table) {
                $table->string('status_kesesuaian')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE pengajuan_items MODIFY COLUMN status_kesesuaian ENUM('sesuai', 'tidak_sesuai') NULL");
        } else {
            Schema::table('pengajuan_items', function (Blueprint $table) {
                $table->string('status_kesesuaian')->nullable()->change();
            });
        }
    }
};
