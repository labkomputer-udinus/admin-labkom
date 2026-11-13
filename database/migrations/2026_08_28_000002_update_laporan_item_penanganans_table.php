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
        Schema::table('laporan_item_penanganans', function (Blueprint $table) {
            $table->text('tindakan_atau_hasil')->nullable()->after('tahap');
            $table->dateTime('tanggal_tindakan')->nullable()->after('status_item_saat_itu');
            $table->dateTime('tanggal_hasil')->nullable()->after('tanggal_tindakan');
        });

        // Migrate existing data if any
        if (Schema::hasColumn('laporan_item_penanganans', 'tindakan_atau_saran')) {
            $records = DB::table('laporan_item_penanganans')->get();
            foreach ($records as $r) {
                $combined = $r->tindakan_atau_saran ?? '';
                if (!empty($r->hasil)) {
                    $combined .= "\n\nHasil: " . $r->hasil;
                }
                DB::table('laporan_item_penanganans')->where('id', $r->id)->update([
                    'tindakan_atau_hasil' => $combined,
                    'tanggal_tindakan'    => $r->tanggal_waktu ?? now(),
                    'tanggal_hasil'       => !empty($r->hasil) ? ($r->updated_at ?? now()) : null,
                ]);
            }

            Schema::table('laporan_item_penanganans', function (Blueprint $table) {
                $table->dropColumn(['tindakan_atau_saran', 'hasil']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_item_penanganans', function (Blueprint $table) {
            $table->text('tindakan_atau_saran')->nullable()->after('tahap');
            $table->text('hasil')->nullable()->after('tindakan_atau_saran');
            $table->dropColumn(['tindakan_atau_hasil', 'tanggal_tindakan', 'tanggal_hasil']);
        });
    }
};
