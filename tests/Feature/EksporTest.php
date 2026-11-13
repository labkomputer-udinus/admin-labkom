<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EksporTest extends TestCase
{
    use RefreshDatabase;

    private function getAuthenticatedUser(): User
    {
        return User::factory()->create();
    }

    public function test_can_export_laporan_excel_and_pdf_with_filters(): void
    {
        $user = $this->getAuthenticatedUser();

        // Excel
        $responseExcel = $this->actingAs($user)->get(route('ekspor.laporan.excel', [
            'start_date' => '2026-01-01',
            'end_date'   => '2026-12-31',
        ]));
        $responseExcel->assertStatus(200);
        $responseExcel->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // PDF
        $responsePdf = $this->actingAs($user)->get(route('ekspor.laporan.pdf', [
            'start_date' => '2026-01-01',
            'end_date'   => '2026-12-31',
        ]));
        $responsePdf->assertStatus(200);
        $responsePdf->assertViewIs('ekspor.laporan-pdf');
    }

    public function test_can_export_stok_excel_and_pdf(): void
    {
        $user = $this->getAuthenticatedUser();

        // Excel
        $responseExcel = $this->actingAs($user)->get(route('ekspor.stok.excel', [
            'status_stok' => 'kritis',
        ]));
        $responseExcel->assertStatus(200);
        $responseExcel->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // PDF
        $responsePdf = $this->actingAs($user)->get(route('ekspor.stok.pdf'));
        $responsePdf->assertStatus(200);
        $responsePdf->assertViewIs('ekspor.stok-pdf');
    }

    public function test_can_export_distribusi_excel_and_pdf(): void
    {
        $user = $this->getAuthenticatedUser();

        // Excel
        $responseExcel = $this->actingAs($user)->get(route('ekspor.distribusi.excel', [
            'start_date' => '2026-01-01',
            'end_date'   => '2026-12-31',
        ]));
        $responseExcel->assertStatus(200);
        $responseExcel->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // PDF
        $responsePdf = $this->actingAs($user)->get(route('ekspor.distribusi.pdf'));
        $responsePdf->assertStatus(200);
        $responsePdf->assertViewIs('ekspor.distribusi-pdf');
    }

    public function test_can_export_pengajuan_excel_and_pdf(): void
    {
        $user = $this->getAuthenticatedUser();

        // Excel
        $responseExcel = $this->actingAs($user)->get(route('ekspor.pengajuan.excel', [
            'start_date' => '2026-01-01',
            'end_date'   => '2026-12-31',
        ]));
        $responseExcel->assertStatus(200);
        $responseExcel->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // PDF
        $responsePdf = $this->actingAs($user)->get(route('ekspor.pengajuan.pdf'));
        $responsePdf->assertStatus(200);
        $responsePdf->assertViewIs('ekspor.pengajuan-pdf');
    }

    public function test_can_export_riwayat_laporan_detail(): void
    {
        $user = $this->getAuthenticatedUser();
        $laporan = Laporan::create([
            'lab_nama'        => 'D.2.A',
            'nama_pelapor'    => 'Test Pelapor',
            'tanggal_laporan' => now(),
            'status'          => 'dicatat',
        ]);

        // Excel
        $responseExcel = $this->actingAs($user)->get(route('ekspor.laporan.detail.excel', $laporan));
        $responseExcel->assertStatus(200);
        $responseExcel->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // PDF
        $responsePdf = $this->actingAs($user)->get(route('ekspor.laporan.detail.pdf', $laporan));
        $responsePdf->assertStatus(200);
        $responsePdf->assertViewIs('ekspor.riwayat-laporan-pdf');
    }
}
