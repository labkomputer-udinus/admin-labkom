<?php

namespace App\Http\Controllers;

use App\Constants\Lab;
use App\Constants\StatusBarangTertinggal;
use App\Models\BarangTertinggal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarangTertinggalController extends Controller
{
    public function index(Request $request): View
    {
        $query = BarangTertinggal::query();

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('penemu', 'like', "%{$search}%")
                  ->orWhere('pemilik', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('lab_nama', 'like', "%{$search}%");
            });
        }

        // Filter Lab
        if ($lab = $request->input('lab')) {
            $query->where('lab_nama', $lab);
        }

        // Filter Status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter Tanggal Ditemukan
        if ($startDate = $request->input('start_date')) {
            $query->whereDate('tanggal_ditemukan', '>=', $startDate);
        }

        if ($endDate = $request->input('end_date')) {
            $query->whereDate('tanggal_ditemukan', '<=', $endDate);
        }

        // Stats for summary cards
        $stats = [
            'total' => BarangTertinggal::count(),
            'belum_diambil' => BarangTertinggal::where('status', StatusBarangTertinggal::BELUM_DIAMBIL)->count(),
            'diumumkan' => BarangTertinggal::where('status', StatusBarangTertinggal::DIUMUMKAN)->count(),
            'sudah_diambil' => BarangTertinggal::where('status', StatusBarangTertinggal::SUDAH_DIAMBIL)->count(),
            'diserahkan_ke_satpam' => BarangTertinggal::where('status', StatusBarangTertinggal::DISERAHKAN_KE_SATPAM)->count(),
        ];

        $barangTertinggals = $query->orderBy('tanggal_ditemukan', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $labs = Lab::list();
        $statuses = StatusBarangTertinggal::labels();

        return view('barang_tertinggal.index', compact(
            'barangTertinggals',
            'stats',
            'labs',
            'statuses'
        ));
    }

    public function create(): View
    {
        $labs = Lab::list();
        $statuses = StatusBarangTertinggal::labels();

        return view('barang_tertinggal.create', compact('labs', 'statuses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal_ditemukan'   => 'required|date',
            'lab_pilihan'         => 'required|string',
            'lab_lainnya'         => 'nullable|string|max:100',
            'penemu'              => 'required|string|max:255',
            'nama_barang'         => 'required|string|max:255',
            'keterangan'          => 'nullable|string',
            'pemilik'             => 'nullable|string|max:255',
            'no_hp'               => 'nullable|string|max:30',
            'status'              => 'required|in:' . implode(',', StatusBarangTertinggal::ALL),
            'tanggal_pengambilan' => 'nullable|date',
            'penyerah'            => 'nullable|string|max:255',
            'catatan'             => 'nullable|string',
        ]);

        $labNama = $validated['lab_pilihan'] === 'Lainnya'
            ? trim($validated['lab_lainnya'] ?? 'Lainnya')
            : $validated['lab_pilihan'];

        if (empty($labNama)) {
            $labNama = 'Lainnya';
        }

        $barang = BarangTertinggal::create([
            'tanggal_ditemukan'   => $validated['tanggal_ditemukan'],
            'lab_nama'            => $labNama,
            'penemu'              => $validated['penemu'],
            'nama_barang'         => $validated['nama_barang'],
            'keterangan'          => $validated['keterangan'] ?? null,
            'pemilik'             => $validated['pemilik'] ?? null,
            'no_hp'               => $validated['no_hp'] ?? null,
            'status'              => $validated['status'],
            'tanggal_pengambilan' => $validated['tanggal_pengambilan'] ?? null,
            'penyerah'            => $validated['penyerah'] ?? null,
            'catatan'             => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('barang-tertinggal.show', $barang)
            ->with('success', 'Data barang tertinggal berhasil dicatat.');
    }

    public function show(BarangTertinggal $barang_tertinggal): View
    {
        $statuses = StatusBarangTertinggal::labels();
        return view('barang_tertinggal.show', compact('barang_tertinggal', 'statuses'));
    }

    public function edit(BarangTertinggal $barang_tertinggal): View
    {
        $labs = Lab::list();
        $statuses = StatusBarangTertinggal::labels();
        $isLabPreset = in_array($barang_tertinggal->lab_nama, $labs, true);

        return view('barang_tertinggal.edit', compact(
            'barang_tertinggal',
            'labs',
            'statuses',
            'isLabPreset'
        ));
    }

    public function update(Request $request, BarangTertinggal $barang_tertinggal): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal_ditemukan'   => 'required|date',
            'lab_pilihan'         => 'required|string',
            'lab_lainnya'         => 'nullable|string|max:100',
            'penemu'              => 'required|string|max:255',
            'nama_barang'         => 'required|string|max:255',
            'keterangan'          => 'nullable|string',
            'pemilik'             => 'nullable|string|max:255',
            'no_hp'               => 'nullable|string|max:30',
            'status'              => 'required|in:' . implode(',', StatusBarangTertinggal::ALL),
            'tanggal_pengambilan' => 'nullable|date',
            'penyerah'            => 'nullable|string|max:255',
            'catatan'             => 'nullable|string',
        ]);

        $labNama = $validated['lab_pilihan'] === 'Lainnya'
            ? trim($validated['lab_lainnya'] ?? 'Lainnya')
            : $validated['lab_pilihan'];

        if (empty($labNama)) {
            $labNama = 'Lainnya';
        }

        $barang_tertinggal->update([
            'tanggal_ditemukan'   => $validated['tanggal_ditemukan'],
            'lab_nama'            => $labNama,
            'penemu'              => $validated['penemu'],
            'nama_barang'         => $validated['nama_barang'],
            'keterangan'          => $validated['keterangan'] ?? null,
            'pemilik'             => $validated['pemilik'] ?? null,
            'no_hp'               => $validated['no_hp'] ?? null,
            'status'              => $validated['status'],
            'tanggal_pengambilan' => $validated['tanggal_pengambilan'] ?? null,
            'penyerah'            => $validated['penyerah'] ?? null,
            'catatan'             => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('barang-tertinggal.show', $barang_tertinggal)
            ->with('success', 'Data barang tertinggal berhasil diperbarui.');
    }

    public function updateStatus(Request $request, BarangTertinggal $barang_tertinggal): RedirectResponse
    {
        $validated = $request->validate([
            'status'              => 'required|in:' . implode(',', StatusBarangTertinggal::ALL),
            'tanggal_pengambilan' => 'nullable|date',
            'penyerah'            => 'nullable|string|max:255',
            'pemilik'             => 'nullable|string|max:255',
            'no_hp'               => 'nullable|string|max:30',
            'catatan'             => 'nullable|string',
        ]);

        // Auto set tanggal_pengambilan to today if moving to status sudah_diambil/diserahkan_ke_satpam and not filled
        $tglPengambilan = $validated['tanggal_pengambilan'] ?? null;
        if (in_array($validated['status'], [StatusBarangTertinggal::SUDAH_DIAMBIL, StatusBarangTertinggal::DISERAHKAN_KE_SATPAM]) && !$tglPengambilan) {
            $tglPengambilan = now()->toDateString();
        }

        $dataToUpdate = [
            'status'              => $validated['status'],
            'tanggal_pengambilan' => $tglPengambilan,
            'penyerah'            => $validated['penyerah'] ?? $barang_tertinggal->penyerah,
        ];

        if ($request->has('pemilik') && !empty($validated['pemilik'])) {
            $dataToUpdate['pemilik'] = $validated['pemilik'];
        }
        if ($request->has('no_hp') && !empty($validated['no_hp'])) {
            $dataToUpdate['no_hp'] = $validated['no_hp'];
        }
        if ($request->has('catatan') && $validated['catatan'] !== null) {
            $dataToUpdate['catatan'] = $validated['catatan'];
        }

        $barang_tertinggal->update($dataToUpdate);

        return redirect()->route('barang-tertinggal.show', $barang_tertinggal)
            ->with('success', 'Status barang tertinggal berhasil diperbarui.');
    }

    public function destroy(BarangTertinggal $barang_tertinggal): RedirectResponse
    {
        $barang_tertinggal->delete();

        return redirect()->route('barang-tertinggal.index')
            ->with('success', 'Data barang tertinggal berhasil dihapus.');
    }
}
