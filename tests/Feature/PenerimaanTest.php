<?php

namespace Tests\Feature;

use App\Models\BarangMaster;
use App\Models\Pengajuan;
use App\Models\PengajuanItem;
use App\Models\StokGudang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenerimaanTest extends TestCase
{
    use RefreshDatabase;

    private function getAuthenticatedUser()
    {
        return User::first() ?? User::factory()->create();
    }

    public function test_can_verify_penerimaan_sesuai_masuk_stok(): void
    {
        $user = $this->getAuthenticatedUser();

        $pengajuan = Pengajuan::create([
            'nomor_pengajuan' => 'PGD-TEST-SESUAI-' . uniqid(),
            'divisi_tujuan' => 'BIUM',
            'tanggal_ajuan' => now(),
            'status' => 'disetujui',
        ]);

        $item = PengajuanItem::create([
            'pengajuan_id' => $pengajuan->id,
            'nama_barang' => 'Mouse Logitech M170',
            'spesifikasi' => 'Wireless Mouse 2.4GHz',
            'jumlah_diajukan' => 4,
            'satuan' => 'unit',
        ]);

        $response = $this->actingAs($user)->post(route('penerimaan.simpan', $pengajuan), [
            'items' => [
                [
                    'id' => $item->id,
                    'jumlah_diterima' => 4,
                    'status_kesesuaian' => 'sesuai',
                    'tanggal_terima' => now()->toDateString(),
                    'spesifikasi_diterima' => 'Wireless Mouse 2.4GHz original box',
                    'catatan_selisih' => null,
                ],
            ],
        ]);

        $response->assertRedirect(route('pengajuan.show', $pengajuan));

        $item->refresh();
        $this->assertEquals('sesuai', $item->status_kesesuaian);
        $this->assertEquals(4, $item->jumlah_diterima);

        $barang = BarangMaster::where('nama_barang', 'Mouse Logitech M170')->first();
        $this->assertNotNull($barang);

        $stok = StokGudang::where('barang_master_id', $barang->id)->first();
        $this->assertNotNull($stok);
        $this->assertGreaterThanOrEqual(4, $stok->stok_saat_ini);
    }

    public function test_can_verify_penerimaan_sesuai_beda_spesifikasi_masuk_stok_aktual(): void
    {
        $user = $this->getAuthenticatedUser();

        $pengajuan = Pengajuan::create([
            'nomor_pengajuan' => 'PGD-TEST-BEDA-' . uniqid(),
            'divisi_tujuan' => 'BIUM',
            'tanggal_ajuan' => now(),
            'status' => 'disetujui',
        ]);

        $item = PengajuanItem::create([
            'pengajuan_id' => $pengajuan->id,
            'nama_barang' => 'Keyboard Mechanical Standard',
            'spesifikasi' => 'Blue switch wired',
            'jumlah_diajukan' => 3,
            'satuan' => 'unit',
        ]);

        $response = $this->actingAs($user)->post(route('penerimaan.simpan', $pengajuan), [
            'items' => [
                [
                    'id' => $item->id,
                    'jumlah_diterima' => 3,
                    'status_kesesuaian' => 'sesuai_beda_spesifikasi',
                    'merk_diterima' => 'Rexus Legionare MX9',
                    'spesifikasi_diterima' => 'Outemu Brown switch, RGB, wired USB-C',
                    'alasan_beda_spesifikasi' => 'Kebijakan pengadaan pusat mengganti vendor penyedia',
                    'catatan_selisih' => 'Dus segel utuh',
                    'tanggal_terima' => now()->toDateString(),
                ],
            ],
        ]);

        $response->assertRedirect(route('pengajuan.show', $pengajuan));

        $item->refresh();
        $this->assertEquals('sesuai_beda_spesifikasi', $item->status_kesesuaian);
        $this->assertEquals(3, $item->jumlah_diterima);
        $this->assertStringContainsString('Rexus Legionare MX9', $item->spesifikasi_diterima);
        $this->assertStringContainsString('Kebijakan pengadaan pusat', $item->catatan_selisih);

        // Harus masuk ke BarangMaster dengan nama spesifik aktual
        $expectedNamaBarang = 'Keyboard Mechanical Standard (Rexus Legionare MX9)';
        $barang = BarangMaster::where('nama_barang', $expectedNamaBarang)->first();
        $this->assertNotNull($barang, "BarangMaster '{$expectedNamaBarang}' harus terbuat di gudang");

        $stok = StokGudang::where('barang_master_id', $barang->id)->first();
        $this->assertNotNull($stok);
        $this->assertGreaterThanOrEqual(3, $stok->stok_saat_ini);
    }

    public function test_can_verify_penerimaan_tidak_sesuai_tidak_masuk_stok(): void
    {
        $user = $this->getAuthenticatedUser();

        $pengajuan = Pengajuan::create([
            'nomor_pengajuan' => 'PGD-TEST-TOLAK-' . uniqid(),
            'divisi_tujuan' => 'BIUM',
            'tanggal_ajuan' => now(),
            'status' => 'disetujui',
        ]);

        $uniqueNama = 'Kabel HDMI Rusak ' . uniqid();
        $item = PengajuanItem::create([
            'pengajuan_id' => $pengajuan->id,
            'nama_barang' => $uniqueNama,
            'spesifikasi' => 'HDMI 2.1 5 meter',
            'jumlah_diajukan' => 2,
            'satuan' => 'buah',
        ]);

        $response = $this->actingAs($user)->post(route('penerimaan.simpan', $pengajuan), [
            'items' => [
                [
                    'id' => $item->id,
                    'jumlah_diterima' => 0,
                    'status_kesesuaian' => 'tidak_sesuai',
                    'catatan_selisih' => 'Barang rusak dan tidak dapat digunakan saat serah terima',
                    'tanggal_terima' => now()->toDateString(),
                ],
            ],
        ]);

        $response->assertRedirect(route('pengajuan.show', $pengajuan));

        $item->refresh();
        $this->assertEquals('tidak_sesuai', $item->status_kesesuaian);

        // BarangMaster tidak boleh terbuat
        $barang = BarangMaster::where('nama_barang', $uniqueNama)->first();
        $this->assertNull($barang);
    }
}
