<?php

namespace App\Http\Controllers;

use App\Constants\JabatanPelapor;
use App\Constants\JenisTindakan;
use App\Constants\Lab;
use App\Models\Laporan;
use App\Models\LaporanItem;
use App\Models\LaporanItemPenanganan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $sort = strtolower($request->input('sort', 'desc'));
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'desc';
        }

        $query = Laporan::with('items')
            ->orderBy('tanggal_laporan', $sort)
            ->orderBy('id', $sort);

        if ($request->filled('lab')) {
            $query->where('lab_nama', $request->lab);
        }

        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->whereNotIn('status', ['selesai', 'ditolak']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_laporan', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_laporan', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelapor', 'like', "%{$search}%")
                  ->orWhere('bagian', 'like', "%{$search}%")
                  ->orWhere('jabatan_pelapor', 'like', "%{$search}%")
                  ->orWhere('bentuk_ketidaksesuaian', 'like', "%{$search}%")
                  ->orWhere('uraian_pengamatan', 'like', "%{$search}%")
                  ->orWhere('tindakan_langsung', 'like', "%{$search}%")
                  ->orWhere('permintaan_tindakan', 'like', "%{$search}%")
                  ->orWhere('catatan_umum', 'like', "%{$search}%")
                  ->orWhereHas('items', function ($iq) use ($search) {
                      $iq->where('nama_barang', 'like', "%{$search}%")
                         ->orWhere('deskripsi', 'like', "%{$search}%");
                  });
            });
        }

        $laporans = $query->paginate(10)->withQueryString();
        $labs = Lab::list();

        return view('laporan.index', compact('laporans', 'labs', 'sort'));
    }

    public function create()
    {
        $labs = Lab::list();
        $jabatans = JabatanPelapor::list();
        return view('laporan.create', compact('labs', 'jabatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab_nama' => ['required', 'string'],
            'bentuk_ketidaksesuaian' => ['nullable', 'string', 'max:255'],
            'tanggal_kejadian' => ['nullable', 'date'],
            'nama_pelapor' => ['nullable', 'string', 'max:255'],
            'bagian' => ['nullable', 'string', 'max:100'],
            'jabatan_pelapor' => ['nullable', 'string', 'max:100'],
            'tanggal_laporan' => ['required', 'date'],
            'catatan_umum' => ['nullable', 'string'],
            'uraian_pengamatan' => ['nullable', 'string'],
            'tindakan_langsung' => ['nullable', 'string'],
            'permintaan_tindakan' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.nama_barang' => ['required', 'string', 'max:255'],
            'items.*.deskripsi' => ['nullable', 'string'],
            'items.*.jumlah' => ['required', 'integer', 'min:1'],
            'items.*.satuan' => ['required', 'string', 'max:50'],
            'items.*.jenis_tindak_lanjut' => ['required', 'in:pending,tindak_ulang,perbaikan,penggantian'],
            'items.*.keterangan' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $laporan = Laporan::create([
                'lab_nama' => $validated['lab_nama'],
                'bentuk_ketidaksesuaian' => $validated['bentuk_ketidaksesuaian'] ?? null,
                'tanggal_kejadian' => $validated['tanggal_kejadian'] ?? null,
                'nama_pelapor' => $validated['nama_pelapor'] ?? null,
                'bagian' => $validated['bagian'] ?? null,
                'jabatan_pelapor' => $validated['jabatan_pelapor'] ?? null,
                'tanggal_laporan' => $validated['tanggal_laporan'],
                'catatan_umum' => $validated['catatan_umum'] ?? null,
                'uraian_pengamatan' => $validated['uraian_pengamatan'] ?? null,
                'tindakan_langsung' => $validated['tindakan_langsung'] ?? null,
                'permintaan_tindakan' => $validated['permintaan_tindakan'] ?? null,
                'status' => 'dicatat',
            ]);

            $hasTriase = false;
            foreach ($validated['items'] as $itemData) {
                $statusItem = 'menunggu_triase';
                if ($itemData['jenis_tindak_lanjut'] === 'perbaikan' || $itemData['jenis_tindak_lanjut'] === 'tindak_ulang') {
                    $statusItem = 'proses_perbaikan';
                    $hasTriase = true;
                } elseif ($itemData['jenis_tindak_lanjut'] === 'penggantian') {
                    $statusItem = 'menunggu_pengadaan';
                    $hasTriase = true;
                }

                $laporanItem = LaporanItem::create([
                    'laporan_id' => $laporan->id,
                    'nama_barang' => $itemData['nama_barang'],
                    'deskripsi' => $itemData['deskripsi'] ?? null,
                    'jumlah' => $itemData['jumlah'],
                    'satuan' => $itemData['satuan'],
                    'jenis_tindak_lanjut' => $itemData['jenis_tindak_lanjut'],
                    'keterangan' => $itemData['keterangan'] ?? null,
                    'status_item' => $statusItem,
                ]);

                $pelaporLabel = ($validated['jabatan_pelapor'] ?? 'Laboran');
                if (!empty($validated['nama_pelapor'])) {
                    $pelaporLabel .= ': ' . $validated['nama_pelapor'];
                }

                $waktuInput = now();
                $waktuLaporan = !empty($validated['tanggal_laporan'])
                    ? \Carbon\Carbon::parse($validated['tanggal_laporan'])->setTime($waktuInput->hour, $waktuInput->minute, $waktuInput->second)
                    : $waktuInput;

                $waktuKejadian = !empty($validated['tanggal_kejadian'])
                    ? \Carbon\Carbon::parse($validated['tanggal_kejadian'])->setTime($waktuInput->hour, $waktuInput->minute, $waktuInput->second)
                    : $waktuLaporan;

                // 1. Auto-log: Laporan Masuk / Dicatat ke Sistem oleh Admin
                $deskripsiMasuk = 'Laporan permasalahan diinput ke sistem oleh Admin berdasarkan form laboratorium.';
                if (!empty($validated['uraian_pengamatan'])) {
                    $deskripsiMasuk .= ' Uraian pengamatan: ' . $validated['uraian_pengamatan'];
                }

                LaporanItemPenanganan::create([
                    'laporan_item_id'      => $laporanItem->id,
                    'tahap'                => 'Laporan Dicatat',
                    'jenis_tindakan'       => JenisTindakan::LAPORAN_DICATAT,
                    'aktor_peran'          => 'Admin: ' . (auth()->user()->name ?? 'Admin Lab'),
                    'tindakan_atau_hasil'  => $deskripsiMasuk,
                    'petugas'              => auth()->user()->name ?? 'Admin',
                    'status_sebelum'       => null,
                    'status_item_saat_itu' => 'menunggu_triase',
                    'tanggal_tindakan'     => $waktuLaporan,
                    'tanggal_waktu'        => $waktuLaporan,
                ]);

                // 2. Auto-log: Tindakan Langsung di Tempat oleh Laboran (jika diisi)
                if (!empty($validated['tindakan_langsung'])) {
                    LaporanItemPenanganan::create([
                        'laporan_item_id'      => $laporanItem->id,
                        'tahap'                => 'Tindakan Langsung (Laboran)',
                        'jenis_tindakan'       => JenisTindakan::TINDAKAN_LANGSUNG,
                        'aktor_peran'          => $pelaporLabel,
                        'tindakan_atau_hasil'  => $validated['tindakan_langsung'],
                        'petugas'              => $validated['nama_pelapor'] ?? 'Laboran',
                        'status_sebelum'       => 'menunggu_triase',
                        'status_item_saat_itu' => 'menunggu_triase',
                        'tanggal_tindakan'     => $waktuKejadian,
                        'tanggal_waktu'        => $waktuKejadian,
                    ]);
                }

                // 3. Auto-log: Permintaan Tindakan / Pengajuan Tindak Lanjut oleh Laboran (jika ada permintaan atau opsi tindak lanjut spesifik)
                if (!empty($validated['permintaan_tindakan']) || $itemData['jenis_tindak_lanjut'] !== 'pending') {
                    $catatanPengajuan = [];
                    if (!empty($validated['permintaan_tindakan'])) {
                        $catatanPengajuan[] = 'Permintaan tindakan: ' . $validated['permintaan_tindakan'];
                    }
                    if ($itemData['jenis_tindak_lanjut'] !== 'pending') {
                        $labelTindakLanjut = match ($itemData['jenis_tindak_lanjut']) {
                            'tindak_ulang' => 'Tindak Ulang Laboran',
                            'perbaikan'    => 'Perbaikan oleh Teknisi',
                            'penggantian'  => 'Penggantian / Pengadaan Barang',
                            default        => $itemData['jenis_tindak_lanjut'],
                        };
                        $catatanPengajuan[] = 'Usulan tindak lanjut item: ' . $labelTindakLanjut;
                    }
                    if (!empty($itemData['keterangan'])) {
                        $catatanPengajuan[] = 'Keterangan item: ' . $itemData['keterangan'];
                    }

                    LaporanItemPenanganan::create([
                        'laporan_item_id'      => $laporanItem->id,
                        'tahap'                => 'Pengajuan Tindak Lanjut oleh Laboran',
                        'jenis_tindakan'       => JenisTindakan::DITERUSKAN_KOORDINATOR,
                        'aktor_peran'          => $pelaporLabel,
                        'tindakan_atau_hasil'  => implode('. ', $catatanPengajuan),
                        'petugas'              => $validated['nama_pelapor'] ?? 'Laboran',
                        'status_sebelum'       => 'menunggu_triase',
                        'status_item_saat_itu' => $statusItem,
                        'tanggal_tindakan'     => $waktuLaporan,
                        'tanggal_waktu'        => $waktuLaporan,
                    ]);
                }
            }

            if ($hasTriase) {
                $laporan->status = 'ditriase';
                $laporan->save();
            }
        });

        return redirect()->route('laporan.index')->with('success', 'Laporan permasalahan laboratorium berhasil dicatat beserta riwayat log awal.');
    }

    public function show(Laporan $laporan)
    {
        $laporan->load([
            'items.penanganans',
            'items.pengajuanItems.pengajuan',
            'items.distribusis.stokGudang.barangMaster'
        ]);
        $labs = Lab::list();
        $jenisTindakanList = JenisTindakan::labels();
        $manualJenisTindakanList = JenisTindakan::manualInputLabels();
        return view('laporan.show', compact('laporan', 'labs', 'jenisTindakanList', 'manualJenisTindakanList'));
    }

    public function edit(Laporan $laporan)
    {
        $laporan->load('items');
        $labs = Lab::list();
        $jabatans = JabatanPelapor::list();
        return view('laporan.edit', compact('laporan', 'labs', 'jabatans'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        $validated = $request->validate([
            'lab_nama' => ['required', 'string'],
            'bentuk_ketidaksesuaian' => ['nullable', 'string', 'max:255'],
            'tanggal_kejadian' => ['nullable', 'date'],
            'nama_pelapor' => ['nullable', 'string', 'max:255'],
            'bagian' => ['nullable', 'string', 'max:100'],
            'jabatan_pelapor' => ['nullable', 'string', 'max:100'],
            'tanggal_laporan' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'status' => ['required', 'string'],
            'status_akhir' => ['nullable', 'in:closed,follow_up'],
            'catatan_umum' => ['nullable', 'string'],
            'uraian_pengamatan' => ['nullable', 'string'],
            'tindakan_langsung' => ['nullable', 'string'],
            'permintaan_tindakan' => ['nullable', 'string'],
        ]);

        $laporan->update($validated);

        return redirect()->route('laporan.show', $laporan)->with('success', 'Data laporan berhasil diperbarui.');
    }

    public function updateItem(Request $request, Laporan $laporan, LaporanItem $item)
    {
        $validated = $request->validate([
            'nama_barang'         => ['sometimes', 'required', 'string', 'max:255'],
            'jumlah'              => ['sometimes', 'required', 'integer', 'min:1'],
            'satuan'              => ['sometimes', 'required', 'string', 'max:50'],
            'deskripsi'           => ['nullable', 'string'],
            'jenis_tindak_lanjut' => ['required', 'in:pending,tindak_ulang,perbaikan,penggantian'],
            'keterangan'          => ['nullable', 'string'],
            'status_item'         => ['required', 'in:menunggu_triase,proses_perbaikan,selesai_perbaikan,menunggu_pengadaan,terdistribusi,selesai'],
        ]);

        $item->update($validated);
        $laporan->updateAutoStatus();

        return redirect()->route('laporan.show', $laporan)->with('success', 'Data item permasalahan berhasil diperbarui.');
    }

    public function destroyItem(Laporan $laporan, LaporanItem $item)
    {
        DB::transaction(function () use ($laporan, $item) {
            $item->penanganans()->delete();
            $item->delete();
            $laporan->updateAutoStatus();
        });

        return redirect()->route('laporan.show', $laporan)->with('success', 'Item permasalahan berhasil dihapus dari laporan.');
    }

    public function konfirmasiSelesaiItem(Laporan $laporan, LaporanItem $item)
    {
        // Hanya bisa dikonfirmasi jika statusnya 'terdistribusi'
        if ($item->status_item !== 'terdistribusi') {
            return redirect()->route('laporan.show', $laporan)
                ->with('error', 'Item ini tidak dalam status menunggu konfirmasi laboran.');
        }

        DB::transaction(function () use ($laporan, $item) {
            $statusSebelum = $item->status_item;

            $item->update(['status_item' => 'selesai']);

            // Catat log konfirmasi laboran
            $item->penanganans()->create([
                'tahap'                => 'Konfirmasi Laboran — Barang Berfungsi Baik',
                'jenis_tindakan'       => \App\Constants\JenisTindakan::KONFIRMASI_LABORAN,
                'aktor_peran'          => 'Admin Lab: ' . (auth()->user()->name ?? 'Admin'),
                'tindakan_atau_hasil'  => 'Laboran telah memverifikasi barang berfungsi dengan baik di lapangan. Item ditutup dan laporan diperbarui.',
                'petugas'              => auth()->user()->name ?? 'Admin',
                'status_sebelum'       => $statusSebelum,
                'status_item_saat_itu' => 'selesai',
                'tanggal_tindakan'     => now(),
                'tanggal_hasil'        => now(),
                'tanggal_waktu'        => now(),
            ]);

            $laporan->updateAutoStatus();
        });

        return redirect()->route('laporan.show', $laporan)
            ->with('success', 'Konfirmasi laboran berhasil dicatat. Item ditutup dan status laporan diperbarui.');
    }

    public function storePenanganan(Request $request, Laporan $laporan, LaporanItem $item)
    {
        $validated = $request->validate([
            'tahap'               => ['required', 'string', 'max:255'],
            'jenis_tindakan'      => ['nullable', 'string', 'max:100'],
            'aktor_peran'         => ['nullable', 'string', 'max:100'],
            'tindakan_atau_hasil' => ['required', 'string'],
            'tanggal_tindakan'    => ['nullable', 'date'],
            'tanggal_hasil'       => ['nullable', 'date'],
            'petugas'             => ['nullable', 'string', 'max:255'],
            'status_item'         => ['nullable', 'in:menunggu_triase,proses_perbaikan,selesai_perbaikan,menunggu_pengadaan,terdistribusi,selesai'],
            'jenis_tindak_lanjut' => ['nullable', 'in:pending,tindak_ulang,perbaikan,penggantian'],
        ]);

        DB::transaction(function () use ($validated, $laporan, $item) {
            $statusSebelum = $item->status_item;
            $currentStatus = $validated['status_item'] ?? $item->status_item;

            $item->penanganans()->create([
                'tahap'                => $validated['tahap'],
                'jenis_tindakan'       => $validated['jenis_tindakan'] ?? JenisTindakan::LAINNYA,
                'aktor_peran'          => $validated['aktor_peran'] ?? ($validated['petugas'] ?? auth()->user()->name ?? 'Admin Lab'),
                'tindakan_atau_hasil'  => $validated['tindakan_atau_hasil'],
                'petugas'              => $validated['petugas'] ?? auth()->user()->name ?? 'Admin',
                'status_sebelum'       => $statusSebelum,
                'status_item_saat_itu' => $currentStatus,
                'tanggal_tindakan'     => $validated['tanggal_tindakan'] ?? now(),
                'tanggal_hasil'        => $validated['tanggal_hasil'] ?? null,
                'tanggal_waktu'        => $validated['tanggal_tindakan'] ?? now(),
            ]);

            $updateData = [];
            if (!empty($validated['status_item'])) {
                $updateData['status_item'] = $validated['status_item'];
                if (empty($validated['jenis_tindak_lanjut'])) {
                    if (in_array($validated['status_item'], ['proses_perbaikan', 'selesai_perbaikan'])) {
                        $updateData['jenis_tindak_lanjut'] = 'perbaikan';
                    } elseif (in_array($validated['status_item'], ['menunggu_pengadaan', 'terdistribusi'])) {
                        $updateData['jenis_tindak_lanjut'] = 'penggantian';
                    } elseif ($validated['status_item'] === 'menunggu_triase') {
                        $updateData['jenis_tindak_lanjut'] = 'pending';
                    }
                }
            }
            if (!empty($validated['jenis_tindak_lanjut'])) {
                $updateData['jenis_tindak_lanjut'] = $validated['jenis_tindak_lanjut'];
            }

            if (!empty($updateData)) {
                $item->update($updateData);
                $laporan->updateAutoStatus();
            }
        });

        return redirect()->route('laporan.show', $laporan)->with('success', 'Catatan langkah tindakan & hasil berhasil ditambahkan ke riwayat log.');
    }

    public function updatePenanganan(Request $request, Laporan $laporan, LaporanItem $item, LaporanItemPenanganan $penanganan)
    {
        $validated = $request->validate([
            'tahap'               => ['required', 'string', 'max:255'],
            'jenis_tindakan'      => ['nullable', 'string', 'max:100'],
            'aktor_peran'         => ['nullable', 'string', 'max:100'],
            'tindakan_atau_hasil' => ['required', 'string'],
            'tanggal_tindakan'    => ['nullable', 'date'],
            'tanggal_hasil'       => ['nullable', 'date'],
            'petugas'             => ['nullable', 'string', 'max:255'],
            'status_item'         => ['nullable', 'in:menunggu_triase,proses_perbaikan,selesai_perbaikan,menunggu_pengadaan,terdistribusi,selesai'],
            'jenis_tindak_lanjut' => ['nullable', 'in:pending,tindak_ulang,perbaikan,penggantian'],
        ]);

        DB::transaction(function () use ($validated, $laporan, $item, $penanganan) {
            $currentStatus = $validated['status_item'] ?? $item->status_item;

            $penanganan->update([
                'tahap'                => $validated['tahap'],
                'jenis_tindakan'       => $validated['jenis_tindakan'] ?? $penanganan->jenis_tindakan,
                'aktor_peran'          => $validated['aktor_peran'] ?? $penanganan->aktor_peran,
                'tindakan_atau_hasil'  => $validated['tindakan_atau_hasil'],
                'petugas'              => $validated['petugas'] ?? $penanganan->petugas,
                'status_item_saat_itu' => $currentStatus,
                'tanggal_tindakan'     => $validated['tanggal_tindakan'] ?? $penanganan->tanggal_tindakan ?? $penanganan->tanggal_waktu,
                'tanggal_hasil'        => $validated['tanggal_hasil'] ?? $penanganan->tanggal_hasil,
            ]);

            $updateData = [];
            if (!empty($validated['status_item'])) {
                $updateData['status_item'] = $validated['status_item'];
                if (empty($validated['jenis_tindak_lanjut'])) {
                    if (in_array($validated['status_item'], ['proses_perbaikan', 'selesai_perbaikan'])) {
                        $updateData['jenis_tindak_lanjut'] = 'perbaikan';
                    } elseif (in_array($validated['status_item'], ['menunggu_pengadaan', 'terdistribusi'])) {
                        $updateData['jenis_tindak_lanjut'] = 'penggantian';
                    } elseif ($validated['status_item'] === 'menunggu_triase') {
                        $updateData['jenis_tindak_lanjut'] = 'pending';
                    }
                }
            }
            if (!empty($validated['jenis_tindak_lanjut'])) {
                $updateData['jenis_tindak_lanjut'] = $validated['jenis_tindak_lanjut'];
            }

            if (!empty($updateData)) {
                $item->update($updateData);
                $laporan->updateAutoStatus();
            }
        });

        return redirect()->route('laporan.show', $laporan)->with('success', 'Langkah tindakan & hasil berhasil diperbarui.');
    }

    public function destroyPenanganan(Laporan $laporan, LaporanItem $item, LaporanItemPenanganan $penanganan)
    {
        $penanganan->delete();
        $laporan->updateAutoStatus();

        return redirect()->route('laporan.show', $laporan)->with('success', 'Catatan langkah penanganan berhasil dihapus.');
    }

    public function destroy(Laporan $laporan)
    {
        $laporan->delete();
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
