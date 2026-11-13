<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Pengajuan Pengadaan LabKom</title>
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
        .badge-draft { background: #f1f5f9; color: #475569; }
        .badge-diajukan { background: #e0f2fe; color: #0369a1; }
        .badge-diproses { background: #fef3c7; color: #92400e; }
        .badge-disetujui { background: #d1fae5; color: #065f46; }
        .badge-ditolak { background: #fee2e2; color: #991b1b; }
        .badge-selesai { background: #ede9fe; color: #5b21b6; }
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
                    <h2 style="margin: 0; font-size: 15px; text-transform: uppercase;">Universitas Dian Nuswantoro — Rekapitulasi Pengajuan Pengadaan</h2>
                    <p style="margin: 3px 0 0; font-size: 10px; color: #555;">Laboratorium Komputer | Dicetak pada: {{ date('d F Y H:i') }}</p>
                    @if(!empty($filterInfo['periode']) || !empty($filterInfo['status']) || !empty($filterInfo['search']))
                        <p class="filter-summary">
                            Filter Aktif:
                            @if(!empty($filterInfo['periode'])) [Periode: {{ $filterInfo['periode'] }}] @endif
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
                <th width="15%">No. Pengajuan</th>
                <th width="10%">Tgl Ajuan</th>
                <th width="14%">Divisi Tujuan</th>
                <th width="29%">Item Barang & Jumlah</th>
                <th width="10%">Status</th>
                <th width="18%">Keputusan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $index => $p)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $p->nomor_pengajuan }}</strong></td>
                    <td>{{ $p->tanggal_ajuan?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $p->divisi_tujuan ?? '-' }}</td>
                    <td>
                        @forelse($p->items as $item)
                            <div style="margin-bottom: 3px;">
                                • <strong>{{ $item->nama_barang }}</strong> ({{ $item->jumlah }} {{ $item->satuan }})
                                @if($item->spesifikasi)
                                    <span style="font-size: 9px; color: #555;"> - {{ $item->spesifikasi }}</span>
                                @endif
                                @if($item->alasan_pengadaan)
                                    <div style="font-size: 9px; color: #777; margin-left: 8px;">Alasan: {{ $item->alasan_pengadaan }}</div>
                                @endif
                            </div>
                        @empty
                            <span style="color: #999;">-</span>
                        @endforelse
                    </td>
                    <td>
                        @php
                            $badgeClass = match($p->status) {
                                'draft' => 'badge-draft',
                                'diajukan' => 'badge-diajukan',
                                'diproses' => 'badge-diproses',
                                'disetujui' => 'badge-disetujui',
                                'ditolak' => 'badge-ditolak',
                                'selesai' => 'badge-selesai',
                                default => 'badge-draft',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ strtoupper(str_replace('_', ' ', $p->status)) }}
                        </span>
                    </td>
                    <td>
                        @if($p->tanggal_keputusan)
                            <div style="font-size: 9px; font-weight: bold;">{{ $p->tanggal_keputusan->format('d/m/Y') }}</div>
                        @endif
                        @if($p->catatan_keputusan)
                            <div style="font-size: 9px; color: #555;">{{ $p->catatan_keputusan }}</div>
                        @elseif(!$p->tanggal_keputusan)
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #666;">
                        Tidak ada data pengajuan pengadaan yang sesuai filter.
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
