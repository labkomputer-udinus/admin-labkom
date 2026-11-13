<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Distribusi Barang ke Lab</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Udinus.png') }}">
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 11px; color: #111; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 15px; text-transform: uppercase; }
        .header p { margin: 3px 0 0; font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-pengajuan { background: #e0f2fe; color: #0369a1; }
        .badge-gudang { background: #fef3c7; color: #92400e; }
        .badge-terima { background: #d1fae5; color: #065f46; }
        .badge-belum { background: #fee2e2; color: #991b1b; }
        .badge-mandiri { background: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe; }
        .badge-laporan { background: #f1f5f9; color: #334155; }
        .print-btn { margin-bottom: 15px; padding: 6px 14px; background: #1e9df1; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .filter-summary { margin-top: 6px; font-size: 10px; color: #444; font-style: italic; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Cetak Dokumen / Simpan PDF (Ctrl+P)</button>

    <div class="header">
        <table style="width: 100%; border: none; margin-bottom: 5px;">
            <tr>
                <td style="width: 60px; border: none; vertical-align: middle; text-align: left; padding: 0;">
                    <img src="{{ asset('images/Logo Udinus.png') }}" alt="Logo Udinus" style="width: 50px; height: 50px; object-fit: contain;">
                </td>
                <td style="border: none; vertical-align: middle; text-align: center; padding: 0;">
                    <h2 style="margin: 0; font-size: 15px; text-transform: uppercase;">Universitas Dian Nuswantoro — Rekapitulasi Distribusi Barang ke Lab</h2>
                    <p style="margin: 3px 0 0; font-size: 10px; color: #555;">Laboratorium Komputer | Dicetak pada: {{ date('d F Y H:i') }}</p>
                    @if(!empty($filterInfo['periode']) || !empty($filterInfo['lab']) || !empty($filterInfo['asal_barang']) || !empty($filterInfo['jenis_distribusi']) || !empty($filterInfo['search']))
                        <p class="filter-summary">
                            Filter Aktif:
                            @if(!empty($filterInfo['periode'])) [Periode: {{ $filterInfo['periode'] }}] @endif
                            @if(!empty($filterInfo['jenis_distribusi'])) [Jenis: {{ $filterInfo['jenis_distribusi'] }}] @endif
                            @if(!empty($filterInfo['lab'])) [Lab: {{ $filterInfo['lab'] }}] @endif
                            @if(!empty($filterInfo['asal_barang'])) [Asal: {{ $filterInfo['asal_barang'] }}] @endif
                            @if(!empty($filterInfo['search'])) [Pencarian: "{{ $filterInfo['search'] }}"] @endif
                        </p>
                    @endif
                </td>
                <td style="width: 60px; border: none; padding: 0;"></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="7%">ID</th>
                <th width="8%">Tgl Kirim</th>
                <th width="17%">Jenis & Alasan</th>
                <th width="18%">Nama Barang</th>
                <th width="7%">Jumlah</th>
                <th width="11%">Asal Barang</th>
                <th width="12%">Lab & Unit</th>
                <th width="9%">Penerima</th>
                <th width="8%">Konfirmasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($distribusis as $index => $dist)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>#DST-{{ str_pad($dist->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $dist->tanggal_distribusi?->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        @if($dist->isMandiri())
                            <span class="badge badge-mandiri">⚡ Mandiri</span>
                            @if(!empty($dist->kategori_alasan))
                                <div style="font-weight: bold; font-size: 9px; color: #4c1d95; margin-top: 2px;">{{ $dist->kategori_alasan }}</div>
                            @endif
                            @if(!empty($dist->alasan_distribusi))
                                <div style="font-size: 8px; color: #555; margin-top: 1px;">{{ $dist->alasan_distribusi }}</div>
                            @endif
                        @else
                            <span class="badge badge-laporan">📋 Laporan</span>
                            @if($dist->laporanItem?->laporan_id)
                                <div style="font-size: 8px; color: #555; margin-top: 2px;">
                                    {{ $dist->laporanItem->laporan->nomor_laporan ?? '#LAP-' . str_pad($dist->laporanItem->laporan_id, 4, '0', STR_PAD_LEFT) }}
                                </div>
                            @endif
                        @endif
                    </td>
                    <td><strong>{{ $dist->stokGudang?->barangMaster?->nama_barang ?? '-' }}</strong></td>
                    <td style="text-align: center;"><strong>{{ $dist->jumlah }}</strong> {{ $dist->stokGudang?->barangMaster?->satuan ?? 'unit' }}</td>
                    <td>
                        @if($dist->asal_barang === 'pengajuan')
                            <span class="badge badge-pengajuan">Pengajuan</span>
                            @if($dist->pengajuanItem?->pengajuan?->nomor_pengajuan)
                                <div style="font-size: 8px; color: #555; margin-top: 2px;">{{ $dist->pengajuanItem->pengajuan->nomor_pengajuan }}</div>
                            @endif
                        @else
                            <span class="badge badge-gudang">Stok Gudang</span>
                        @endif
                    </td>
                    <td>
                        <strong>Lab {{ $dist->lab_tujuan }}</strong>
                        @if($dist->kode_pc_tujuan)
                            <div style="font-size: 9px; color: #555;">Unit: {{ $dist->kode_pc_tujuan }}</div>
                        @else
                            <div style="font-size: 8px; color: #888; font-style: italic;">Umum/Non-PC</div>
                        @endif
                    </td>
                    <td>{{ $dist->diterima_oleh ?? '-' }}</td>
                    <td>
                        @if($dist->tanggal_terima_lab)
                            <span class="badge badge-terima">Diterima</span>
                            <div style="font-size: 8px; color: #065f46; margin-top: 2px;">{{ $dist->tanggal_terima_lab->format('d/m/Y') }}</div>
                        @else
                            <span class="badge badge-belum">Belum</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px; color: #666;">
                        Tidak ada data distribusi barang yang sesuai filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; float: right; width: 220px; text-align: center; font-size: 11px;">
        <p>Semarang, {{ date('d F Y') }}</p>
        <p style="margin-top: 60px; font-weight: bold; border-bottom: 1px solid #333; display: inline-block; min-width: 160px;">
            Petugas Laboratorium
        </p>
    </div>
</body>
</html>
