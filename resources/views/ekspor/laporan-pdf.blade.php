<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Laporan Permasalahan LabKom</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Udinus.png') }}">
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 11px; color: #111; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 3px 0 0; font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
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
                    <h2 style="margin: 0; font-size: 15px; text-transform: uppercase;">Universitas Dian Nuswantoro — Rekapitulasi Laporan LabKom</h2>
                    <p style="margin: 3px 0 0; font-size: 10px; color: #555;">Dicetak pada: {{ date('d F Y H:i') }} | Sistem LabKom Admin</p>
                    @if(!empty($filterInfo['periode']) || !empty($filterInfo['lab']) || !empty($filterInfo['status']) || !empty($filterInfo['search']))
                        <p class="filter-summary">
                            Filter Aktif:
                            @if(!empty($filterInfo['periode'])) [Periode: {{ $filterInfo['periode'] }}] @endif
                            @if(!empty($filterInfo['lab'])) [Lab: {{ $filterInfo['lab'] }}] @endif
                            @if(!empty($filterInfo['status'])) [Status: {{ $filterInfo['status'] }}] @endif
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
                <th width="5%">No</th>
                <th width="8%">Lab</th>
                <th width="12%">Tgl Masuk</th>
                <th width="15%">Pelapor</th>
                <th width="25%">Item Barang & Kerusakan</th>
                <th width="12%">Tindak Lanjut</th>
                <th width="11%">Status</th>
                <th width="12%">Tgl Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $index => $lap)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>Lab {{ $lap->lab_nama }}</strong></td>
                    <td>
                        {{ $lap->tanggal_laporan?->format('d/m/Y') }}
                        @if($lap->tanggal_kejadian)
                            <div style="font-size: 9px; color: #666;">Kejadian: {{ $lap->tanggal_kejadian->format('d/m/Y') }}</div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $lap->nama_pelapor ?? '-' }}</strong>
                        @if($lap->jabatan_pelapor)
                            <div style="font-size: 9px; color: #555;">{{ $lap->jabatan_pelapor }}</div>
                        @endif
                        @if($lap->bagian)
                            <div style="font-size: 9px; color: #777;">{{ $lap->bagian }}</div>
                        @endif
                    </td>
                    <td>
                        @if($lap->bentuk_ketidaksesuaian)
                            <div style="font-weight: bold; color: #d97706; margin-bottom: 3px;">[{{ $lap->bentuk_ketidaksesuaian }}]</div>
                        @endif
                        @foreach($lap->items as $item)
                            <div>• <strong>{{ $item->nama_barang }}</strong> ({{ $item->jumlah }} {{ $item->satuan }})</div>
                            @if($item->deskripsi)
                                <div style="font-size: 10px; color: #666;">- {{ $item->deskripsi }}</div>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($lap->items as $item)
                            <div style="text-transform: capitalize;">{{ $item->jenis_tindak_lanjut }}</div>
                        @endforeach
                    </td>
                    <td>
                        {{ ucwords(str_replace('_', ' ', $lap->status)) }}
                        @if($lap->status_akhir)
                            <div style="font-size: 9px; font-weight: bold; color: {{ $lap->status_akhir === 'closed' ? '#059669' : '#d97706' }};">
                                ({{ strtoupper($lap->status_akhir) }})
                            </div>
                        @endif
                    </td>
                    <td>{{ $lap->tanggal_selesai ? $lap->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data laporan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
