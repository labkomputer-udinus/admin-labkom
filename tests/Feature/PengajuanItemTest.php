<?php

namespace Tests\Feature;

use App\Models\Pengajuan;
use App\Models\PengajuanItem;
use App\Models\User;
use Tests\TestCase;

class PengajuanItemTest extends TestCase
{
    private function getAuthenticatedUser()
    {
        return User::first() ?? User::factory()->create();
    }

    public function test_can_update_pengajuan_item(): void
    {
        $user = $this->getAuthenticatedUser();
        $pengajuan = Pengajuan::first() ?? Pengajuan::create([
            'nomor_pengajuan' => 'PGD/2026/09/999',
            'divisi_tujuan' => 'BIUM',
            'tanggal_ajuan' => now(),
            'status' => 'menunggu',
        ]);

        $item = $pengajuan->items()->first() ?? PengajuanItem::create([
            'pengajuan_id' => $pengajuan->id,
            'nama_barang' => 'Barang Awal',
            'jumlah_diajukan' => 5,
            'satuan' => 'unit',
            'spesifikasi' => 'Spek Awal',
        ]);

        $response = $this->actingAs($user)->put(route('pengajuan.item.update', [$pengajuan, $item]), [
            'nama_barang' => 'Barang Diperbarui',
            'jumlah_diajukan' => 10,
            'satuan' => 'buah',
            'spesifikasi' => 'Spek Baru',
        ]);

        $response->assertRedirect(route('pengajuan.show', $pengajuan));
        $this->assertDatabaseHas('pengajuan_items', [
            'id' => $item->id,
            'nama_barang' => 'Barang Diperbarui',
            'jumlah_diajukan' => 10,
            'satuan' => 'buah',
            'spesifikasi' => 'Spek Baru',
        ]);
    }

    public function test_can_add_item_to_pengajuan(): void
    {
        $user = $this->getAuthenticatedUser();
        $pengajuan = Pengajuan::first();

        $response = $this->actingAs($user)->post(route('pengajuan.item.store', $pengajuan), [
            'nama_barang' => 'Item Tambahan Baru',
            'jumlah_diajukan' => 3,
            'satuan' => 'set',
            'spesifikasi' => 'Tambahan tes',
        ]);

        $response->assertRedirect(route('pengajuan.show', $pengajuan));
        $this->assertDatabaseHas('pengajuan_items', [
            'pengajuan_id' => $pengajuan->id,
            'nama_barang' => 'Item Tambahan Baru',
            'jumlah_diajukan' => 3,
            'satuan' => 'set',
        ]);
    }

    public function test_can_delete_item_from_pengajuan_when_more_than_one(): void
    {
        $user = $this->getAuthenticatedUser();
        $pengajuan = Pengajuan::first();

        $itemToDelete = PengajuanItem::where('nama_barang', 'Item Tambahan Baru')->first();
        if ($itemToDelete) {
            $response = $this->actingAs($user)->delete(route('pengajuan.item.destroy', [$pengajuan, $itemToDelete]));
            $response->assertRedirect(route('pengajuan.show', $pengajuan));
            $this->assertDatabaseMissing('pengajuan_items', [
                'id' => $itemToDelete->id,
            ]);
        }
    }
}
