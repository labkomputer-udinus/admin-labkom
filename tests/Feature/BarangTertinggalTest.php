<?php

namespace Tests\Feature;

use App\Constants\StatusBarangTertinggal;
use App\Models\BarangTertinggal;
use App\Models\User;
use Tests\TestCase;

class BarangTertinggalTest extends TestCase
{
    private function getAuthenticatedUser()
    {
        return User::first() ?? User::factory()->create();
    }

    public function test_can_list_barang_tertinggal(): void
    {
        $user = $this->getAuthenticatedUser();

        $response = $this->actingAs($user)->get(route('barang-tertinggal.index'));
        $response->assertStatus(200);
        $response->assertViewIs('barang_tertinggal.index');
        $response->assertViewHas('barangTertinggals', function ($barangTertinggals) {
            return $barangTertinggals->perPage() === 10;
        });
    }

    public function test_can_store_barang_tertinggal_with_preset_lab(): void
    {
        $user = $this->getAuthenticatedUser();

        $data = [
            'tanggal_ditemukan'   => '2026-09-18',
            'lab_pilihan'         => 'D2A',
            'penemu'              => 'Budi Laboran',
            'nama_barang'         => 'Flashdisk Kingston 32GB Hitam',
            'keterangan'          => 'Gantungan kunci tali biru',
            'pemilik'             => 'Andi Mahasiswa',
            'no_hp'               => '081234567890',
            'status'              => StatusBarangTertinggal::BELUM_DIAMBIL,
            'catatan'             => 'Disimpan di loker 1',
        ];

        $response = $this->actingAs($user)->post(route('barang-tertinggal.store'), $data);

        $this->assertDatabaseHas('barang_tertinggals', [
            'nama_barang' => 'Flashdisk Kingston 32GB Hitam',
            'lab_nama'    => 'D2A',
            'penemu'      => 'Budi Laboran',
            'status'      => 'belum_diambil',
        ]);

        $barang = BarangTertinggal::where('nama_barang', 'Flashdisk Kingston 32GB Hitam')->latest('id')->first();
        $response->assertRedirect(route('barang-tertinggal.show', $barang));
    }

    public function test_can_store_barang_tertinggal_with_custom_lab(): void
    {
        $user = $this->getAuthenticatedUser();

        $data = [
            'tanggal_ditemukan'   => '2026-09-18',
            'lab_pilihan'         => 'Lainnya',
            'lab_lainnya'         => 'Koridor Lantai 2 Gedung D',
            'penemu'              => 'Satpam Kampus',
            'nama_barang'         => 'Jaket Hoodie Hitam Polos',
            'keterangan'          => 'Ukuran XL, ada logo Udinus',
            'pemilik'             => null,
            'no_hp'               => null,
            'status'              => StatusBarangTertinggal::DIUMUMKAN,
        ];

        $response = $this->actingAs($user)->post(route('barang-tertinggal.store'), $data);

        $this->assertDatabaseHas('barang_tertinggals', [
            'nama_barang' => 'Jaket Hoodie Hitam Polos',
            'lab_nama'    => 'Koridor Lantai 2 Gedung D',
            'status'      => 'diumumkan',
        ]);
    }

    public function test_can_update_status_barang_tertinggal(): void
    {
        $user = $this->getAuthenticatedUser();

        $barang = BarangTertinggal::create([
            'tanggal_ditemukan' => '2026-09-10',
            'lab_nama'          => 'D2B',
            'penemu'            => 'Mahasiswa',
            'nama_barang'       => 'Tumbler Thermos Silver',
            'status'            => StatusBarangTertinggal::BELUM_DIAMBIL,
        ]);

        $response = $this->actingAs($user)->patch(route('barang-tertinggal.status', $barang), [
            'status'              => StatusBarangTertinggal::SUDAH_DIAMBIL,
            'tanggal_pengambilan' => '2026-09-18',
            'penyerah'            => 'Laboran D2B',
            'pemilik'             => 'Siti Rahma',
            'no_hp'               => '08987654321',
            'catatan'             => 'Diambil oleh pemilik sah setelah mencocokkan ciri-ciri',
        ]);

        $response->assertRedirect(route('barang-tertinggal.show', $barang));
        $this->assertDatabaseHas('barang_tertinggals', [
            'id'                  => $barang->id,
            'status'              => 'sudah_diambil',
            'pemilik'             => 'Siti Rahma',
            'penyerah'            => 'Laboran D2B',
        ]);
    }

    public function test_can_export_barang_tertinggal_to_excel_and_pdf(): void
    {
        $user = $this->getAuthenticatedUser();

        // Test Excel export (.xlsx)
        $responseExcel = $this->actingAs($user)->get(route('ekspor.barang-tertinggal.excel'));
        $responseExcel->assertStatus(200);
        $responseExcel->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        // Test PDF export
        $responsePdf = $this->actingAs($user)->get(route('ekspor.barang-tertinggal.pdf'));
        $responsePdf->assertStatus(200);
        $responsePdf->assertViewIs('ekspor.barang-tertinggal-pdf');
    }
}
