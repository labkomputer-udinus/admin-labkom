<?php

namespace App\Http\Controllers;

use App\Constants\Lab;
use App\Models\BarangTertinggal;
use App\Models\Distribusi;
use App\Models\Laporan;
use App\Models\LaporanItem;
use App\Models\Pengajuan;
use App\Models\StokGudang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLaporan = Laporan::count();
        $laporanProses = Laporan::whereNotIn('status', ['selesai', 'ditolak'])->count();
        $laporanSelesai = Laporan::where('status', 'selesai')->count();
        $pengajuanMenunggu = Pengajuan::where('status', 'menunggu')->count();

        // Barang Tertinggal Metrics
        $totalBarangTertinggal = BarangTertinggal::count();
        $barangTertinggalBelumDiambil = BarangTertinggal::where('status', 'belum_diambil')->count();

        // Stok Kritis
        $stokKritis = StokGudang::with('barangMaster')
            ->whereHas('barangMaster', function ($q) {
                $q->whereColumn('stok_gudangs.stok_saat_ini', '<=', 'barang_masters.stok_minimum');
            })
            ->get();

        // Laporan per Status
        $statusCounts = [
            'dicatat' => Laporan::where('status', 'dicatat')->count(),
            'ditriase' => Laporan::where('status', 'ditriase')->count(),
            'dalam_proses' => Laporan::where('status', 'dalam_proses')->count(),
            'menunggu_keputusan_divisi' => Laporan::where('status', 'menunggu_keputusan_divisi')->count(),
            'barang_diterima' => Laporan::where('status', 'barang_diterima')->count(),
            'siap_distribusi' => Laporan::where('status', 'siap_distribusi')->count(),
            'selesai' => $laporanSelesai,
        ];

        // Laporan per Lab (14 Lab)
        $labReports = [];
        foreach (Lab::list() as $lab) {
            $labReports[$lab] = Laporan::where('lab_nama', $lab)->count();
        }

        // Laporan Terbaru
        $recentLaporans = Laporan::with('items')
            ->latest('tanggal_laporan')
            ->latest('id')
            ->take(5)
            ->get();

        // Distribusi Terbaru
        $recentDistribusi = Distribusi::with(['laporanItem.laporan', 'stokGudang.barangMaster'])
            ->latest('tanggal_distribusi')
            ->latest('id')
            ->take(5)
            ->get();

        // Barang Tertinggal Terbaru
        $recentBarangTertinggal = BarangTertinggal::latest('tanggal_ditemukan')
            ->latest('id')
            ->take(5)
            ->get();

        // 5 Barang Terbanyak yang Perlu Diganti dan Belum Didistribusikan
        $topPenggantianBelumDistribusi = LaporanItem::with(['distribusis', 'laporan'])
            ->where('jenis_tindak_lanjut', 'penggantian')
            ->whereNotIn('status_item', ['selesai', 'selesai_perbaikan', 'terdistribusi'])
            ->whereHas('laporan', function ($q) {
                $q->whereNotIn('status', ['selesai', 'ditolak']);
            })
            ->get()
            ->map(function ($item) {
                $terdistribusi = (int) $item->distribusis->sum('jumlah');
                $sisa = max(0, (int) $item->jumlah - $terdistribusi);
                return [
                    'nama_barang' => $item->nama_barang,
                    'satuan' => $item->satuan ?? 'unit',
                    'total_belum_distribusi' => $sisa,
                    'total_diajukan' => (int) $item->jumlah,
                    'frekuensi' => 1,
                ];
            })
            ->filter(fn($item) => $item['total_belum_distribusi'] > 0)
            ->groupBy('nama_barang')
            ->map(function ($group, $namaBarang) {
                return (object) [
                    'nama_barang' => $namaBarang,
                    'total_belum_distribusi' => $group->sum('total_belum_distribusi'),
                    'total_diajukan' => $group->sum('total_diajukan'),
                    'frekuensi' => $group->count(),
                    'satuan' => $group->first()['satuan'] ?? 'unit',
                ];
            })
            ->sortByDesc('total_belum_distribusi')
            ->take(5)
            ->values();

        // Ringkasan Kebutuhan Barang per Lab (Item aktif yang belum selesai, urut berdasarkan Lab)
        $labOrder = array_flip(Lab::list());
        $labKebutuhan = Laporan::with(['items' => function ($q) {
                $q->whereNotIn('status_item', ['selesai', 'selesai_perbaikan', 'terdistribusi'])
                  ->with('distribusis');
            }])
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->whereHas('items', function ($q) {
                $q->whereNotIn('status_item', ['selesai', 'selesai_perbaikan', 'terdistribusi']);
            })
            ->latest('tanggal_laporan')
            ->get()
            ->groupBy('lab_nama')
            ->sortBy(function ($group, $labNama) use ($labOrder) {
                return $labOrder[$labNama] ?? 999;
            });

        // Laporan Tertahan (> 1 bulan tanpa penanganan / log baru)
        $thresholdDate = now()->subMonth();

        $activeLaporans = Laporan::with(['items.penanganans'])
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->where(function ($q) {
                $q->whereNull('status_akhir')->orWhere('status_akhir', '!=', 'closed');
            })
            ->get();

        $laporanTertahan = $activeLaporans->filter(function ($laporan) use ($thresholdDate) {
            // Laporan yang semua itemnya 'terdistribusi' sedang menunggu konfirmasi laboran,
            // bukan benar-benar mandek — jangan masukkan ke daftar tertahan.
            $allTerdistribusi = $laporan->items->isNotEmpty() &&
                $laporan->items->every(fn($item) => in_array($item->status_item, ['terdistribusi', 'selesai', 'selesai_perbaikan']));
            if ($allTerdistribusi) {
                return false;
            }

            $latestActivity = null;

            foreach ($laporan->items as $item) {
                foreach ($item->penanganans as $penanganan) {
                    $logDate = $penanganan->tanggal_tindakan ?? $penanganan->tanggal_waktu;
                    if ($logDate && (!$latestActivity || $logDate > $latestActivity)) {
                        $latestActivity = $logDate;
                    }
                }
            }

            if (!$latestActivity) {
                $latestActivity = $laporan->tanggal_laporan ?? $laporan->created_at;
            }

            $laporan->last_activity_at = $latestActivity;
            $laporan->days_inactive = $latestActivity ? (int) abs(now()->diffInDays($latestActivity)) : 999;

            return $latestActivity && $latestActivity < $thresholdDate;
        })->sortByDesc('days_inactive');

        return view('dashboard.index', compact(
            'totalLaporan',
            'laporanProses',
            'laporanSelesai',
            'pengajuanMenunggu',
            'totalBarangTertinggal',
            'barangTertinggalBelumDiambil',
            'stokKritis',
            'statusCounts',
            'labReports',
            'recentLaporans',
            'recentBarangTertinggal',
            'recentDistribusi',
            'topPenggantianBelumDistribusi',
            'labKebutuhan',
            'laporanTertahan'
        ));
    }
}
