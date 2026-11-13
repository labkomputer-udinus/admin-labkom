<?php

namespace App\Http\Controllers;

use App\Models\BarangMaster;
use App\Models\StokGudang;
use App\Models\StokTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    public const KATEGORIS = [
        'Komponen PC',
        'Peripheral',
        'Jaringan',
        'Sarana & Prasarana',
        'Lainnya',
    ];

    public function index(Request $request)
    {
        $query = StokGudang::with('barangMaster')
            ->join('barang_masters', 'stok_gudangs.barang_master_id', '=', 'barang_masters.id')
            ->orderBy('barang_masters.nama_barang', 'asc')
            ->select('stok_gudangs.*');

        if ($request->filled('kategori')) {
            $query->where('barang_masters.kategori', $request->kategori);
        }

        if ($request->filled('status_stok')) {
            if ($request->status_stok === 'kritis') {
                $query->whereColumn('stok_gudangs.stok_saat_ini', '<=', 'barang_masters.stok_minimum');
            } elseif ($request->status_stok === 'aman') {
                $query->whereColumn('stok_gudangs.stok_saat_ini', '>', 'barang_masters.stok_minimum');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('barang_masters.nama_barang', 'like', "%{$search}%")
                  ->orWhere('barang_masters.spesifikasi', 'like', "%{$search}%");
            });
        }

        $stoks = $query->paginate(12)->withQueryString();

        $kategoris = collect(self::KATEGORIS)
            ->merge(BarangMaster::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori'))
            ->unique()
            ->values();

        $masterNamaList = BarangMaster::select('nama_barang')->distinct()->orderBy('nama_barang')->pluck('nama_barang');
        $masterSpesifikasiList = BarangMaster::select('spesifikasi')->whereNotNull('spesifikasi')->where('spesifikasi', '!=', '')->distinct()->orderBy('spesifikasi')->pluck('spesifikasi');

        return view('gudang.index', compact('stoks', 'kategoris', 'masterNamaList', 'masterSpesifikasiList'));
    }

    public function searchBarang(Request $request)
    {
        $term = trim($request->get('q', ''));
        $query = BarangMaster::with('stokGudang');

        if (!empty($term)) {
            $query->where(function ($q) use ($term) {
                $q->where('nama_barang', 'like', "%{$term}%")
                  ->orWhere('spesifikasi', 'like', "%{$term}%")
                  ->orWhere('kategori', 'like', "%{$term}%");
            });
        }

        $results = $query->orderBy('nama_barang', 'asc')
            ->limit(7)
            ->get()
            ->map(function ($b) {
                return [
                    'id' => $b->id,
                    'nama_barang' => $b->nama_barang,
                    'spesifikasi' => $b->spesifikasi ?? '',
                    'nama_lengkap' => $b->nama_lengkap,
                    'kategori' => $b->kategori ?? 'Umum',
                    'satuan' => $b->satuan ?? 'unit',
                    'stok_saat_ini' => $b->stokGudang?->stok_saat_ini ?? 0,
                    'catatan' => $b->stokGudang?->catatan ?? '',
                ];
            });

        return response()->json($results);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => ['required', 'string', 'max:255'],
            'spesifikasi' => ['nullable', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'satuan' => ['required', 'string', 'max:50'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'stok_awal' => ['required', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ]);

        $spekInput = !empty($validated['spesifikasi']) ? trim($validated['spesifikasi']) : null;

        // Cek kombinasi nama_barang + spesifikasi agar tidak duplikat persis
        $duplicate = BarangMaster::where('nama_barang', trim($validated['nama_barang']))
            ->where(function ($q) use ($spekInput) {
                if (empty($spekInput)) {
                    $q->whereNull('spesifikasi')->orWhere('spesifikasi', '');
                } else {
                    $q->where('spesifikasi', $spekInput);
                }
            })->exists();

        if ($duplicate) {
            $labelDuplikat = $spekInput ? "{$validated['nama_barang']} - {$spekInput}" : $validated['nama_barang'];
            return back()->withInput()->withErrors([
                'nama_barang' => "Barang \"{$labelDuplikat}\" sudah ada di gudang. Silakan lakukan penyesuaian stok atau gunakan spesifikasi yang berbeda."
            ]);
        }

        DB::transaction(function () use ($validated, $spekInput) {
            $barang = BarangMaster::create([
                'nama_barang' => trim($validated['nama_barang']),
                'spesifikasi' => $spekInput,
                'kategori' => $validated['kategori'],
                'satuan' => $validated['satuan'],
                'stok_minimum' => $validated['stok_minimum'],
            ]);

            $stok = StokGudang::create([
                'barang_master_id' => $barang->id,
                'stok_saat_ini' => $validated['stok_awal'],
                'catatan' => $validated['catatan'] ?? null,
            ]);

            if ($validated['stok_awal'] > 0) {
                StokTransaksi::create([
                    'stok_gudang_id' => $stok->id,
                    'jenis' => 'masuk',
                    'jumlah' => $validated['stok_awal'],
                    'referensi_type' => 'manual',
                    'referensi_id' => null,
                    'keterangan' => 'Pencatatan stok awal barang baru',
                    'tanggal_transaksi' => now()->toDateString(),
                ]);
            }
        });

        return redirect()->route('gudang.index')->with('success', 'Barang baru berhasil didaftarkan ke gudang.');
    }

    public function show(StokGudang $stok)
    {
        $stok->load(['barangMaster', 'transaksis' => fn($q) => $q->latest('tanggal_transaksi')->latest('id')]);
        $kategoris = collect(self::KATEGORIS)
            ->merge(BarangMaster::select('kategori')->distinct()->whereNotNull('kategori')->pluck('kategori'))
            ->unique()
            ->values();

        $masterNamaList = BarangMaster::select('nama_barang')->distinct()->orderBy('nama_barang')->pluck('nama_barang');
        $masterSpesifikasiList = BarangMaster::select('spesifikasi')->whereNotNull('spesifikasi')->where('spesifikasi', '!=', '')->distinct()->orderBy('spesifikasi')->pluck('spesifikasi');

        return view('gudang.show', compact('stok', 'kategoris', 'masterNamaList', 'masterSpesifikasiList'));
    }

    public function mutasi(StokGudang $stok)
    {
        return $this->show($stok);
    }

    public function update(Request $request, StokGudang $stok)
    {
        $validated = $request->validate([
            'nama_barang' => ['required', 'string', 'max:255'],
            'spesifikasi' => ['nullable', 'string', 'max:255'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'satuan' => ['required', 'string', 'max:50'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ]);

        $spekInput = !empty($validated['spesifikasi']) ? trim($validated['spesifikasi']) : null;

        // Cek kombinasi nama_barang + spesifikasi pada record lain
        $duplicate = BarangMaster::where('id', '!=', $stok->barang_master_id)
            ->where('nama_barang', trim($validated['nama_barang']))
            ->where(function ($q) use ($spekInput) {
                if (empty($spekInput)) {
                    $q->whereNull('spesifikasi')->orWhere('spesifikasi', '');
                } else {
                    $q->where('spesifikasi', $spekInput);
                }
            })->exists();

        if ($duplicate) {
            $labelDuplikat = $spekInput ? "{$validated['nama_barang']} - {$spekInput}" : $validated['nama_barang'];
            return back()->withInput()->withErrors([
                'nama_barang' => "Barang lain dengan nama dan spesifikasi \"{$labelDuplikat}\" sudah ada di gudang."
            ]);
        }

        $stok->barangMaster->update([
            'nama_barang' => trim($validated['nama_barang']),
            'spesifikasi' => $spekInput,
            'kategori' => $validated['kategori'],
            'satuan' => $validated['satuan'],
            'stok_minimum' => $validated['stok_minimum'],
        ]);

        $stok->update([
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('gudang.show', $stok)->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy(StokGudang $stok)
    {
        $namaBarang = $stok->barangMaster?->nama_barang ?? 'Barang';

        DB::transaction(function () use ($stok) {
            $barangMaster = $stok->barangMaster;
            if ($barangMaster) {
                $barangMaster->delete();
            } else {
                $stok->delete();
            }
        });

        return redirect()->route('gudang.index')->with('success', "Barang '{$namaBarang}' berhasil dihapus dari inventaris gudang.");
    }

    public function sesuaikanStok(Request $request, StokGudang $stok)
    {
        $validated = $request->validate([
            'jenis' => ['required', 'in:masuk,keluar'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['required', 'string'],
            'tanggal_transaksi' => ['required', 'date'],
        ]);

        if ($validated['jenis'] === 'keluar' && $stok->stok_saat_ini < $validated['jumlah']) {
            return back()->withErrors(['jumlah' => 'Jumlah keluar melebihi stok yang tersedia saat ini (' . $stok->stok_saat_ini . ').']);
        }

        DB::transaction(function () use ($validated, $stok) {
            if ($validated['jenis'] === 'masuk') {
                $stok->increment('stok_saat_ini', $validated['jumlah']);
            } else {
                $stok->decrement('stok_saat_ini', $validated['jumlah']);
            }

            StokTransaksi::create([
                'stok_gudang_id' => $stok->id,
                'jenis' => $validated['jenis'],
                'jumlah' => $validated['jumlah'],
                'referensi_type' => 'manual',
                'referensi_id' => null,
                'keterangan' => $validated['keterangan'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
            ]);
        });

        return redirect()->route('gudang.show', $stok)->with('success', 'Penyesuaian stok berhasil dicatat.');
    }
}
