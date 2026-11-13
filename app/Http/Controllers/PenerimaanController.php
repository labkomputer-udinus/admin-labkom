<?php

namespace App\Http\Controllers;

use App\Constants\JenisTindakan;
use App\Models\BarangMaster;
use App\Models\Pengajuan;
use App\Models\PengajuanItem;
use App\Models\StokGudang;
use App\Models\StokTransaksi;
use App\Services\PenangananLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load(['items.laporanItem.laporan']);
        return view('penerimaan.verifikasi', compact('pengajuan'));
    }

    public function simpan(Request $request, Pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:pengajuan_items,id'],
            'items.*.jumlah_diterima' => ['required', 'integer', 'min:0'],
            'items.*.spesifikasi_diterima' => ['nullable', 'string'],
            'items.*.status_kesesuaian' => ['required', 'in:sesuai,sesuai_beda_spesifikasi,tidak_sesuai'],
            'items.*.merk_diterima' => ['nullable', 'string', 'max:255'],
            'items.*.alasan_beda_spesifikasi' => ['nullable', 'string', 'max:500'],
            'items.*.catatan_selisih' => ['nullable', 'string'],
            'items.*.tanggal_terima' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($validated, $pengajuan) {
            foreach ($validated['items'] as $itemData) {
                $item = PengajuanItem::with('laporanItem.laporan')->findOrFail($itemData['id']);
                $prevJumlahDiterima = $item->jumlah_diterima ?? 0;
                $prevMasukStok = in_array($item->status_kesesuaian, ['sesuai', 'sesuai_beda_spesifikasi']);

                // Siapkan data spesifikasi & catatan jika beda spesifikasi/merk karena kebijakan pusat
                $statusKesesuaian = $itemData['status_kesesuaian'];
                $merkDiterima = trim($itemData['merk_diterima'] ?? '');
                $spekDiterima = trim($itemData['spesifikasi_diterima'] ?? '');
                $alasanBeda = trim($itemData['alasan_beda_spesifikasi'] ?? '');
                $catatanSelisih = trim($itemData['catatan_selisih'] ?? '');

                // Format spesifikasi diterima jika ada merk
                if ($statusKesesuaian === 'sesuai_beda_spesifikasi') {
                    $combinedSpek = '';
                    if (!empty($merkDiterima)) {
                        $combinedSpek .= "[Merk/Model: {$merkDiterima}]";
                    }
                    if (!empty($spekDiterima)) {
                        $combinedSpek .= ($combinedSpek ? ' ' : '') . $spekDiterima;
                    }
                    $finalSpekDiterima = !empty($combinedSpek) ? $combinedSpek : null;

                    // Gabungkan alasan ke catatan selisih jika belum ada
                    if (!empty($alasanBeda)) {
                        $finalCatatanSelisih = "Kebijakan Pusat: {$alasanBeda}" . (!empty($catatanSelisih) ? " | {$catatanSelisih}" : '');
                    } else {
                        $finalCatatanSelisih = !empty($catatanSelisih) ? $catatanSelisih : null;
                    }
                } else {
                    $finalSpekDiterima = !empty($spekDiterima) ? $spekDiterima : null;
                    $finalCatatanSelisih = !empty($catatanSelisih) ? $catatanSelisih : null;
                }

                $item->update([
                    'jumlah_diterima' => $itemData['jumlah_diterima'],
                    'spesifikasi_diterima' => $finalSpekDiterima,
                    'status_kesesuaian' => $statusKesesuaian,
                    'catatan_selisih' => $finalCatatanSelisih,
                    'tanggal_terima' => $itemData['tanggal_terima'],
                ]);

                // Proses penambahan stok gudang jika status 'sesuai' atau 'sesuai_beda_spesifikasi'
                $isMasukStok = in_array($statusKesesuaian, ['sesuai', 'sesuai_beda_spesifikasi']);
                if ($isMasukStok && $itemData['jumlah_diterima'] > 0) {
                    $targetNama = trim($item->nama_barang);

                    // Tentukan spesifikasi fisik barang yang disimpan di master gudang
                    if ($statusKesesuaian === 'sesuai_beda_spesifikasi') {
                        $spekBarangGudang = !empty($merkDiterima) ? $merkDiterima : (!empty($spekDiterima) ? $spekDiterima : $item->spesifikasi);
                    } else {
                        $spekBarangGudang = !empty($spekDiterima) ? $spekDiterima : $item->spesifikasi;
                    }
                    $targetSpek = !empty($spekBarangGudang) ? trim($spekBarangGudang) : null;

                    // Cari atau buat BarangMaster baru jika nama atau spesifikasi berbeda
                    $barangQuery = BarangMaster::where('nama_barang', $targetNama);
                    if (empty($targetSpek)) {
                        $barangQuery->where(function ($q) {
                            $q->whereNull('spesifikasi')->orWhere('spesifikasi', '');
                        });
                    } else {
                        $barangQuery->where('spesifikasi', $targetSpek);
                    }
                    $barang = $barangQuery->first();

                    if (!$barang) {
                        $barang = BarangMaster::create([
                            'nama_barang' => $targetNama,
                            'spesifikasi' => $targetSpek,
                            'kategori' => 'Komponen/Periferal',
                            'satuan' => $item->satuan,
                            'stok_minimum' => 2,
                        ]);
                    }

                    $stok = StokGudang::firstOrCreate(
                        ['barang_master_id' => $barang->id],
                        ['stok_saat_ini' => 0]
                    );

                    // Catat riwayat jika ada penyesuaian pusat di catatan stok gudang jika belum ada
                    if ($statusKesesuaian === 'sesuai_beda_spesifikasi' && empty($stok->catatan) && !empty($alasanBeda)) {
                        $stok->catatan = "Pengadaan {$pengajuan->nomor_pengajuan} (Penyesuaian spesifikasi pusat: {$alasanBeda})";
                        $stok->save();
                    }

                    // Hitung delta penambahan jika sebelumnya sudah pernah diverifikasi
                    $delta = $prevMasukStok ? ($itemData['jumlah_diterima'] - $prevJumlahDiterima) : $itemData['jumlah_diterima'];

                    if ($delta > 0) {
                        $stok->increment('stok_saat_ini', $delta);

                        $keteranganTransaksi = "Penerimaan pengadaan {$pengajuan->nomor_pengajuan} - {$barang->nama_lengkap}"
                            . ($statusKesesuaian === 'sesuai_beda_spesifikasi' ? ' (Penyesuaian Spek)' : '');

                        StokTransaksi::create([
                            'stok_gudang_id' => $stok->id,
                            'jenis' => 'masuk',
                            'jumlah' => $delta,
                            'referensi_type' => 'pengajuan_items',
                            'referensi_id' => $item->id,
                            'keterangan' => $keteranganTransaksi,
                            'tanggal_transaksi' => $itemData['tanggal_terima'],
                        ]);
                    }
                }

                // Update laporan item & laporan terkait + catat log audit trail
                if ($item->laporanItem) {
                    $laporan = $item->laporanItem->laporan;
                    $statusLaporanBaru = $isMasukStok ? 'siap_distribusi' : 'perlu_revisi';
                    if ($laporan && in_array($laporan->status, ['menunggu_keputusan_divisi', 'dalam_proses', 'ditriase'])) {
                        $laporan->status = $statusLaporanBaru;
                        $laporan->save();
                    }

                    $prevStatus = $item->laporanItem->status_item;

                    if ($statusKesesuaian === 'sesuai_beda_spesifikasi') {
                        $namaBarangFinal = isset($namaBarangGudang) ? $namaBarangGudang : $item->nama_barang;
                        $tindakanHasil = "Verifikasi Penerimaan (Sesuai Beda Spesifikasi/Merk): {$itemData['jumlah_diterima']} {$item->satuan} diterima.";
                        if (!empty($item->spesifikasi)) {
                            $tindakanHasil .= " Diajukan: {$item->spesifikasi}.";
                        }
                        if (!empty($finalSpekDiterima)) {
                            $tindakanHasil .= " Diterima: {$finalSpekDiterima}.";
                        }
                        if (!empty($alasanBeda)) {
                            $tindakanHasil .= " Kebijakan/Alasan: {$alasanBeda}.";
                        }
                        $tindakanHasil .= " Masuk stok gudang sebagai '{$namaBarangFinal}'.";
                        $tahapLog = 'Verifikasi Penerimaan Barang (Beda Spesifikasi)';
                    } else {
                        $labelStatus = $statusKesesuaian === 'sesuai' ? 'Sesuai Penuh' : 'Tidak Sesuai';
                        $tindakanHasil = "Verifikasi Penerimaan: {$itemData['jumlah_diterima']} {$item->satuan} diterima (Status: {$labelStatus}).";
                        if (!empty($finalSpekDiterima)) {
                            $tindakanHasil .= " Spek diterima: {$finalSpekDiterima}.";
                        }
                        if (!empty($finalCatatanSelisih)) {
                            $tindakanHasil .= " Catatan: {$finalCatatanSelisih}.";
                        }
                        $tahapLog = 'Verifikasi Penerimaan Barang';
                    }

                    PenangananLogger::log(
                        item: $item->laporanItem,
                        jenisTindakan: JenisTindakan::VERIFIKASI_PENERIMAAN,
                        tahap: $tahapLog,
                        tindakanAtauHasil: $tindakanHasil,
                        aktorPeran: 'Petugas Penerima: ' . (auth()->user()->name ?? 'Admin Lab'),
                        statusSebelum: $prevStatus,
                        statusSesudah: $prevStatus,
                        tanggal: $itemData['tanggal_terima']
                    );
                }
            }
        });

        return redirect()->route('pengajuan.show', $pengajuan)
            ->with('success', 'Verifikasi penerimaan barang berhasil disimpan, stok gudang & riwayat log telah diperbarui.');
    }
}
