<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Inventaris Stok Gudang LabKom</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Udinus.png') }}">
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 11px; color: #111; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 3px 0 0; font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .kritis { color: #d32f2f; font-weight: bold; }
        .aman { color: #2e7d32; font-weight: bold; }
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
                    <h2 style="margin: 0; font-size: 15px; text-transform: uppercase;">Universitas Dian Nuswantoro — Rekap Stok Gudang LabKom</h2>
                    <p style="margin: 3px 0 0; font-size: 10px; color: #555;">Dicetak pada: {{ date('d F Y H:i') }} | Sistem LabKom Admin</p>
                    @if(!empty($filterInfo['kategori']) || !empty($filterInfo['status_stok']) || !empty($filterInfo['search']))
                        <p class="filter-summary">
                            Filter Aktif:
                            @if(!empty($filterInfo['kategori'])) [Kategori: {{ $filterInfo['kategori'] }}] @endif
                            @if(!empty($filterInfo['status_stok'])) [Status: {{ strtoupper($filterInfo['status_stok']) }}] @endif
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
                <th width="4%">No</th>
                <th width="22%">Nama Barang</th>
                <th width="16%">Spesifikasi</th>
                <th width="14%">Kategori</th>
                <th width="11%">Stok Min</th>
                <th width="11%">Stok Fisik</th>
                <th width="10%">Status</th>
                <th width="12%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stoks as $index => $stok)
                @php $isKritis = $stok->isKritis(); @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $stok->barangMaster->nama_barang }}</strong></td>
                    <td>{{ $stok->barangMaster->spesifikasi ?? '-' }}</td>
                    <td>{{ $stok->barangMaster->kategori ?? '-' }}</td>
                    <td>{{ $stok->barangMaster->stok_minimum }} {{ $stok->barangMaster->satuan }}</td>
                    <td class="{{ $isKritis ? 'kritis' : 'aman' }}">
                        <strong>{{ $stok->stok_saat_ini }} {{ $stok->barangMaster->satuan }}</strong>
                    </td>
                    <td class="{{ $isKritis ? 'kritis' : 'aman' }}">
                        {{ $isKritis ? 'KRITIS' : 'AMAN' }}
                    </td>
                    <td style="font-size: 10px; color: #555;">
                        {{ $stok->catatan ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px; color: #666;">Tidak ada data stok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
