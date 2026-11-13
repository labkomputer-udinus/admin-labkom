<?php

namespace App\Http\Controllers;

use App\Constants\JenisTindakan;
use App\Constants\Lab;
use App\Models\Distribusi;
use App\Models\LaporanItem;
use App\Models\PengajuanItem;
use App\Models\StokGudang;
use App\Models\StokTransaksi;
use App\Services\PenangananLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DistribusiController extends Controller
{
    public function index(Request $request)
    {
        $query = Distribusi::with([
            'laporanItem.laporan',
            'stokGudang.barangMaster',
            'pengajuanItem.pengajuan',
        ])->latest('tanggal_distribusi')->latest('id');

        if ($request->filled('lab')) {
            $query->where('lab_tujuan', $request->lab);
        }

        if ($request->filled('asal_barang')) {
            $query->where('asal_barang', $request->asal_barang);
        }

        if ($request->filled('jenis_distribusi')) {
            $query->where('jenis_distribusi', $request->jenis_distribusi);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_distribusi', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_distribusi', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_pc_tujuan', 'like', "%{$search}%")
                  ->orWhere('diterima_oleh', 'like', "%{$search}%")
                  ->orWhereHas('stokGudang.barangMaster', fn($bq) => $bq->where('nama_barang', 'like', "%{$search}%"));
            });
        }

        $distribusis = $query->paginate(12)->withQueryString();
        $labs = Lab::list();

        return view('distribusi.index', compact('distribusis', 'labs'));
    }

    public function create(Request $request)
    {
        $selectedItemId = $request->query('laporan_item_id');
        $selectedStokId = $request->query('stok_gudang_id');

        $pendingItems = LaporanItem::with(['laporan', 'distribusis'])
            ->whereIn('status_item', ['menunggu_pengadaan', 'menunggu_triase', 'proses_perbaikan'])
            ->get()
            ->map(function ($item) {
                $terdistribusi = (int) $item->distribusis->sum('jumlah');
                $item->sudah_terdistribusi = $terdistribusi;
                $item->sisa_kebutuhan = max(0, (int) $item->jumlah - $terdistribusi);
                return $item;
            })
            ->filter(fn($item) => $item->sisa_kebutuhan > 0)
            ->values();

        $stoks = StokGudang::with('barangMaster')->where('stok_saat_ini', '>', 0)->get();
        $pengajuanItems = PengajuanItem::with('pengajuan')
            ->whereIn('status_kesesuaian', ['sesuai', 'sesuai_beda_spesifikasi'])
            ->where('jumlah_diterima', '>', 0)
            ->get();

        $labs = Lab::list();

        $kategoriAlasanList = [
            'Upgrade Perangkat Lab',
            'Pengisian Awal / Lab Baru',
            'Pemeliharaan & Perawatan Rutin',
            'Penggantian Preventif',
            'Lainnya',
        ];

        // Mode awal: jika berasal dari link tombol laporan -> 'laporan', jika dari tombol gudang tanpa laporan -> 'mandiri'
        $initialMode = $selectedItemId ? 'laporan' : ($selectedStokId ? 'mandiri' : 'laporan');

        return view('distribusi.create', compact(
            'pendingItems',
            'stoks',
            'pengajuanItems',
            'labs',
            'selectedItemId',
            'selectedStokId',
            'kategoriAlasanList',
            'initialMode'
        ));
    }

    public function store(Request $request)
    {
        $isMandiri = $request->input('jenis_distribusi') === 'mandiri';

        $validated = $request->validate([
            'jenis_distribusi' => ['required', 'in:laporan,mandiri'],
            'laporan_item_id' => [
                $isMandiri ? 'nullable' : 'required',
                'nullable',
                'exists:laporan_items,id',
            ],
            'stok_gudang_id' => ['required', 'exists:stok_gudangs,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'asal_barang' => ['required', 'in:pengajuan,stok_lama'],
            'pengajuan_item_id' => ['nullable', 'exists:pengajuan_items,id'],
            'lab_tujuan' => ['required', 'string'],
            'kode_pc_tujuan' => ['nullable', 'string', 'max:255'],
            'kategori_alasan' => [
                $isMandiri ? 'required' : 'nullable',
                'nullable',
                'string',
                'max:100',
            ],
            'kategori_alasan_kustom' => ['nullable', 'string', 'max:100'],
            'alasan_distribusi' => ['nullable', 'string', 'max:500'],
            'diterima_oleh' => ['nullable', 'string', 'max:255'],
            'tanggal_distribusi' => ['required', 'date'],
            'tanggal_terima_lab' => ['nullable', 'date'],
        ]);

        // Jika kategori 'Lainnya' dipilih dan ada input kustom, gunakan teks kustom
        if ($isMandiri) {
            $kat = $validated['kategori_alasan'] ?? '';
            if ($kat === 'Lainnya' && !empty($request->input('kategori_alasan_kustom'))) {
                $validated['kategori_alasan'] = trim($request->input('kategori_alasan_kustom'));
            }
        } else {
            $validated['kategori_alasan'] = null;
            $validated['alasan_distribusi'] = null;
        }

        $stok = StokGudang::with('barangMaster')->findOrFail($validated['stok_gudang_id']);

        if ($stok->stok_saat_ini < $validated['jumlah']) {
            return back()->withInput()->withErrors([
                'jumlah' => "Stok {$stok->barangMaster->nama_barang} tidak mencukupi (Tersedia: {$stok->stok_saat_ini}, Diminta: {$validated['jumlah']})."
            ]);
        }

        $laporanItem = null;
        $sudahTerkirim = 0;

        if (!$isMandiri) {
            $laporanItem = LaporanItem::with(['laporan', 'distribusis'])->findOrFail($validated['laporan_item_id']);
            $sudahTerkirim = (int) $laporanItem->distribusis->sum('jumlah');
            $sisaKebutuhan = max(0, (int) $laporanItem->jumlah - $sudahTerkirim);

            if ($validated['jumlah'] > $sisaKebutuhan) {
                return back()->withInput()->withErrors([
                    'jumlah' => "Jumlah distribusi ({$validated['jumlah']} {$laporanItem->satuan}) melebihi sisa kebutuhan ({$sisaKebutuhan} {$laporanItem->satuan})."
                ]);
            }
        }

        $waktuDistribusi = !empty($validated['tanggal_distribusi'])
            ? \Carbon\Carbon::parse($validated['tanggal_distribusi'])
            : now();

        if (is_string($validated['tanggal_distribusi']) && strlen(trim($validated['tanggal_distribusi'])) <= 10) {
            $waktuDistribusi->setTime(now()->hour, now()->minute, now()->second);
        }

        $validated['tanggal_distribusi'] = $waktuDistribusi;

        DB::transaction(function () use ($validated, $stok, $laporanItem, $sudahTerkirim, $waktuDistribusi, $isMandiri) {
            $distribusi = Distribusi::create($validated);

            // Kurangi stok gudang
            $stok->decrement('stok_saat_ini', $validated['jumlah']);

            $unitText = !empty($validated['kode_pc_tujuan']) ? " ({$validated['kode_pc_tujuan']})" : "";

            if ($isMandiri) {
                $katLabel = !empty($validated['kategori_alasan']) ? " [{$validated['kategori_alasan']}]" : "";
                $alasanDetail = !empty($validated['alasan_distribusi']) ? " - {$validated['alasan_distribusi']}" : "";
                $keteranganTransaksi = "Distribusi Mandiri{$katLabel} ke Lab {$validated['lab_tujuan']}{$unitText}{$alasanDetail}";
            } else {
                $keteranganTransaksi = "Distribusi ke Lab {$validated['lab_tujuan']}{$unitText}";
            }

            // Catat stok transaksi
            StokTransaksi::create([
                'stok_gudang_id' => $stok->id,
                'jenis' => 'keluar',
                'jumlah' => $validated['jumlah'],
                'referensi_type' => 'distribusi',
                'referensi_id' => $distribusi->id,
                'keterangan' => $keteranganTransaksi,
                'tanggal_transaksi' => $waktuDistribusi,
            ]);

            // Jika distribusi terikat item laporan, perbarui status item & rekam jejak audit
            if (!$isMandiri && $laporanItem) {
                $totalTerkirim = $sudahTerkirim + $validated['jumlah'];
                $isLengkap = $totalTerkirim >= (int) $laporanItem->jumlah;
                $prevStatus = $laporanItem->status_item;
                $statusSesudah = $isLengkap ? 'terdistribusi' : $prevStatus;

                $sisaTersisa = max(0, (int) $laporanItem->jumlah - $totalTerkirim);
                $formatWaktu = $waktuDistribusi->format('d/m/Y H:i');
                $catatanProgres = $isLengkap
                    ? "Didistribusikan {$validated['jumlah']} {$laporanItem->satuan} ke {$validated['lab_tujuan']}{$unitText} tgl {$formatWaktu} (Total terpenuhi: {$totalTerkirim}/{$laporanItem->jumlah} {$laporanItem->satuan})."
                    : "Didistribusikan sebagian: {$validated['jumlah']} {$laporanItem->satuan} ke {$validated['lab_tujuan']}{$unitText} tgl {$formatWaktu} (Progres: {$totalTerkirim}/{$laporanItem->jumlah} {$laporanItem->satuan}, Sisa: {$sisaTersisa} {$laporanItem->satuan}).";

                $laporanItem->update([
                    'status_item' => $statusSesudah,
                    'keterangan' => ($laporanItem->keterangan ? $laporanItem->keterangan . "\n" : '') . $catatanProgres,
                ]);

                // Auto-log Distribusi Barang ke Lab
                $asalTeks = ($validated['asal_barang'] === 'pengajuan') ? 'Pengadaan BIUM' : 'Stok Gudang Lab';
                $penerimaInfo = !empty($validated['diterima_oleh']) ? ". Diterima oleh: {$validated['diterima_oleh']}" : "";

                $deskripsiDistribusi = $isLengkap
                    ? "Barang ({$stok->barangMaster->nama_barang}) sebanyak {$validated['jumlah']} {$laporanItem->satuan} didistribusikan ke Lab {$validated['lab_tujuan']}{$unitText}. Pengiriman tuntas ({$totalTerkirim}/{$laporanItem->jumlah} {$laporanItem->satuan}). Asal barang: {$asalTeks}{$penerimaInfo}."
                    : "Distribusi Sebagian: Barang ({$stok->barangMaster->nama_barang}) sebanyak {$validated['jumlah']} {$laporanItem->satuan} didistribusikan ke Lab {$validated['lab_tujuan']}{$unitText}. Baru terkirim {$totalTerkirim} dari {$laporanItem->jumlah} {$laporanItem->satuan} (Sisa {$sisaTersisa} {$laporanItem->satuan} belum terdistribusi). Asal barang: {$asalTeks}{$penerimaInfo}.";

                PenangananLogger::log(
                    item: $laporanItem,
                    jenisTindakan: JenisTindakan::DISTRIBUSI_BARANG,
                    tahap: $isLengkap ? 'Distribusi Barang ke Lab (Tuntas)' : 'Distribusi Sebagian ke Lab',
                    tindakanAtauHasil: $deskripsiDistribusi,
                    aktorPeran: 'Petugas Distribusi: ' . (auth()->user()->name ?? 'Admin Lab'),
                    statusSebelum: $prevStatus,
                    statusSesudah: $statusSesudah,
                    tanggal: $waktuDistribusi
                );

                // Update status laporan induk
                $laporan = $laporanItem->laporan;
                if ($laporan) {
                    $laporan->updateAutoStatus();
                }
            }
        });

        return redirect()->route('distribusi.index')->with('success', 'Barang berhasil didistribusikan ke laboratorium tujuan dan riwayat log penanganan telah diperbarui.');
    }

    /**
     * Konfirmasi bahwa barang sudah diterima oleh lab tujuan.
     * Mengisi tanggal_terima_lab dengan tanggal hari ini (atau tanggal custom).
     */
    public function konfirmasiTerima(Request $request, Distribusi $distribusi)
    {
        $validated = $request->validate([
            'tanggal_terima_lab' => ['nullable', 'date'],
        ]);

        $tanggal = !empty($validated['tanggal_terima_lab'])
            ? Carbon::parse($validated['tanggal_terima_lab'])
            : Carbon::today();

        $distribusi->update([
            'tanggal_terima_lab' => $tanggal,
        ]);

        $namaBarang = $distribusi->stokGudang?->barangMaster?->nama_barang ?? 'Barang';
        $labTujuan  = $distribusi->lab_tujuan;

        return redirect()->route('distribusi.index')
            ->with('success', "Konfirmasi terima berhasil dicatat. {$namaBarang} telah diterima oleh Lab {$labTujuan} pada " . $tanggal->translatedFormat('d F Y') . '.');
    }

    /**
     * Hapus data distribusi (hanya jika belum dikonfirmasi terima).
     */
    public function destroy(Distribusi $distribusi)
    {
        if ($distribusi->tanggal_terima_lab) {
            return redirect()->route('distribusi.index')
                ->with('error', 'Data distribusi yang sudah dikonfirmasi terima tidak dapat dihapus.');
        }

        DB::transaction(function () use ($distribusi) {
            // Kembalikan stok
            $distribusi->stokGudang?->increment('stok_saat_ini', $distribusi->jumlah);

            // Hapus transaksi stok terkait
            StokTransaksi::where('referensi_type', 'distribusi')
                ->where('referensi_id', $distribusi->id)
                ->delete();

            // Kembalikan status laporan item jika perlu
            $laporanItem = $distribusi->laporanItem;
            if ($laporanItem && $laporanItem->status_item === 'terdistribusi') {
                $totalSisaSetelahHapus = (int)$laporanItem->distribusis->where('id', '!=', $distribusi->id)->sum('jumlah');
                if ($totalSisaSetelahHapus < (int)$laporanItem->jumlah) {
                    $laporanItem->update(['status_item' => 'menunggu_pengadaan']);
                }
            }

            $distribusi->delete();
        });

        return redirect()->route('distribusi.index')
            ->with('success', 'Data distribusi berhasil dihapus dan stok gudang telah dikembalikan.');
    }
}
