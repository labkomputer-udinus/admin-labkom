<?php

namespace App\Http\Controllers;

use App\Constants\JenisTindakan;
use App\Models\BarangMaster;
use App\Models\LaporanItem;
use App\Models\Pengajuan;
use App\Models\PengajuanItem;
use App\Services\PenangananLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::with('items')->latest('tanggal_ajuan')->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_ajuan', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_ajuan', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', "%{$search}%")
                  ->orWhere('divisi_tujuan', 'like', "%{$search}%")
                  ->orWhereHas('items', fn($iq) => $iq->where('nama_barang', 'like', "%{$search}%"));
            });
        }

        $pengajuans = $query->paginate(10)->withQueryString();

        return view('pengajuan.index', compact('pengajuans'));
    }

    public function create()
    {
        $autoNomor = Pengajuan::generateNomorPengajuan();

        // Ambil item laporan yang butuh penggantian dan belum masuk pengajuan aktif
        $availableItems = LaporanItem::with('laporan')
            ->where('jenis_tindak_lanjut', 'penggantian')
            ->whereIn('status_item', ['menunggu_triase', 'menunggu_pengadaan'])
            ->get();

        $topBarangs = BarangMaster::with('stokGudang')
            ->orderBy('nama_barang', 'asc')
            ->limit(7)
            ->get()
            ->map(fn($b) => [
                'id' => $b->id,
                'nama_barang' => $b->nama_barang,
                'spesifikasi' => $b->spesifikasi ?? '',
                'nama_lengkap' => $b->nama_lengkap,
                'kategori' => $b->kategori ?? 'Umum',
                'satuan' => $b->satuan ?? 'unit',
                'stok_saat_ini' => $b->stokGudang?->stok_saat_ini ?? 0,
                'catatan' => $b->stokGudang?->catatan ?? '',
            ]);

        return view('pengajuan.create', compact('autoNomor', 'availableItems', 'topBarangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_pengajuan' => ['required', 'string', 'unique:pengajuans,nomor_pengajuan'],
            'divisi_tujuan' => ['required', 'string', 'max:255'],
            'tanggal_ajuan' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.laporan_item_id' => ['nullable', 'exists:laporan_items,id'],
            'items.*.nama_barang' => ['required', 'string', 'max:255'],
            'items.*.spesifikasi' => ['nullable', 'string'],
            'items.*.jumlah_diajukan' => ['required', 'integer', 'min:1'],
            'items.*.satuan' => ['required', 'string', 'max:50'],
        ]);

        DB::transaction(function () use ($validated) {
            $pengajuan = Pengajuan::create([
                'nomor_pengajuan' => $validated['nomor_pengajuan'],
                'divisi_tujuan' => $validated['divisi_tujuan'],
                'tanggal_ajuan' => $validated['tanggal_ajuan'],
                'status' => 'menunggu',
            ]);

            foreach ($validated['items'] as $itemData) {
                PengajuanItem::create([
                    'pengajuan_id' => $pengajuan->id,
                    'laporan_item_id' => $itemData['laporan_item_id'] ?? null,
                    'nama_barang' => $itemData['nama_barang'],
                    'spesifikasi' => $itemData['spesifikasi'] ?? null,
                    'jumlah_diajukan' => $itemData['jumlah_diajukan'],
                    'satuan' => $itemData['satuan'],
                ]);

                if (!empty($itemData['laporan_item_id'])) {
                    $laporanItem = LaporanItem::find($itemData['laporan_item_id']);
                    if ($laporanItem) {
                        $prevStatus = $laporanItem->status_item;
                        $laporanItem->status_item = 'menunggu_pengadaan';
                        $laporanItem->save();

                        // Auto-log Pengajuan ke BIUM
                        $spekInfo = !empty($itemData['spesifikasi']) ? " (Spesifikasi: {$itemData['spesifikasi']})" : "";
                        PenangananLogger::log(
                            item: $laporanItem,
                            jenisTindakan: JenisTindakan::PENGAJUAN_BIUM,
                            tahap: 'Pengajuan Pengadaan ke BIUM',
                            tindakanAtauHasil: "Pengajuan #{$pengajuan->nomor_pengajuan} ke Divisi {$pengajuan->divisi_tujuan} dikirim. Diajukan: {$itemData['nama_barang']} ({$itemData['jumlah_diajukan']} {$itemData['satuan']}){$spekInfo}.",
                            aktorPeran: 'Admin: ' . (auth()->user()->name ?? 'Admin Lab'),
                            statusSebelum: $prevStatus,
                            statusSesudah: 'menunggu_pengadaan',
                            tanggal: $validated['tanggal_ajuan']
                        );

                        $laporan = $laporanItem->laporan;
                        if ($laporan && in_array($laporan->status, ['dicatat', 'ditriase', 'dalam_proses'])) {
                            $laporan->status = 'menunggu_keputusan_divisi';
                            $laporan->save();
                        }
                    }
                }
            }
        });

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan pengadaan berhasil dibuat dan riwayat log telah diperbarui.');
    }

    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load(['items.laporanItem.laporan', 'items.distribusis']);

        // Item laporan yang butuh penggantian dan belum masuk pengajuan aktif
        $availableItems = LaporanItem::with('laporan')
            ->where('jenis_tindak_lanjut', 'penggantian')
            ->whereIn('status_item', ['menunggu_triase', 'menunggu_pengadaan'])
            ->get();

        $topBarangs = BarangMaster::with('stokGudang')
            ->orderBy('nama_barang', 'asc')
            ->limit(7)
            ->get()
            ->map(fn($b) => [
                'id' => $b->id,
                'nama_barang' => $b->nama_barang,
                'spesifikasi' => $b->spesifikasi ?? '',
                'nama_lengkap' => $b->nama_lengkap,
                'kategori' => $b->kategori ?? 'Umum',
                'satuan' => $b->satuan ?? 'unit',
                'stok_saat_ini' => $b->stokGudang?->stok_saat_ini ?? 0,
                'catatan' => $b->stokGudang?->catatan ?? '',
            ]);

        return view('pengajuan.show', compact('pengajuan', 'availableItems', 'topBarangs'));
    }

    public function edit(Pengajuan $pengajuan)
    {
        return redirect()->route('pengajuan.show', $pengajuan);
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'nomor_pengajuan' => ['required', 'string', 'unique:pengajuans,nomor_pengajuan,' . $pengajuan->id],
            'divisi_tujuan'   => ['required', 'string', 'max:255'],
            'tanggal_ajuan'   => ['required', 'date'],
        ]);

        $pengajuan->update($validated);

        return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Informasi pengajuan pengadaan berhasil diperbarui.');
    }

    public function storeItem(Request $request, Pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'nama_barang'      => ['required', 'string', 'max:255'],
            'spesifikasi'      => ['nullable', 'string'],
            'jumlah_diajukan'  => ['required', 'integer', 'min:1'],
            'satuan'           => ['required', 'string', 'max:50'],
            'laporan_item_id'  => ['nullable', 'exists:laporan_items,id'],
        ]);

        DB::transaction(function () use ($validated, $pengajuan) {
            PengajuanItem::create([
                'pengajuan_id'    => $pengajuan->id,
                'laporan_item_id' => $validated['laporan_item_id'] ?? null,
                'nama_barang'     => $validated['nama_barang'],
                'spesifikasi'     => $validated['spesifikasi'] ?? null,
                'jumlah_diajukan' => $validated['jumlah_diajukan'],
                'satuan'          => $validated['satuan'],
            ]);

            if (!empty($validated['laporan_item_id'])) {
                $laporanItem = LaporanItem::find($validated['laporan_item_id']);
                if ($laporanItem) {
                    $prevStatus = $laporanItem->status_item;
                    $laporanItem->status_item = 'menunggu_pengadaan';
                    $laporanItem->save();

                    $spekInfo = !empty($validated['spesifikasi']) ? " (Spesifikasi: {$validated['spesifikasi']})" : "";
                    PenangananLogger::log(
                        item: $laporanItem,
                        jenisTindakan: JenisTindakan::PENGAJUAN_BIUM,
                        tahap: 'Pengajuan Pengadaan ke BIUM',
                        tindakanAtauHasil: "Item ditambahkan ke Pengajuan #{$pengajuan->nomor_pengajuan} ke Divisi {$pengajuan->divisi_tujuan}. Diajukan: {$validated['nama_barang']} ({$validated['jumlah_diajukan']} {$validated['satuan']}){$spekInfo}.",
                        aktorPeran: 'Admin: ' . (auth()->user()->name ?? 'Admin Lab'),
                        statusSebelum: $prevStatus,
                        statusSesudah: 'menunggu_pengadaan',
                        tanggal: $pengajuan->tanggal_ajuan ?? now()
                    );

                    $laporan = $laporanItem->laporan;
                    if ($laporan && in_array($laporan->status, ['dicatat', 'ditriase', 'dalam_proses'])) {
                        $laporan->status = 'menunggu_keputusan_divisi';
                        $laporan->save();
                    }
                }
            }
        });

        return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Item barang baru berhasil ditambahkan ke pengajuan.');
    }

    public function updateItem(Request $request, Pengajuan $pengajuan, PengajuanItem $item)
    {
        $validated = $request->validate([
            'nama_barang'      => ['required', 'string', 'max:255'],
            'spesifikasi'      => ['nullable', 'string'],
            'jumlah_diajukan'  => ['required', 'integer', 'min:1'],
            'satuan'           => ['required', 'string', 'max:50'],
        ]);

        $item->update([
            'nama_barang'     => $validated['nama_barang'],
            'spesifikasi'     => $validated['spesifikasi'] ?? null,
            'jumlah_diajukan' => $validated['jumlah_diajukan'],
            'satuan'          => $validated['satuan'],
        ]);

        return redirect()->route('pengajuan.show', $pengajuan)->with('success', "Item '{$item->nama_barang}' berhasil diperbarui.");
    }

    public function destroyItem(Pengajuan $pengajuan, PengajuanItem $item)
    {
        if ($pengajuan->items()->count() <= 1) {
            return redirect()->route('pengajuan.show', $pengajuan)->with('error', 'Pengajuan harus memiliki minimal 1 item barang.');
        }

        $namaBarang = $item->nama_barang;
        $item->delete();

        return redirect()->route('pengajuan.show', $pengajuan)->with('success', "Item '{$namaBarang}' berhasil dihapus dari pengajuan.");
    }

    public function updateKeputusan(Request $request, Pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:menunggu,disetujui,ditolak'],
            'tanggal_keputusan' => ['required', 'date'],
            'catatan_keputusan' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $pengajuan) {
            $pengajuan->update($validated);

            $pengajuan->load('items.laporanItem');
            foreach ($pengajuan->items as $pItem) {
                if ($pItem->laporanItem) {
                    $statusTeks = match ($validated['status']) {
                        'disetujui' => 'Disetujui oleh BIUM',
                        'ditolak'   => 'Ditolak oleh BIUM',
                        default     => ucfirst($validated['status']),
                    };

                    $deskripsi = "Keputusan BIUM ({$pengajuan->nomor_pengajuan}): Status {$statusTeks}.";
                    if (!empty($validated['catatan_keputusan'])) {
                        $deskripsi .= " Catatan: {$validated['catatan_keputusan']}";
                    }

                    $statusItem = $pItem->laporanItem->status_item;

                    PenangananLogger::log(
                        item: $pItem->laporanItem,
                        jenisTindakan: JenisTindakan::KEPUTUSAN_BIUM,
                        tahap: 'Keputusan BIUM',
                        tindakanAtauHasil: $deskripsi,
                        aktorPeran: 'Divisi BIUM / Admin: ' . (auth()->user()->name ?? 'Admin Lab'),
                        statusSebelum: $statusItem,
                        statusSesudah: $statusItem,
                        tanggal: $validated['tanggal_keputusan']
                    );
                }
            }
        });

        return redirect()->route('pengajuan.show', $pengajuan)->with('success', 'Status keputusan pengajuan berhasil diperbarui dan riwayat log telah dicatat.');
    }

    public function destroy(Pengajuan $pengajuan)
    {
        $pengajuan->delete();
        return redirect()->route('pengajuan.index')->with('success', 'Data pengajuan berhasil dihapus.');
    }
}
