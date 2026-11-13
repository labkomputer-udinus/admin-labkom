<?php

namespace App\Http\Controllers;

use App\Constants\JenisTindakan;
use App\Constants\StatusBarangTertinggal;
use App\Models\BarangTertinggal;
use App\Models\Distribusi;
use App\Models\Laporan;
use App\Models\Pengajuan;
use App\Models\StokGudang;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EksporController extends Controller
{
    /* =========================================================================
     * 1. LAPORAN PERMASALAHAN LABKOM
     * ========================================================================= */

    private function buildLaporanQuery(Request $request)
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
            $query->where('status', $request->status);
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

        return $query;
    }

    public function laporanExcel(Request $request): StreamedResponse
    {
        $laporans = $this->buildLaporanQuery($request)->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Laporan');

        $headers = [
            'No',
            'ID Laporan',
            'Lab',
            'Bentuk Ketidaksesuaian',
            'Tanggal Kejadian',
            'Nama Pelapor',
            'Bagian',
            'Jabatan Pelapor',
            'Tanggal Laporan',
            'Tanggal Selesai',
            'Status Laporan',
            'Status Akhir',
            'Nama Barang',
            'Deskripsi Kerusakan',
            'Jumlah',
            'Satuan',
            'Tindak Lanjut',
            'Status Item',
            'Tindakan Langsung (Laboran)',
            'Permintaan Tindakan',
            'Keterangan / Catatan',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $rowNum = 2;
        $no = 1;
        foreach ($laporans as $lap) {
            if ($lap->items->isEmpty()) {
                $sheet->fromArray([
                    $no++,
                    '#LAP-' . str_pad($lap->id, 4, '0', STR_PAD_LEFT),
                    'Lab ' . $lap->lab_nama,
                    $lap->bentuk_ketidaksesuaian ?? '-',
                    $lap->tanggal_kejadian?->format('d/m/Y') ?? '-',
                    $lap->nama_pelapor ?? '-',
                    $lap->bagian ?? '-',
                    $lap->jabatan_pelapor ?? '-',
                    $lap->tanggal_laporan?->format('d/m/Y'),
                    $lap->tanggal_selesai?->format('d/m/Y') ?? '-',
                    ucwords(str_replace('_', ' ', $lap->status)),
                    $lap->status_akhir ? strtoupper($lap->status_akhir) : '-',
                    '-',
                    '-',
                    '-',
                    '-',
                    '-',
                    '-',
                    $lap->tindakan_langsung ?? '-',
                    $lap->permintaan_tindakan ?? '-',
                    $lap->catatan_umum ?? '-',
                ], null, 'A' . $rowNum);
                $rowNum++;
            } else {
                foreach ($lap->items as $item) {
                    $sheet->fromArray([
                        $no++,
                        '#LAP-' . str_pad($lap->id, 4, '0', STR_PAD_LEFT),
                        'Lab ' . $lap->lab_nama,
                        $lap->bentuk_ketidaksesuaian ?? '-',
                        $lap->tanggal_kejadian?->format('d/m/Y') ?? '-',
                        $lap->nama_pelapor ?? '-',
                        $lap->bagian ?? '-',
                        $lap->jabatan_pelapor ?? '-',
                        $lap->tanggal_laporan?->format('d/m/Y'),
                        $lap->tanggal_selesai?->format('d/m/Y') ?? '-',
                        ucwords(str_replace('_', ' ', $lap->status)),
                        $lap->status_akhir ? strtoupper($lap->status_akhir) : '-',
                        $item->nama_barang,
                        $item->deskripsi ?? '-',
                        $item->jumlah,
                        $item->satuan,
                        ucwords(str_replace('_', ' ', $item->jenis_tindak_lanjut)),
                        ucwords(str_replace('_', ' ', $item->status_item)),
                        $lap->tindakan_langsung ?? '-',
                        $lap->permintaan_tindakan ?? '-',
                        $item->keterangan ?? $lap->catatan_umum ?? '-',
                    ], null, 'A' . $rowNum);
                    $rowNum++;
                }
            }
        }

        $this->styleHeader($sheet, count($headers));
        $this->styleTable($sheet, count($headers), max($rowNum - 1, 1));

        $filename = 'rekap_laporan_labkom_' . date('Ymd_His') . '.xlsx';
        return $this->streamXlsx($spreadsheet, $filename);
    }

    public function laporanPdf(Request $request)
    {
        $laporans = $this->buildLaporanQuery($request)->get();
        $filterInfo = [
            'periode' => $this->formatPeriode($request->input('start_date'), $request->input('end_date')),
            'lab'     => $request->input('lab') ? 'Lab ' . $request->input('lab') : null,
            'status'  => $request->input('status') ? ucwords(str_replace('_', ' ', $request->input('status'))) : null,
            'search'  => $request->input('search'),
        ];

        return view('ekspor.laporan-pdf', compact('laporans', 'filterInfo'));
    }

    /* =========================================================================
     * 2. GUDANG & INVENTARIS STOK
     * ========================================================================= */

    private function buildStokQuery(Request $request)
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

        return $query;
    }

    public function stokExcel(Request $request): StreamedResponse
    {
        $stoks = $this->buildStokQuery($request)->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Stok Gudang');

        $headers = [
            'No',
            'ID Barang',
            'Nama Barang',
            'Spesifikasi',
            'Kategori',
            'Satuan',
            'Stok Minimum',
            'Stok Saat Ini',
            'Status Stok',
            'Catatan',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $rowNum = 2;
        foreach ($stoks as $index => $stok) {
            $isKritis = $stok->isKritis();
            $status = $isKritis ? 'KRITIS' : 'AMAN';

            $sheet->fromArray([
                $index + 1,
                $stok->barangMaster->id ?? '-',
                $stok->barangMaster->nama_barang ?? '-',
                $stok->barangMaster->spesifikasi ?? '-',
                $stok->barangMaster->kategori ?? '-',
                $stok->barangMaster->satuan ?? 'unit',
                $stok->barangMaster->stok_minimum ?? 0,
                $stok->stok_saat_ini,
                $status,
                $stok->catatan ?? '-',
            ], null, 'A' . $rowNum);
            $rowNum++;
        }

        $this->styleHeader($sheet, count($headers));
        $this->styleTable($sheet, count($headers), max($rowNum - 1, 1));

        $filename = 'rekap_stok_gudang_' . date('Ymd_His') . '.xlsx';
        return $this->streamXlsx($spreadsheet, $filename);
    }

    public function stokPdf(Request $request)
    {
        $stoks = $this->buildStokQuery($request)->get();
        $filterInfo = [
            'kategori'    => $request->input('kategori'),
            'status_stok' => $request->input('status_stok'),
            'search'      => $request->input('search'),
        ];

        return view('ekspor.stok-pdf', compact('stoks', 'filterInfo'));
    }

    /* =========================================================================
     * 3. DISTRIBUSI KE LAB
     * ========================================================================= */

    private function buildDistribusiQuery(Request $request)
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

        return $query;
    }

    public function distribusiExcel(Request $request): StreamedResponse
    {
        $distribusis = $this->buildDistribusiQuery($request)->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Distribusi Lab');

        $headers = [
            'No',
            'ID Distribusi',
            'Jenis Distribusi',
            'Kategori / Alasan',
            'Nama Barang',
            'Jumlah',
            'Satuan',
            'Asal Barang',
            'Nomor Pengajuan Asal',
            'Tanggal Pengajuan Asal',
            'Lab Tujuan',
            'Kode PC / Unit Tujuan',
            'Diterima Oleh',
            'Tanggal Distribusi',
            'Tanggal Konfirmasi Terima',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $rowNum = 2;
        foreach ($distribusis as $index => $dist) {
            $nomorPengajuan = $dist->pengajuanItem?->pengajuan?->nomor_pengajuan ?? '-';
            $tglPengajuan = $dist->pengajuanItem?->pengajuan?->tanggal_ajuan?->format('d/m/Y') ?? '-';
            $asalLabel = $dist->asal_barang === 'pengajuan' ? "Pengajuan ({$nomorPengajuan})" : 'Stok Lama Gudang';

            $jenisLabel = $dist->isMandiri() ? 'Distribusi Mandiri' : 'Berdasarkan Laporan';
            $kategoriAlasanLabel = '-';
            if ($dist->isMandiri()) {
                $parts = [];
                if (!empty($dist->kategori_alasan)) $parts[] = "[{$dist->kategori_alasan}]";
                if (!empty($dist->alasan_distribusi)) $parts[] = $dist->alasan_distribusi;
                $kategoriAlasanLabel = count($parts) ? implode(' ', $parts) : 'Distribusi Mandiri';
            } else {
                $nomorLap = $dist->laporanItem?->laporan?->nomor_laporan ?? ($dist->laporanItem ? '#LAP-' . str_pad($dist->laporanItem->laporan_id, 4, '0', STR_PAD_LEFT) : '-');
                $kategoriAlasanLabel = "Item Laporan ({$nomorLap})";
            }

            $sheet->fromArray([
                $index + 1,
                '#DST-' . str_pad($dist->id, 4, '0', STR_PAD_LEFT),
                $jenisLabel,
                $kategoriAlasanLabel,
                $dist->stokGudang?->barangMaster?->nama_barang ?? '-',
                $dist->jumlah,
                $dist->stokGudang?->barangMaster?->satuan ?? 'unit',
                $asalLabel,
                $nomorPengajuan,
                $tglPengajuan,
                'Lab ' . $dist->lab_tujuan,
                $dist->kode_pc_tujuan ?? '-',
                $dist->diterima_oleh ?? '-',
                $dist->tanggal_distribusi?->format('d/m/Y') ?? '-',
                $dist->tanggal_terima_lab?->format('d/m/Y') ?? '-',
            ], null, 'A' . $rowNum);
            $rowNum++;
        }

        $this->styleHeader($sheet, count($headers));
        $this->styleTable($sheet, count($headers), max($rowNum - 1, 1));

        $filename = 'dokumentasi_akreditasi_distribusi_' . date('Ymd_His') . '.xlsx';
        return $this->streamXlsx($spreadsheet, $filename);
    }

    public function distribusiPdf(Request $request)
    {
        $distribusis = $this->buildDistribusiQuery($request)->get();
        $filterInfo = [
            'periode'          => $this->formatPeriode($request->input('start_date'), $request->input('end_date')),
            'lab'              => $request->input('lab') ? 'Lab ' . $request->input('lab') : null,
            'asal_barang'      => $request->input('asal_barang') ? ($request->input('asal_barang') === 'pengajuan' ? 'Pengajuan Pengadaan' : 'Stok Lama Gudang') : null,
            'jenis_distribusi' => $request->input('jenis_distribusi') ? ($request->input('jenis_distribusi') === 'mandiri' ? 'Distribusi Mandiri' : 'Berdasarkan Laporan') : null,
            'search'           => $request->input('search'),
        ];

        return view('ekspor.distribusi-pdf', compact('distribusis', 'filterInfo'));
    }

    /* =========================================================================
     * 4. PENGAJUAN PENGADAAN BARANG
     * ========================================================================= */

    private function buildPengajuanQuery(Request $request)
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

        return $query;
    }

    public function pengajuanExcel(Request $request): StreamedResponse
    {
        $pengajuans = $this->buildPengajuanQuery($request)->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Pengajuan Pengadaan');

        $headers = [
            'No',
            'No. Pengajuan',
            'Divisi Tujuan',
            'Tanggal Ajuan',
            'Status',
            'Tanggal Keputusan',
            'Catatan Keputusan',
            'Nama Barang',
            'Spesifikasi',
            'Jumlah',
            'Satuan',
            'Alasan Pengadaan',
            'Status Item',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $rowNum = 2;
        $no = 1;
        foreach ($pengajuans as $p) {
            if ($p->items->isEmpty()) {
                $sheet->fromArray([
                    $no++,
                    $p->nomor_pengajuan,
                    $p->divisi_tujuan ?? '-',
                    $p->tanggal_ajuan?->format('d/m/Y') ?? '-',
                    strtoupper(str_replace('_', ' ', $p->status)),
                    $p->tanggal_keputusan?->format('d/m/Y') ?? '-',
                    $p->catatan_keputusan ?? '-',
                    '-',
                    '-',
                    '-',
                    '-',
                    '-',
                    '-',
                ], null, 'A' . $rowNum);
                $rowNum++;
            } else {
                foreach ($p->items as $item) {
                    $sheet->fromArray([
                        $no++,
                        $p->nomor_pengajuan,
                        $p->divisi_tujuan ?? '-',
                        $p->tanggal_ajuan?->format('d/m/Y') ?? '-',
                        strtoupper(str_replace('_', ' ', $p->status)),
                        $p->tanggal_keputusan?->format('d/m/Y') ?? '-',
                        $p->catatan_keputusan ?? '-',
                        $item->nama_barang,
                        $item->spesifikasi ?? '-',
                        $item->jumlah,
                        $item->satuan,
                        $item->alasan_pengadaan ?? '-',
                        $item->status_item ? ucwords(str_replace('_', ' ', $item->status_item)) : '-',
                    ], null, 'A' . $rowNum);
                    $rowNum++;
                }
            }
        }

        $this->styleHeader($sheet, count($headers));
        $this->styleTable($sheet, count($headers), max($rowNum - 1, 1));

        $filename = 'rekap_pengajuan_pengadaan_' . date('Ymd_His') . '.xlsx';
        return $this->streamXlsx($spreadsheet, $filename);
    }

    public function pengajuanPdf(Request $request)
    {
        $pengajuans = $this->buildPengajuanQuery($request)->get();
        $filterInfo = [
            'periode' => $this->formatPeriode($request->input('start_date'), $request->input('end_date')),
            'status'  => $request->input('status') ? strtoupper(str_replace('_', ' ', $request->input('status'))) : null,
            'search'  => $request->input('search'),
        ];

        return view('ekspor.pengajuan-pdf', compact('pengajuans', 'filterInfo'));
    }

    public function detailPengajuanExcel(Pengajuan $pengajuan)
    {
        $pengajuan->load('items');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Detail Pengajuan');

        // Section 1: Informasi Dokumen Pengajuan
        $sheet->setCellValue('A1', 'DOKUMEN DETAIL PENGAJUAN PENGADAAN BARANG');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $metadata = [
            ['Nomor Pengajuan', $pengajuan->nomor_pengajuan],
            ['Divisi / Vendor Tujuan', $pengajuan->divisi_tujuan],
            ['Tanggal Pengajuan', $pengajuan->tanggal_ajuan?->format('d/m/Y') ?? '-'],
            ['Status Pengajuan', strtoupper(str_replace('_', ' ', $pengajuan->status))],
            ['Tanggal Keputusan', $pengajuan->tanggal_keputusan?->format('d/m/Y') ?? '-'],
            ['Catatan Keputusan', $pengajuan->catatan_keputusan ?? '-'],
            ['Total Item Barang', $pengajuan->items->count()],
            ['Total Unit Diajukan', $pengajuan->items->sum('jumlah_diajukan')],
            ['Total Unit Diterima', $pengajuan->items->sum('jumlah_diterima') ?: 0],
            ['Waktu Unduh', date('d/m/Y H:i:s')],
        ];

        $metaRow = 3;
        foreach ($metadata as $row) {
            $sheet->fromArray($row, null, 'A' . $metaRow);
            $sheet->getStyle('A' . $metaRow)->getFont()->setBold(true);
            $metaRow++;
        }

        // Section 2: Tabel Daftar Barang & Verifikasi
        $tableStartRow = $metaRow + 2;
        $sheet->setCellValue('A' . ($tableStartRow - 1), 'RINCIAN ITEM BARANG & STATUS PENERIMAAN');
        $sheet->getStyle('A' . ($tableStartRow - 1))->getFont()->setBold(true)->setSize(12);

        $headers = [
            'No',
            'Nama Barang',
            'Spesifikasi Diajukan',
            'Jumlah Diajukan',
            'Satuan',
            'Jumlah Diterima',
            'Spesifikasi Diterima',
            'Status Kesesuaian',
            'Tanggal Terima',
            'Catatan Selisih / Keterangan',
        ];

        $sheet->fromArray($headers, null, 'A' . $tableStartRow);
        $this->styleHeader($sheet, count($headers), $tableStartRow);

        $currRow = $tableStartRow + 1;
        foreach ($pengajuan->items as $index => $item) {
            $statusLabel = match ($item->status_kesesuaian) {
                'sesuai' => 'SESUAI (PENUH)',
                'sesuai_beda_spesifikasi' => 'SESUAI (BEDA SPESIFIKASI/MERK)',
                'tidak_sesuai' => 'TIDAK SESUAI',
                default => '-',
            };

            $sheet->fromArray([
                $index + 1,
                $item->nama_barang,
                $item->spesifikasi ?? '-',
                $item->jumlah_diajukan,
                $item->satuan,
                $item->jumlah_diterima !== null ? $item->jumlah_diterima : '-',
                $item->spesifikasi_diterima ?? '-',
                $statusLabel,
                $item->tanggal_terima ? $item->tanggal_terima->format('d/m/Y') : '-',
                $item->catatan_selisih ?? '-',
            ], null, 'A' . $currRow);
            $currRow++;
        }

        $this->styleTable($sheet, count($headers), max($currRow - 1, $tableStartRow), $tableStartRow);

        $cleanNomor = preg_replace('/[^A-Za-z0-9_\-]/', '_', $pengajuan->nomor_pengajuan);
        $filename = 'detail_pengajuan_' . $cleanNomor . '_' . date('Ymd_His') . '.xlsx';
        return $this->streamXlsx($spreadsheet, $filename);
    }

    public function detailPengajuanPdf(Pengajuan $pengajuan)
    {
        $pengajuan->load('items');
        return view('ekspor.detail-pengajuan-pdf', compact('pengajuan'));
    }

    /* =========================================================================
     * 5. BARANG TERTINGGAL (LOST & FOUND)
     * ========================================================================= */

    private function buildBarangTertinggalQuery(Request $request)
    {
        $query = BarangTertinggal::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('penemu', 'like', "%{$search}%")
                  ->orWhere('pemilik', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('lab_nama', 'like', "%{$search}%");
            });
        }

        if ($lab = $request->input('lab')) {
            $query->where('lab_nama', $lab);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($startDate = $request->input('start_date')) {
            $query->whereDate('tanggal_ditemukan', '>=', $startDate);
        }

        if ($endDate = $request->input('end_date')) {
            $query->whereDate('tanggal_ditemukan', '<=', $endDate);
        }

        return $query->orderBy('tanggal_ditemukan', 'desc')->orderBy('id', 'desc');
    }

    public function barangTertinggalExcel(Request $request): StreamedResponse
    {
        $items = $this->buildBarangTertinggalQuery($request)->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Barang Tertinggal');

        $headers = [
            'No',
            'ID Record',
            'Tanggal Ditemukan',
            'Lab / Lokasi',
            'Nama Barang',
            'Merk / Type / Keterangan',
            'Penemu',
            'Status',
            'Pemilik',
            'No HP / WA',
            'Tanggal Pengambilan',
            'Penyerah',
            'Catatan',
        ];

        $sheet->fromArray($headers, null, 'A1');

        $rowNum = 2;
        foreach ($items as $index => $item) {
            $sheet->fromArray([
                $index + 1,
                '#BT-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
                $item->tanggal_ditemukan?->format('d/m/Y') ?? '-',
                $item->lab_nama,
                $item->nama_barang,
                $item->keterangan ?? '-',
                $item->penemu,
                StatusBarangTertinggal::label($item->status),
                $item->pemilik ?? '-',
                $item->no_hp ?? '-',
                $item->tanggal_pengambilan?->format('d/m/Y') ?? '-',
                $item->penyerah ?? '-',
                $item->catatan ?? '-',
            ], null, 'A' . $rowNum);
            $rowNum++;
        }

        $this->styleHeader($sheet, count($headers));
        $this->styleTable($sheet, count($headers), max($rowNum - 1, 1));

        $filename = 'rekap_barang_tertinggal_' . date('Ymd_His') . '.xlsx';
        return $this->streamXlsx($spreadsheet, $filename);
    }

    public function barangTertinggalPdf(Request $request)
    {
        $items = $this->buildBarangTertinggalQuery($request)->get();
        $filterInfo = [
            'periode' => $this->formatPeriode($request->input('start_date'), $request->input('end_date')),
            'search'  => $request->input('search'),
            'lab'     => $request->input('lab'),
            'status'  => $request->input('status') ? StatusBarangTertinggal::label($request->input('status')) : null,
        ];

        return view('ekspor.barang-tertinggal-pdf', compact('items', 'filterInfo'));
    }

    /* =========================================================================
     * 6. RIWAYAT DETAIL LAPORAN (SATUAN)
     * ========================================================================= */

    public function riwayatLaporanExcel(Laporan $laporan): StreamedResponse
    {
        $laporan->load([
            'items.penanganans' => fn($q) => $q->orderBy('id', 'asc'),
            'items.pengajuanItems.pengajuan',
            'items.distribusis',
        ]);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Laporan');

        // Section 1: Metadata Form Laporan Fisik
        $sheet->setCellValue('A1', 'INFORMASI LAPORAN LABORATORIUM');
        $sheet->mergeCells('A1:B1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

        $metadata = [
            ['ID Laporan', '#LAP-' . str_pad($laporan->id, 4, '0', STR_PAD_LEFT)],
            ['Laboratorium', 'Lab ' . $laporan->lab_nama],
            ['Bentuk Ketidaksesuaian', $laporan->bentuk_ketidaksesuaian ?? '-'],
            ['Tanggal Kejadian', $laporan->tanggal_kejadian?->format('d/m/Y') ?? '-'],
            ['Tanggal Laporan', $laporan->tanggal_laporan?->format('d/m/Y') ?? '-'],
            ['Nama Pelapor', $laporan->nama_pelapor ?? '-'],
            ['Bagian', $laporan->bagian ?? '-'],
            ['Jabatan Pelapor', $laporan->jabatan_pelapor ?? '-'],
            ['Status Sistem', ucwords(str_replace('_', ' ', $laporan->status))],
            ['Status Akhir', $laporan->status_akhir ? strtoupper($laporan->status_akhir) : '-'],
            ['Uraian Pengamatan', $laporan->uraian_pengamatan ?? $laporan->catatan_umum ?? '-'],
            ['Tindakan Langsung (Laboran)', $laporan->tindakan_langsung ?? '-'],
            ['Permintaan Tindakan', $laporan->permintaan_tindakan ?? '-'],
        ];

        $metaRow = 2;
        foreach ($metadata as $row) {
            $sheet->fromArray($row, null, 'A' . $metaRow);
            $sheet->getStyle('A' . $metaRow)->getFont()->setBold(true);
            $metaRow++;
        }

        // Section 2: Kronologi Timeline Per Item
        $tableStartRow = $metaRow + 2;
        $sheet->setCellValue('A' . ($tableStartRow - 1), 'KRONOLOGI PENANGANAN PER ITEM BARANG');
        $sheet->getStyle('A' . ($tableStartRow - 1))->getFont()->setBold(true)->setSize(12);

        $headers = [
            'Item ID',
            'Nama Barang',
            'Jumlah',
            'Satuan',
            'Jenis Tindak Lanjut',
            'Status Item Terkini',
            'Langkah Ke-',
            'Tahap / Langkah',
            'Kategori Tindakan',
            'Aktor / Pihak Pelaksana',
            'Petugas',
            'Tanggal Tindakan',
            'Tanggal Hasil',
            'Uraian Tindakan / Hasil',
            'Status Sebelum',
            'Status Sesudah',
        ];

        $sheet->fromArray($headers, null, 'A' . $tableStartRow);
        $this->styleHeader($sheet, count($headers), $tableStartRow);

        $currRow = $tableStartRow + 1;
        foreach ($laporan->items as $item) {
            if ($item->penanganans->isEmpty()) {
                $sheet->fromArray([
                    $item->id,
                    $item->nama_barang,
                    $item->jumlah,
                    $item->satuan,
                    $item->jenis_tindak_lanjut,
                    $item->status_item,
                    '-',
                    '-',
                    '-',
                    '-',
                    '-',
                    '-',
                    '-',
                    'Belum ada riwayat penanganan tercatat.',
                    '-',
                    '-',
                ], null, 'A' . $currRow);
                $currRow++;
            } else {
                foreach ($item->penanganans as $pIndex => $p) {
                    $sheet->fromArray([
                        $item->id,
                        $item->nama_barang,
                        $item->jumlah,
                        $item->satuan,
                        $item->jenis_tindak_lanjut,
                        $item->status_item,
                        $pIndex + 1,
                        $p->tahap,
                        JenisTindakan::label($p->jenis_tindakan),
                        $p->aktor_peran ?? '-',
                        $p->petugas ?? '-',
                        $p->tanggal_tindakan?->format('d/m/Y H:i') ?? ($p->tanggal_waktu?->format('d/m/Y H:i') ?? '-'),
                        $p->tanggal_hasil?->format('d/m/Y H:i') ?? '-',
                        $p->tindakan_atau_hasil,
                        $p->status_sebelum ? str_replace('_', ' ', $p->status_sebelum) : '-',
                        $p->status_item_saat_itu ? str_replace('_', ' ', $p->status_item_saat_itu) : '-',
                    ], null, 'A' . $currRow);
                    $currRow++;
                }
            }
        }

        $this->styleTable($sheet, count($headers), max($currRow - 1, $tableStartRow), $tableStartRow);

        $filename = 'riwayat_laporan_LAP_' . str_pad($laporan->id, 4, '0', STR_PAD_LEFT) . '_' . date('Ymd_His') . '.xlsx';
        return $this->streamXlsx($spreadsheet, $filename);
    }

    public function riwayatLaporanPdf(Laporan $laporan)
    {
        $laporan->load([
            'items.penanganans' => fn($q) => $q->orderBy('id', 'asc'),
            'items.pengajuanItems.pengajuan',
            'items.distribusis',
        ]);

        return view('ekspor.riwayat-laporan-pdf', compact('laporan'));
    }

    /* =========================================================================
     * HELPER FUNCTIONS (STYLING & STREAMING)
     * ========================================================================= */

    private function styleHeader($sheet, int $columnCount, int $row = 1): void
    {
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCount);
        $range = "A{$row}:{$lastCol}{$row}";

        $sheet->getStyle($range)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
        $sheet->getStyle($range)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1E3A8A'); // Navy Blue
        $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(24);
    }

    private function styleTable($sheet, int $columnCount, int $endRow, int $startRow = 1): void
    {
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCount);
        $range = "A{$startRow}:{$lastCol}{$endRow}";

        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFD1D5DB'));
    }

    private function streamXlsx(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        $sheet = $spreadsheet->getActiveSheet();
        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    private function formatPeriode(?string $startDate, ?string $endDate): ?string
    {
        if ($startDate && $endDate) {
            return date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate));
        }
        if ($startDate) {
            return 'Mulai ' . date('d/m/Y', strtotime($startDate));
        }
        if ($endDate) {
            return 'Sampai ' . date('d/m/Y', strtotime($endDate));
        }
        return null;
    }
}
