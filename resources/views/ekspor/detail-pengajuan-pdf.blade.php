<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pengajuan Pengadaan {{ $pengajuan->nomor_pengajuan }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo Udinus.png') }}">
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 11px; color: #111; margin: 25px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 12px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; color: #0f172a; }
        .header h3 { margin: 4px 0 0; font-size: 13px; font-weight: normal; color: #334155; }
        .header p { margin: 3px 0 0; font-size: 10px; color: #64748b; }
        
        .section-title { font-size: 12px; font-weight: bold; text-transform: uppercase; margin-top: 18px; margin-bottom: 6px; padding-bottom: 3px; border-bottom: 1.5px solid #cbd5e1; color: #1e293b; }
        
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .meta-table td { padding: 6px 8px; vertical-align: top; border: 1px solid #e2e8f0; font-size: 11px; }
        .meta-label { width: 22%; background-color: #f8fafc; font-weight: bold; color: #475569; }
        
        .data-table { width: 100%; border-collapse: collapse; margin-top: 8px; margin-bottom: 16px; }
        .data-table th, .data-table td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; vertical-align: top; }
        .data-table th { background-color: #f1f5f9; font-weight: bold; font-size: 10px; text-transform: uppercase; color: #334155; }
        .data-table tfoot td { font-weight: bold; background-color: #f8fafc; }
        
        .badge { display: inline-block; padding: 2px 7px; border-radius: 4px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .badge-draft { background-color: #f1f5f9; color: #475569; }
        .badge-diajukan { background-color: #e0f2fe; color: #0369a1; }
        .badge-diproses { background-color: #fef3c7; color: #92400e; }
        .badge-disetujui { background-color: #d1fae5; color: #065f46; }
        .badge-ditolak { background-color: #fee2e2; color: #991b1b; }
        .badge-selesai { background-color: #ede9fe; color: #5b21b6; }
        
        .badge-sesuai { background-color: #d1fae5; color: #065f46; }
        .badge-selisih { background-color: #fef3c7; color: #92400e; }
        .badge-tidak-sesuai { background-color: #fee2e2; color: #991b1b; }
        .badge-belum { background-color: #f1f5f9; color: #64748b; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .print-btn { margin-bottom: 15px; padding: 7px 16px; background: #0284c7; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 11px; }
        .print-btn:hover { background: #0369a1; }
        @media print { 
            .print-btn { display: none; } 
            body { margin: 10px; } 
            @page { size: portrait; margin: 12mm; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Cetak Dokumen / Simpan PDF (Ctrl+P)</button>

    <div class="header">
        <table style="width: 100%; border: none; margin-bottom: 5px;">
            <tr>
                <td style="width: 70px; border: none; vertical-align: middle; text-align: left; padding: 0;">
                    <img src="{{ asset('images/Logo Udinus.png') }}" alt="Logo Udinus" style="width: 60px; height: 60px; object-fit: contain;">
                </td>
                <td style="border: none; vertical-align: middle; text-align: center; padding: 0;">
                    <h2>Universitas Dian Nuswantoro — Laboratorium Komputer</h2>
                    <h3>Dokumen Detail Pengajuan & Verifikasi Penerimaan Barang</h3>
                    <p>Dicetak: {{ date('d F Y, H:i') }} | Nomor Dokumen: {{ $pengajuan->nomor_pengajuan }}</p>
                </td>
                <td style="width: 70px; border: none; padding: 0;"></td>
            </tr>
        </table>
    </div>

    <!-- Section 1: Informasi Dokumen Pengajuan -->
    <div class="section-title">1. Data Pengajuan & Status Keputusan</div>
    <table class="meta-table">
        <tr>
            <td class="meta-label">Nomor Pengajuan</td>
            <td width="28%"><strong>{{ $pengajuan->nomor_pengajuan }}</strong></td>
            <td class="meta-label">Divisi / Vendor Tujuan</td>
            <td width="28%"><strong>{{ $pengajuan->divisi_tujuan }}</strong></td>
        </tr>
        <tr>
            <td class="meta-label">Tanggal Ajuan</td>
            <td>{{ $pengajuan->tanggal_ajuan?->format('d F Y') ?? '-' }}</td>
            <td class="meta-label">Status Pengajuan</td>
            <td>
                @php
                    $statusClass = match(strtolower($pengajuan->status ?? '')) {
                        'disetujui' => 'badge-disetujui',
                        'ditolak'   => 'badge-ditolak',
                        'diproses'  => 'badge-diproses',
                        'selesai'   => 'badge-selesai',
                        'diajukan'  => 'badge-diajukan',
                        default     => 'badge-draft',
                    };
                @endphp
                <span class="badge {{ $statusClass }}">
                    {{ strtoupper(str_replace('_', ' ', $pengajuan->status ?? 'DRAFT')) }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="meta-label">Tanggal Keputusan</td>
            <td>{{ $pengajuan->tanggal_keputusan?->format('d F Y') ?? '-' }}</td>
            <td class="meta-label">Catatan Keputusan</td>
            <td>{{ $pengajuan->catatan_keputusan ?: '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Total Item Barang</td>
            <td><strong>{{ $pengajuan->items->count() }} Item</strong></td>
            <td class="meta-label">Rekap Kuantitas</td>
            <td>
                Diajukan: <strong>{{ $pengajuan->items->sum('jumlah_diajukan') }} unit</strong> | 
                Diterima: <strong>{{ $pengajuan->items->sum('jumlah_diterima') ?: 0 }} unit</strong>
            </td>
        </tr>
    </table>

    <!-- Section 2: Tabel Rincian Item Barang -->
    <div class="section-title">2. Rincian Item Barang & Verifikasi Penerimaan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%" class="text-center">No</th>
                <th width="18%">Nama Barang</th>
                <th width="16%">Spesifikasi Diajukan</th>
                <th width="9%" class="text-center">Diajukan</th>
                <th width="9%" class="text-center">Diterima</th>
                <th width="16%">Spesifikasi Diterima</th>
                <th width="12%" class="text-center">Kesesuaian</th>
                <th width="16%">Keterangan / Selisih</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuan->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->nama_barang }}</strong>
                        @if($item->laporanItem && $item->laporanItem->laporan)
                            <div style="font-size: 9px; color: #64748b; margin-top: 2px;">
                                Sumber: Lab {{ $item->laporanItem->laporan->lab_nama }} (#LAP-{{ str_pad($item->laporanItem->laporan->id, 4, '0', STR_PAD_LEFT) }})
                            </div>
                        @endif
                    </td>
                    <td>{{ $item->spesifikasi ?: '-' }}</td>
                    <td class="text-center">
                        <strong>{{ $item->jumlah_diajukan }}</strong> {{ $item->satuan }}
                    </td>
                    <td class="text-center">
                        @if($item->jumlah_diterima !== null)
                            <strong>{{ $item->jumlah_diterima }}</strong> {{ $item->satuan }}
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                    <td>{{ $item->spesifikasi_diterima ?: '-' }}</td>
                    <td class="text-center">
                        @if($item->status_kesesuaian)
                            @php
                                $badgeKesesuaian = match(strtolower($item->status_kesesuaian)) {
                                    'sesuai' => 'badge-sesuai',
                                    'sesuai_beda_spesifikasi', 'selisih_spesifikasi', 'selisih_jumlah' => 'badge-selisih',
                                    'tidak_sesuai' => 'badge-tidak-sesuai',
                                    default => 'badge-belum',
                                };
                                $labelKesesuaian = match(strtolower($item->status_kesesuaian)) {
                                    'sesuai' => 'SESUAI (PENUH)',
                                    'sesuai_beda_spesifikasi' => 'BEDA MERK/SPEK',
                                    'tidak_sesuai' => 'TIDAK SESUAI',
                                    default => strtoupper(str_replace('_', ' ', $item->status_kesesuaian)),
                                };
                            @endphp
                            <span class="badge {{ $badgeKesesuaian }}">
                                {{ $labelKesesuaian }}
                            </span>
                        @else
                            <span class="badge badge-belum">BELUM DICEK</span>
                        @endif
                        @if($item->tanggal_terima)
                            <div style="font-size: 8.5px; color: #64748b; margin-top: 3px;">
                                Tgl: {{ $item->tanggal_terima->format('d/m/Y') }}
                            </div>
                        @endif
                    </td>
                    <td>{{ $item->catatan_selisih ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="color: #64748b; padding: 14px;">Belum ada item barang yang tercatat pada pengajuan ini.</td>
                </tr>
            @endforelse
        </tbody>
        @if($pengajuan->items->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">Total Keseluruhan:</td>
                    <td class="text-center">{{ $pengajuan->items->sum('jumlah_diajukan') }}</td>
                    <td class="text-center">{{ $pengajuan->items->sum('jumlah_diterima') ?: 0 }}</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- Section 3: Footer Tanda Tangan Ringkas -->
    <div style="margin-top: 35px; page-break-inside: avoid;">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 50%; border: none; text-align: center;">
                    <p style="margin: 0; color: #64748b;">Pemohon / Pengaju Logistik,</p>
                    <div style="height: 55px;"></div>
                    <p style="margin: 0; font-weight: bold;">( {{ auth()->user()->name ?? 'Admin LabKom' }} )</p>
                    <p style="margin: 0; font-size: 9.5px; color: #64748b;">Laboratorium Komputer UDINUS</p>
                </td>
                <td style="width: 50%; border: none; text-align: center;">
                    <p style="margin: 0; color: #64748b;">Mengetahui / Pihak Verifikator,</p>
                    <div style="height: 55px;"></div>
                    <p style="margin: 0; font-weight: bold;">( {{ $pengajuan->divisi_tujuan ?: 'Divisi Logistik / Pengadaan' }} )</p>
                    <p style="margin: 0; font-size: 9.5px; color: #64748b;">Universitas Dian Nuswantoro</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
