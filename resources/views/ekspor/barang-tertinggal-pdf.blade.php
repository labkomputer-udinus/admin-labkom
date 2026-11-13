<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Barang Tertinggal LabKom</title>
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
        .badge-belum { background: #fef3c7; color: #92400e; }
        .badge-diumumkan { background: #e0f2fe; color: #0369a1; }
        .badge-diambil { background: #d1fae5; color: #065f46; }
        .badge-satpam { background: #f1f5f9; color: #475569; }
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
                    <h2 style="margin: 0; font-size: 15px; text-transform: uppercase;">Universitas Dian Nuswantoro — Rekapitulasi Barang Tertinggal</h2>
                    <p style="margin: 3px 0 0; font-size: 10px; color: #555;">Laboratorium Komputer | Dicetak pada: {{ date('d F Y H:i') }}</p>
                    @if(!empty($filterInfo['lab']) || !empty($filterInfo['status']) || !empty($filterInfo['search']))
                        <p class="filter-summary">
                            Filter Aktif: 
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
                <th width="4%">No</th>
                <th width="10%">ID Record</th>
                <th width="10%">Tgl Temu</th>
                <th width="10%">Lab / Lokasi</th>
                <th width="20%">Nama Barang & Keterangan</th>
                <th width="12%">Penemu</th>
                <th width="12%">Status</th>
                <th width="12%">Pemilik / No HP</th>
                <th width="10%">Penyerahan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>#BT-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                    <td>{{ $item->tanggal_ditemukan?->format('d/m/Y') }}</td>
                    <td><strong>{{ $item->lab_nama }}</strong></td>
                    <td>
                        <strong>{{ $item->nama_barang }}</strong>
                        @if($item->keterangan)
                            <div style="font-size: 9px; color: #555; margin-top: 2px;">{{ $item->keterangan }}</div>
                        @endif
                        @if($item->catatan)
                            <div style="font-size: 9px; color: #777; font-style: italic;">Cat: {{ $item->catatan }}</div>
                        @endif
                    </td>
                    <td>{{ $item->penemu }}</td>
                    <td>
                        @php
                            $badgeClass = match($item->status) {
                                'belum_diambil' => 'badge-belum',
                                'diumumkan' => 'badge-diumumkan',
                                'sudah_diambil' => 'badge-diambil',
                                'diserahkan_ke_satpam' => 'badge-satpam',
                                default => 'badge-satpam',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ \App\Constants\StatusBarangTertinggal::label($item->status) }}
                        </span>
                    </td>
                    <td>
                        @if($item->pemilik)
                            <strong>{{ $item->pemilik }}</strong>
                            @if($item->no_hp)
                                <div style="font-size: 9px; color: #555;">{{ $item->no_hp }}</div>
                            @endif
                        @else
                            <span style="color: #999;">Belum diketahui</span>
                        @endif
                    </td>
                    <td>
                        @if($item->tanggal_pengambilan)
                            <div>{{ $item->tanggal_pengambilan->format('d/m/Y') }}</div>
                            @if($item->penyerah)
                                <div style="font-size: 9px; color: #555;">Oleh: {{ $item->penyerah }}</div>
                            @endif
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px; color: #666;">
                        Tidak ada data barang tertinggal yang sesuai filter.
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
