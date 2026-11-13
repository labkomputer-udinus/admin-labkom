<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    private function getAuthenticatedUser()
    {
        return User::first() ?? User::factory()->create();
    }

    public function test_laporan_masuk_sorted_by_date_descending_by_default(): void
    {
        $user = $this->getAuthenticatedUser();
        $prefix = 'SORT_' . rand(1000, 9999);

        $lap1 = Laporan::create([
            'lab_nama' => 'D2A',
            'tanggal_laporan' => '2026-01-10',
            'nama_pelapor' => $prefix . '_Pelapor_A',
            'status' => 'dicatat',
        ]);

        $lap2 = Laporan::create([
            'lab_nama' => 'D2A',
            'tanggal_laporan' => '2026-09-15',
            'nama_pelapor' => $prefix . '_Pelapor_B',
            'status' => 'dicatat',
        ]);

        $lap3 = Laporan::create([
            'lab_nama' => 'D2A',
            'tanggal_laporan' => '2026-05-20',
            'nama_pelapor' => $prefix . '_Pelapor_C',
            'status' => 'dicatat',
        ]);

        // Default: descending (lap2: 2026-09-15, lap3: 2026-05-20, lap1: 2026-01-10)
        $response = $this->actingAs($user)->get(route('laporan.index', ['search' => $prefix]));

        $response->assertStatus(200);
        $response->assertViewIs('laporan.index');
        $response->assertViewHas('laporans');

        $laporans = $response->viewData('laporans');
        $ids = $laporans->pluck('id')->all();
        $this->assertEquals([$lap2->id, $lap3->id, $lap1->id], $ids);

        // Explicit ascending sort: (lap1: 2026-01-10, lap3: 2026-05-20, lap2: 2026-09-15)
        $responseAsc = $this->actingAs($user)->get(route('laporan.index', ['search' => $prefix, 'sort' => 'asc']));
        $responseAsc->assertStatus(200);
        $laporansAsc = $responseAsc->viewData('laporans');
        $idsAsc = $laporansAsc->pluck('id')->all();
        $this->assertEquals([$lap1->id, $lap3->id, $lap2->id], $idsAsc);

        // Cleanup
        $lap1->delete();
        $lap2->delete();
        $lap3->delete();
    }
}
