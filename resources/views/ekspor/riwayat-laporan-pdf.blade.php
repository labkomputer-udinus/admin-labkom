<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Penanganan Laporan #LAP-{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }} - Lab {{ $laporan->lab_nama }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Udinus.png') }}">
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 11px; color: #111; margin: 25px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 12px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; color: #0f172a; }
        .header p { margin: 3px 0 0; font-size: 10px; color: #64748b; }
        
        .section-title { font-size: 12px; font-weight: bold; text-transform: uppercase; margin-top: 18px; margin-bottom: 6px; padding-bottom: 3px; border-bottom: 1.5px solid #cbd5e1; color: #1e293b; }
        
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .meta-table td { padding: 5px 8px; vertical-align: top; border: 1px solid #e2e8f0; font-size: 11px; }
        .meta-label { width: 22%; background-color: #f8fafc; font-weight: bold; color: #475569; }
        
        .data-table { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 16px; }
        .data-table th, .data-table td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; vertical-align: top; }
        .data-table th { background-color: #f1f5f9; font-weight: bold; font-size: 10px; text-transform: uppercase; color: #334155; }
        
        .badge { display: inline-block; padding: 2px 7px; border-radius: 4px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .badge-closed { background-color: #d1fae5; color: #065f46; }
        .badge-followup { background-color: #fef3c7; color: #92400e; }
        .badge-step { background-color: #e0f2fe; color: #0369a1; border-radius: 3px; padding: 1px 5px; font-size: 9px; font-weight: bold; }
        .badge-action { background-color: #ede9fe; color: #6d28d9; border-radius: 3px; padding: 1px 5px; font-size: 9px; font-weight: bold; }
        
        .item-card { border: 1px solid #94a3b8; border-radius: 6px; padding: 10px; margin-bottom: 16px; background-color: #fff; page-break-inside: avoid; }
        .item-header { font-size: 12px; font-weight: bold; color: #0f172a; margin-bottom: 6px; padding-bottom: 4px; border-bottom: 1px dashed #cbd5e1; }
        
        .print-btn { margin-bottom: 15px; padding: 7px 16px; background: #0284c7; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 11px; }
        .print-btn:hover { background: #0369a1; }
        @media print { .print-btn { display: none; } body { margin: 10px; } }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Cetak Riwayat Laporan / Simpan PDF (Ctrl+P)</button>

    <div class="header">
        <table style="width: 100%; border: none; margin-bottom: 5px;">
            <tr>
                <td style="width: 70px; border: none; vertical-align: middle; text-align: left; padding: 0;">
                    <img src="{{ asset('images/Logo Udinus.png') }}" alt="Logo Udinus" style="width: 60px; height: 60px; object-fit: contain;">
                </td>
                <td style="border: none; vertical-align: middle; text-align: center; padding: 0;">
                    <h2 style="margin: 0; font-size: 16px; text-transform: uppercase; color: #0f172a;">Universitas Dian Nuswantoro — Laboratorium Komputer</h2>
                    <h3 style="margin: 4px 0 0; font-size: 13px; font-weight: normal; color: #334155;">Dokumen Rekam Jejak & Kronologi Penanganan Permasalahan</h3>
                    <p style="margin: 3px 0 0; font-size: 10px; color: #64748b;">Dicetak: {{ date('d F Y, H:i') }} | ID Dokumen: #LAP-{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}</p>
                </td>
                <td style="width: 70px; border: none; padding: 0;"></td>
            </tr>
        </table>
    </div>

    <!-- Section 1: Metadata Form Fisik -->
    <div class="section-title">1. Data Form & Identitas Laporan</div>
    <table class="meta-table">
        <tr>
            <td class="meta-label">ID Laporan</td>
            <td width="28%"><strong>#LAP-{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
            <td class="meta-label">Laboratorium</td>
            <td width="28%"><strong>Lab {{ $laporan->lab_nama }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">Bentuk Ketidaksesuaian</td>
            <td><strong>{{ $laporan->bentuk_ketidaksesuaian ?? '-' }}</strong></td>
            <td class="meta-label">Tanggal Laporan</td>
            <td>{{ $laporan->tanggal_laporan?->format('d F Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Nama Pelapor</td>
            <td>{{ $laporan->nama_pelapor ?? '-' }}</td>
            <td class="meta-label">Tanggal Kejadian</td>
            <td>{{ $laporan->tanggal_kejadian?->format('d F Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Jabatan & Bagian</td>
            <td>{{ $laporan->jabatan_pelapor ?? '-' }} {{ $laporan->bagian ? '('.$laporan->bagian.')' : '' }}</td>
            <td class="meta-label">Status Terkini</td>
            <td>
                <strong>{{ ucwords(str_replace('_', ' ', $laporan->status)) }}</strong>
                @if($laporan->status_akhir)
                    <span class="badge {{ $laporan->status_akhir === 'closed' ? 'badge-closed' : 'badge-followup' }}">
                        {{ strtoupper($laporan->status_akhir) }}
                    </span>
                @endif
            </td>
        </tr>
        @if($laporan->uraian_pengamatan)
        <tr>
            <td class="meta-label">Uraian Pengamatan</td>
            <td colspan="3">{{ $laporan->uraian_pengamatan }}</td>
        </tr>
        @endif
        @if($laporan->tindakan_langsung)
        <tr>
            <td class="meta-label">Tindakan Langsung (Laboran)</td>
            <td colspan="3">{{ $laporan->tindakan_langsung }}</td>
        </tr>
        @endif
        @if($laporan->permintaan_tindakan)
        <tr>
            <td class="meta-label">Permintaan Tindakan Lanjut</td>
            <td colspan="3">{{ $laporan->permintaan_tindakan }}</td>
        </tr>
        @endif
    </table>

    <!-- Section 2: Kronologi Penanganan Per Item -->
    <div class="section-title">2. Detail Item & Kronologi Solusi</div>

    @foreach($laporan->items as $index => $item)
        <div class="item-card">
            <div class="item-header">
                Item #{{ $index + 1 }}: {{ $item->nama_barang }} ({{ $item->jumlah }} {{ $item->satuan }}) — 
                <span style="font-weight: normal; color: #475569;">
                    Tindak Lanjut: <strong>{{ ucfirst($item->jenis_tindak_lanjut) }}</strong> | 
                    Status Terkini: <strong>{{ ucwords(str_replace('_', ' ', $item->status_item)) }}</strong>
                </span>
            </div>

            @if($item->deskripsi)
                <p style="margin: 4px 0 8px; font-size: 10.5px; color: #475569;">
                    <strong>Deskripsi Kerusakan:</strong> {{ $item->deskripsi }}
                </p>
            @endif

            <table class="data-table">
                <thead>
                    <tr>
                        <th width="4%">No</th>
                        <th width="18%">Tahap & Kategori</th>
                        <th width="15%">Aktor / Petugas</th>
                        <th width="14%">Waktu Tindakan</th>
                        <th width="35%">Uraian Tindakan / Hasil Solusi</th>
                        <th width="14%">Perubahan Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($item->penanganans as $pIndex => $p)
                        <tr>
                            <td style="text-align: center;"><strong>{{ $pIndex + 1 }}</strong></td>
                            <td>
                                <strong>{{ $p->tahap }}</strong>
                                @if($p->jenis_tindakan)
                                    <div style="margin-top: 2px;">
                                        <span class="badge-action">{{ \App\Constants\JenisTindakan::label($p->jenis_tindakan) }}</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $p->aktor_peran ?? ($p->petugas ?? 'Admin Lab') }}</strong>
                            </td>
                            <td>
                                <div>{{ $p->tanggal_tindakan?->format('d/m/Y H:i') ?? ($p->tanggal_waktu?->format('d/m/Y H:i') ?? '-') }}</div>
                                @if($p->tanggal_hasil)
                                    <div style="font-size: 9px; color: #059669;">Selesai: {{ $p->tanggal_hasil->format('d/m/Y H:i') }}</div>
                                @endif
                            </td>
                            <td>{{ $p->tindakan_atau_hasil }}</td>
                            <td>
                                @if($p->status_sebelum && $p->status_sebelum !== $p->status_item_saat_itu)
                                    <span style="color: #64748b; font-size: 9.5px; text-decoration: line-through;">{{ str_replace('_', ' ', $p->status_sebelum) }}</span>
                                    <span style="font-size: 10px;">&rarr;</span>
                                @endif
                                <strong>{{ $p->status_item_saat_itu ? ucwords(str_replace('_', ' ', $p->status_item_saat_itu)) : '-' }}</strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b;">Belum ada riwayat penanganan untuk item ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

    <!-- Footer Tanda Tangan Ringkas -->
    <div style="margin-top: 30px; page-break-inside: avoid;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; text-align: center;">
                    <p style="margin: 0; color: #64748b;">Pelapor / Laboran,</p>
                    <div style="height: 50px;"></div>
                    <p style="margin: 0; font-weight: bold;">( {{ $laporan->nama_pelapor ?? 'Laboran' }} )</p>
                    <p style="margin: 0; font-size: 9.5px; color: #64748b;">{{ $laporan->jabatan_pelapor ?? 'Laboran LabKom' }}</p>
                </td>
                <td style="width: 50%; border: none; text-align: center;">
                    <p style="margin: 0; color: #64748b;">Mengetahui / Admin LabKom,</p>
                    <div style="height: 50px;"></div>
                    <p style="margin: 0; font-weight: bold;">( {{ auth()->user()->name ?? 'Admin LabKom' }} )</p>
                    <p style="margin: 0; font-size: 9.5px; color: #64748b;">Laboratorium Komputer UDINUS</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
