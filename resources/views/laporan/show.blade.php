@extends('layouts.app')

@section('title', 'Detail Laporan #' . $laporan->id . ' — Lab ' . $laporan->lab_nama)
@section('header_title', 'Detail & Tracking Progres Laporan Lab ' . $laporan->lab_nama)

@section('content')
<div class="space-y-6 max-w-6xl mx-auto pb-16">

    <!-- Context Bar: Back Link and Action Buttons -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('laporan.index') }}"
            class="inline-flex items-center gap-2 text-xs font-semibold text-muted-foreground hover:text-primary transition-colors group">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
            </svg>
            <span>Kembali ke Rekap Laporan</span>
        </a>

        <div class="flex items-center gap-2 flex-wrap">
            <!-- Button Cetak PDF -->
            <a href="{{ route('ekspor.laporan.detail.pdf', $laporan) }}" target="_blank"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-foreground bg-card border border-border rounded-lg shadow-xs hover:bg-muted transition-colors">
                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
                <span>Cetak / PDF</span>
            </a>

            <!-- Button Ekspor CSV -->
            <a href="{{ route('ekspor.laporan.detail.excel', $laporan) }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-foreground bg-card border border-border rounded-lg shadow-xs hover:bg-muted transition-colors">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
                <span>Ekspor CSV</span>
            </a>

            <span class="w-px h-5 bg-border mx-1"></span>

            <!-- Edit Ticket Button -->
            <a href="{{ route('laporan.edit', $laporan) }}"
                class="p-1.5 text-muted-foreground hover:text-primary hover:bg-primary/10 rounded-md border border-border bg-card transition-colors"
                title="Edit Laporan">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
            </a>

            <!-- Delete Ticket Button -->
            <form method="POST" action="{{ route('laporan.destroy', $laporan) }}" class="inline"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="p-1.5 text-muted-foreground hover:text-rose-600 hover:bg-rose-500/10 rounded-md border border-border bg-card transition-colors"
                    title="Hapus Laporan">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Stepper Progress Tracker -->
    @php
        $statusOrder = [
            'dicatat'                   => 1,
            'ditriase'                  => 2,
            'dalam_proses'              => 3,
            'menunggu_keputusan_divisi' => 3,
            'barang_diterima'           => 3,
            'siap_distribusi'           => 4,
            'selesai'                   => 5,
        ];
        $currentStepNumber = $statusOrder[$laporan->status] ?? 1;

        $macroSteps = [
            1 => ['label' => 'Laporan Masuk', 'desc' => 'Dicatat laboran'],
            2 => ['label' => 'Triase & Diagnosa', 'desc' => 'Pemeriksaan awal'],
            3 => ['label' => 'Penanganan / Logistik', 'desc' => 'Troubleshooting / pengadaan'],
            4 => ['label' => 'Distribusi Barang', 'desc' => 'Pemasangan ke lab'],
            5 => ['label' => 'Selesai', 'desc' => 'Tuntas & terverifikasi'],
        ];
    @endphp

    <section class="bg-card rounded-2xl border border-border shadow-xs p-6" data-purpose="stepper-progress-card">
        <div class="relative">
            <!-- Continuous Connection Bar -->
            <div class="hidden md:block absolute top-5 left-10 right-10 h-1 bg-border -z-0">
                <div class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ max(0, min(100, (($currentStepNumber - 1) / 4) * 100)) }}%;"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 relative z-10 text-center">
                @foreach($macroSteps as $stepNum => $stepData)
                    @php
                        $isPassed  = $currentStepNumber > $stepNum;
                        $isCurrent = $currentStepNumber === $stepNum;
                        $isNext    = $currentStepNumber < $stepNum;
                    @endphp

                    <div class="flex flex-col items-center {{ $stepNum === 5 ? 'col-span-2 md:col-span-1' : '' }}">
                        @if($isPassed)
                            <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-card">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                            <h3 class="mt-3 text-xs font-bold text-foreground">{{ $stepData['label'] }}</h3>
                            <p class="text-[11px] text-muted-foreground font-medium">{{ $stepData['desc'] }}</p>
                        @elseif($isCurrent)
                            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-md ring-4 ring-primary/20 {{ $stepNum === 5 ? 'animate-pulse' : '' }}">
                                @if($stepNum === 5)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                    </svg>
                                @else
                                    <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                @endif
                            </div>
                            <h3 class="mt-3 text-xs font-bold text-primary">{{ $stepData['label'] }}</h3>
                            <p class="text-[11px] text-muted-foreground font-medium">{{ $stepData['desc'] }}</p>
                        @else
                            <div class="w-10 h-10 rounded-full bg-muted text-muted-foreground flex items-center justify-center font-bold text-xs ring-4 ring-card border border-border">
                                {{ $stepNum }}
                            </div>
                            <h3 class="mt-3 text-xs font-bold text-muted-foreground">{{ $stepData['label'] }}</h3>
                            <p class="text-[11px] text-muted-foreground font-medium">{{ $stepData['desc'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Ticket Overview Card -->
    <section class="bg-card rounded-2xl border border-border shadow-xs overflow-hidden" data-purpose="ticket-overview-card">
        <div class="p-6">
            <!-- Top Row: Lab badge + name + status -->
            <div class="flex flex-wrap items-center justify-between gap-4 pb-5 border-b border-border/80">
                <div class="flex items-center gap-3.5">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-black text-xl tracking-tight shadow-xs">
                        {{ $laporan->lab_nama }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl font-bold text-foreground tracking-tight">Laboratorium {{ $laporan->lab_nama }}</h2>
                            <x-status-badge :status="$laporan->status" />
                            @if($laporan->status_akhir === 'closed')
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20">Closed</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-muted-foreground">
                            <span>ID Laporan: <strong class="text-foreground font-mono">#LAP-{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}</strong></span>
                            <span>•</span>
                            <span>Pelapor: <strong class="text-foreground">{{ $laporan->nama_pelapor ?? 'Laboran' }}</strong>
                                @if($laporan->jabatan_pelapor)
                                    <span>({{ $laporan->jabatan_pelapor }})</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Date Metadata Grid -->
                <div class="grid grid-cols-3 gap-2 text-center w-full sm:w-auto">
                    <div class="bg-muted/40 border border-border rounded-xl px-3.5 py-2">
                        <span class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">TGL KEJADIAN</span>
                        <span class="text-xs font-bold text-foreground">{{ $laporan->tanggal_kejadian ? $laporan->tanggal_kejadian->format('d M Y') : '—' }}</span>
                    </div>
                    <div class="bg-muted/40 border border-border rounded-xl px-3.5 py-2">
                        <span class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider">TANGGAL MASUK</span>
                        <span class="text-xs font-bold text-foreground">{{ $laporan->tanggal_laporan ? $laporan->tanggal_laporan->format('d M Y') : '—' }}</span>
                    </div>
                    <div class="{{ $laporan->tanggal_selesai ? 'bg-emerald-500/10 border-emerald-500/30' : 'bg-muted/40 border-border' }} border rounded-xl px-3.5 py-2">
                        <span class="block text-[10px] font-bold {{ $laporan->tanggal_selesai ? 'text-emerald-700 dark:text-emerald-400' : 'text-muted-foreground' }} uppercase tracking-wider">TANGGAL SELESAI</span>
                        <span class="text-xs font-bold {{ $laporan->tanggal_selesai ? 'text-emerald-700 dark:text-emerald-400' : 'text-muted-foreground' }}">
                            {{ $laporan->tanggal_selesai ? $laporan->tanggal_selesai->format('d M Y') : 'Belum Selesai' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Section: ISIAN FORM PERMINTAAN TINDAKAN (FISIK) -->
            @if($laporan->bentuk_ketidaksesuaian || $laporan->tindakan_langsung || $laporan->permintaan_tindakan || $laporan->uraian_pengamatan)
                <div class="mt-5 space-y-4">
                    <div class="flex items-center gap-2 text-xs font-bold text-primary uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        <span>ISIAN FORM PERMINTAAN TINDAKAN (FISIK)</span>
                    </div>

                    <!-- Problem Highlight Banner -->
                    @if($laporan->bentuk_ketidaksesuaian)
                        <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-amber-700 dark:text-amber-400">BENTUK KETIDAKSESUAIAN / POKOK MASALAH</span>
                            <p class="text-sm font-semibold text-foreground mt-0.5">{{ $laporan->bentuk_ketidaksesuaian }}</p>
                        </div>
                    @endif

                    <!-- Dual Columns: Direct Actions & Prevention -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-muted/30 border border-border rounded-xl p-4">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-muted-foreground">TINDAKAN LANGSUNG YANG SUDAH DICOBA (LABORAN)</span>
                            <p class="text-xs font-medium text-foreground mt-1 leading-relaxed">{{ $laporan->tindakan_langsung ?? '—' }}</p>
                        </div>
                        <div class="bg-muted/30 border border-border rounded-xl p-4">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-muted-foreground">PERMINTAAN TINDAKAN PERBAIKAN &amp; PENCEGAHAN</span>
                            <p class="text-xs font-medium text-foreground mt-1 leading-relaxed">{{ $laporan->permintaan_tindakan ?? '—' }}</p>
                        </div>
                    </div>

                    @if($laporan->uraian_pengamatan)
                        <div class="bg-muted/30 border border-border rounded-xl p-4">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-muted-foreground">URAIAN PENGAMATAN</span>
                            <p class="text-xs font-medium text-foreground mt-1 leading-relaxed">{{ $laporan->uraian_pengamatan }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <!-- Section Header Daftar Item Permasalahan -->
    <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
        <div>
            <div class="flex items-center gap-2">
                <h3 class="text-base font-bold text-foreground tracking-tight">Daftar Item Permasalahan</h3>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-primary/10 text-primary border border-primary/20">
                    {{ $laporan->items->count() }} Item
                </span>
            </div>
            <p class="text-xs text-muted-foreground mt-0.5">Klik pada setiap item untuk melihat keluhan, aksi logistik, dan riwayat tracking langkah penanganan.</p>
        </div>

        <button type="button" onclick="openAddItemModal()"
            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-primary text-white text-xs font-semibold shadow-xs hover:bg-primary/90 transition-all active:scale-95">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Item</span>
        </button>
    </div>

    <!-- Item Cards -->
    <div class="space-y-5">
        @foreach($laporan->items as $itemIndex => $item)
            @php
                $stepCount = $item->penanganans->count();
            @endphp
            <div class="bg-card rounded-2xl border border-border shadow-xs overflow-hidden" data-purpose="item-card-{{ $item->id }}">
                <!-- Item Header Bar -->
                <div class="px-6 py-4 bg-muted/40 border-b border-border flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-primary/10 text-primary text-xs font-extrabold flex items-center justify-center border border-primary/20">
                            #{{ $itemIndex + 1 }}
                        </span>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-sm font-bold text-foreground">{{ $item->nama_barang }}</h4>
                                <span class="text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-500/10 px-2 py-0.5 rounded-full border border-rose-500/20">
                                    {{ $item->jumlah }} {{ $item->satuan }}
                                </span>
                            </div>
                            <p class="text-xs text-muted-foreground">
                                Tindak Lanjut: <span class="font-medium text-foreground capitalize">{{ $item->jenis_tindak_lanjut }}</span>
                                @if($item->deskripsi)
                                    &nbsp;•&nbsp; <span class="text-muted-foreground">{{ Str::limit($item->deskripsi, 60) }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-primary/10 text-primary border border-primary/20 px-2.5 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            {{ $stepCount }} Langkah
                        </span>

                        <x-status-badge :status="$item->status_item" />

                        <!-- Edit Item Button -->
                        <button type="button"
                            onclick="openEditItemModal({{ $item->id }}, '{{ addslashes($item->nama_barang) }}', {{ (int)$item->jumlah }}, '{{ addslashes($item->satuan) }}', '{{ addslashes($item->deskripsi ?? '') }}', '{{ addslashes($item->keterangan ?? '') }}', '{{ $item->jenis_tindak_lanjut }}', '{{ $item->status_item }}', '{{ route('laporan.item.update', [$laporan, $item]) }}')"
                            class="p-1.5 text-muted-foreground hover:text-foreground hover:bg-muted rounded-md transition-colors"
                            title="Edit Item">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </button>

                        <!-- Delete Item Button -->
                        <form method="POST" action="{{ route('laporan.item.destroy', [$laporan, $item]) }}" class="inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus item \'{{ addslashes($item->nama_barang) }}\' ini beserta seluruh riwayat langkah penanganannya?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-muted-foreground hover:text-rose-600 hover:bg-rose-500/10 rounded-md transition-colors" title="Hapus Item">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Item Body Content -->
                <div class="p-6 space-y-6">
                    <!-- Summary Box & Quick Logistics Action -->
                    <div class="border border-border rounded-xl p-4 bg-muted/20 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">KELUHAN / KERUSAKAN AWAL</span>
                            <p class="text-xs font-semibold text-foreground">{{ $item->deskripsi ?? 'Tidak ada catatan kerusakan spesifik.' }}</p>
                            @if($item->keterangan)
                                <p class="text-xs text-muted-foreground">
                                    <span class="font-medium text-foreground">Catatan Ringkas:</span> {{ $item->keterangan }}
                                </p>
                            @endif
                        </div>

                        <!-- Quick Action Buttons -->
                        @if($item->jenis_tindak_lanjut === 'penggantian')
                            <div class="flex items-center gap-2 self-end md:self-auto shrink-0">
                                <a href="{{ route('pengajuan.create') }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-amber-700 dark:text-amber-400 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                    </svg>
                                    <span>Ajukan Pengadaan</span>
                                </a>
                                <a href="{{ route('distribusi.create', ['laporan_item_id' => $item->id]) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-primary bg-primary/10 hover:bg-primary/20 border border-primary/20 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                    </svg>
                                    <span>Distribusi dari Stok</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Banner Konfirmasi Laboran (jika status = terdistribusi) -->
                    @if($item->status_item === 'terdistribusi')
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Menunggu Konfirmasi Laboran</p>
                                    <p class="text-[11px] text-emerald-700/80 dark:text-emerald-400/80 mt-0.5">
                                        Barang telah didistribusikan. Setelah diperiksa dan berfungsi baik di lab, klik konfirmasi untuk menyelesaikan item ini.
                                    </p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('laporan.item.konfirmasi-selesai', [$laporan, $item]) }}" class="shrink-0">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    onclick="return confirm('Konfirmasi bahwa barang sudah diperiksa laboran dan berfungsi baik di lapangan?')"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Konfirmasi Laboran — Selesai</span>
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Section: KRONOLOGI SOLUSI & RIWAYAT LANGKAH -->
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-foreground uppercase tracking-wider mb-4">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            <span>KRONOLOGI SOLUSI &amp; RIWAYAT LANGKAH ({{ $stepCount }} LANGKAH)</span>
                        </div>

                        <!-- Vertical Stepper Timeline -->
                        @if($item->penanganans->isNotEmpty())
                            <div class="relative pl-6 space-y-6 before:absolute before:left-3 before:top-2 before:bottom-2 before:w-0.5 before:bg-border">
                                @foreach($item->penanganans as $pIndex => $penanganan)
                                    @php
                                        $isStepDone = !empty($penanganan->tanggal_hasil);
                                    @endphp
                                    <div class="relative group">
                                        <!-- Node -->
                                        <div class="absolute -left-[1.65rem] top-1.5 w-6 h-6 rounded-full {{ $isStepDone ? 'bg-emerald-500 text-white' : 'bg-primary text-white' }} font-bold text-[11px] flex items-center justify-center ring-4 ring-card shadow-xs">
                                            @if($isStepDone)
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                    <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            @else
                                                {{ $pIndex + 1 }}
                                            @endif
                                        </div>

                                        <div class="bg-card border border-border rounded-xl p-4 shadow-xs space-y-3">
                                            <!-- Step Header -->
                                            <div class="flex flex-wrap items-center justify-between gap-2 pb-2 border-b border-border/60">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <h5 class="text-xs font-bold text-foreground">Langkah #{{ $pIndex + 1 }}: {{ $penanganan->tahap }}</h5>
                                                    @if($penanganan->jenis_tindakan)
                                                        <span class="text-[10px] font-semibold bg-primary/10 text-primary border border-primary/20 px-2 py-0.5 rounded-full">
                                                            {{ \App\Constants\JenisTindakan::label($penanganan->jenis_tindakan) }}
                                                        </span>
                                                    @endif
                                                    @if($penanganan->status_item_saat_itu)
                                                        <x-status-badge :status="$penanganan->status_item_saat_itu" />
                                                    @endif
                                                </div>
                                                <span class="text-xs text-muted-foreground font-medium">Aktor / Pihak: <strong class="text-foreground">{{ $penanganan->aktor_peran ?? ($penanganan->petugas ?? 'Admin Lab') }}</strong></span>
                                            </div>

                                            <!-- Dates Grid -->
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                                <div class="bg-muted/30 rounded-lg p-2.5 border border-border/80">
                                                    <span class="text-[10px] font-bold text-muted-foreground block uppercase">TANGGAL &amp; JAM TINDAKAN</span>
                                                    <span class="font-semibold text-foreground">
                                                        {{ $penanganan->tanggal_tindakan ? $penanganan->tanggal_tindakan->format('d M Y, H:i') : ($penanganan->tanggal_waktu ? $penanganan->tanggal_waktu->format('d M Y, H:i') : '-') }}
                                                    </span>
                                                </div>
                                                <div class="{{ $penanganan->tanggal_hasil ? 'bg-emerald-500/10 border-emerald-500/30' : 'bg-amber-500/10 border-amber-500/30' }} rounded-lg p-2.5 border">
                                                    <span class="text-[10px] font-bold {{ $penanganan->tanggal_hasil ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400' }} block uppercase">TANGGAL &amp; JAM HASIL</span>
                                                    <span class="font-semibold {{ $penanganan->tanggal_hasil ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400' }}">
                                                        {{ $penanganan->tanggal_hasil ? $penanganan->tanggal_hasil->format('d M Y, H:i') : 'Menunggu Pengerjaan / Uji Coba' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Step Result Description -->
                                            <div class="bg-muted/20 border border-border/70 rounded-lg p-3 text-xs">
                                                <span class="text-[10px] font-bold text-muted-foreground block uppercase mb-1">TINDAKAN / HASIL LANGKAH PENANGANAN:</span>
                                                <p class="text-foreground leading-relaxed whitespace-pre-line">{{ $penanganan->tindakan_atau_hasil ?? '-' }}</p>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="flex items-center justify-between pt-1">
                                                <button type="button"
                                                    onclick="toggleEditForm('edit-form-{{ $penanganan->id }}')"
                                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary hover:underline">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                                    </svg>
                                                    <span>Edit Langkah / Update Tanggal &amp; Hasil</span>
                                                </button>

                                                <form method="POST" action="{{ route('laporan.item.penanganan.destroy', [$laporan, $item, $penanganan]) }}"
                                                    onsubmit="return confirm('Hapus catatan langkah ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-muted-foreground hover:text-rose-600 transition-colors p-1" title="Hapus langkah">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Inline Edit Form (Hidden by Default) -->
                                            <div id="edit-form-{{ $penanganan->id }}" class="hidden pt-4 mt-3 border-t border-dashed border-border bg-muted/20 p-4 rounded-xl">
                                                <form method="POST" action="{{ route('laporan.item.penanganan.update', [$laporan, $item, $penanganan]) }}" class="space-y-4">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="flex items-center justify-between pb-2 border-b border-border/60">
                                                        <span class="text-xs font-bold text-foreground">Edit Langkah #{{ $pIndex + 1 }}</span>
                                                        <button type="button" onclick="toggleEditForm('edit-form-{{ $penanganan->id }}')" class="text-xs text-muted-foreground hover:text-foreground font-semibold">
                                                            ✕ Batal
                                                        </button>
                                                    </div>

                                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                                        <div>
                                                            <label class="block text-[11px] font-semibold text-foreground mb-1">Jenis Tindakan</label>
                                                            <select name="jenis_tindakan" class="w-full px-3 py-1.5 rounded-lg bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                                                @foreach($manualJenisTindakanList as $jKey => $jLbl)
                                                                    <option value="{{ $jKey }}" {{ old('jenis_tindakan', $penanganan->jenis_tindakan) === $jKey ? 'selected' : '' }}>{{ $jLbl }}</option>
                                                                @endforeach
                                                                @if(!array_key_exists($penanganan->jenis_tindakan, $manualJenisTindakanList) && $penanganan->jenis_tindakan)
                                                                    <option value="{{ $penanganan->jenis_tindakan }}" selected>{{ \App\Constants\JenisTindakan::label($penanganan->jenis_tindakan) }}</option>
                                                                @endif
                                                            </select>
                                                        </div>

                                                        <div>
                                                            <label class="block text-[11px] font-semibold text-foreground mb-1">Tahap Penanganan</label>
                                                            <input type="text" name="tahap" value="{{ $penanganan->tahap }}" required
                                                                class="w-full px-3 py-1.5 rounded-lg bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                                        </div>

                                                        <div>
                                                            <label class="block text-[11px] font-semibold text-foreground mb-1">Aktor / Pihak Pelaksana</label>
                                                            <input type="text" name="aktor_peran" value="{{ $penanganan->aktor_peran }}"
                                                                class="w-full px-3 py-1.5 rounded-lg bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                        <div>
                                                            <label class="block text-[11px] font-semibold text-foreground mb-1">Tanggal Tindakan</label>
                                                            <input type="datetime-local" name="tanggal_tindakan"
                                                                value="{{ $penanganan->tanggal_tindakan ? $penanganan->tanggal_tindakan->format('Y-m-d\TH:i') : ($penanganan->tanggal_waktu ? $penanganan->tanggal_waktu->format('Y-m-d\TH:i') : '') }}"
                                                                class="w-full px-3 py-1.5 rounded-lg bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                                        </div>

                                                        <div>
                                                            <label class="block text-[11px] font-semibold text-emerald-700 dark:text-emerald-400 mb-1">Tanggal Hasil</label>
                                                            <input type="datetime-local" name="tanggal_hasil"
                                                                value="{{ $penanganan->tanggal_hasil ? $penanganan->tanggal_hasil->format('Y-m-d\TH:i') : '' }}"
                                                                class="w-full px-3 py-1.5 rounded-lg bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label class="block text-[11px] font-semibold text-foreground mb-1">Tindakan / Hasil</label>
                                                        <textarea name="tindakan_atau_hasil" rows="3" required
                                                            class="w-full px-3 py-2 rounded-lg bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary resize-none">{{ $penanganan->tindakan_atau_hasil }}</textarea>
                                                    </div>

                                                    <div class="p-3 rounded-lg bg-card border border-border">
                                                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1">Update Status &amp; Progres Item</label>
                                                        <select name="status_item" class="w-full px-2.5 py-1.5 rounded-md bg-muted/40 border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                                                            <option value="menunggu_triase" {{ $item->status_item === 'menunggu_triase' ? 'selected' : '' }}>⏳ Menunggu Triase</option>
                                                            <option value="proses_perbaikan" {{ $item->status_item === 'proses_perbaikan' ? 'selected' : '' }}>🔧 Proses Perbaikan</option>
                                                            <option value="selesai_perbaikan" {{ $item->status_item === 'selesai_perbaikan' ? 'selected' : '' }}>✓ Selesai Perbaikan</option>
                                                            <option value="menunggu_pengadaan" {{ $item->status_item === 'menunggu_pengadaan' ? 'selected' : '' }}>📦 Menunggu Pengadaan / Penggantian</option>
                                                            <option value="terdistribusi" {{ $item->status_item === 'terdistribusi' ? 'selected' : '' }}>🚚 Terdistribusi ke Lab</option>
                                                            <option value="selesai" {{ $item->status_item === 'selesai' ? 'selected' : '' }}>✓ Selesai</option>
                                                        </select>
                                                    </div>

                                                    <div class="flex items-center justify-end gap-2 pt-1">
                                                        <button type="button" onclick="toggleEditForm('edit-form-{{ $penanganan->id }}')" class="px-3.5 py-1.5 rounded-lg border border-border text-xs font-semibold text-muted-foreground hover:text-foreground">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="px-4 py-1.5 rounded-lg bg-primary text-white text-xs font-bold shadow-xs hover:bg-primary/90">
                                                            Simpan Pembaruan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-6 rounded-xl bg-muted/20 border border-dashed border-border text-center text-xs text-muted-foreground">
                                Belum ada riwayat langkah penanganan untuk item ini.
                            </div>
                        @endif

                        <!-- Form Tambah Catatan / Langkah Penanganan Baru -->
                        <details class="group/form mt-6">
                            <summary class="w-full py-2.5 border-2 border-dashed border-border hover:border-primary/50 rounded-xl text-xs font-semibold text-muted-foreground hover:text-primary bg-muted/20 hover:bg-primary/5 flex items-center justify-center gap-2 transition-all cursor-pointer list-none select-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                                <span>Tambah Catatan / Langkah Penanganan Baru</span>
                            </summary>

                            <div class="mt-4 p-5 rounded-xl bg-muted/20 border border-border">
                                <form method="POST" action="{{ route('laporan.item.penanganan.store', [$laporan, $item]) }}" class="space-y-4">
                                    @csrf

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-semibold text-foreground mb-1">
                                                Kategori / Jenis Tindakan <span class="text-rose-500">*</span>
                                            </label>
                                            <select name="jenis_tindakan" required
                                                class="w-full px-3 py-2 rounded-lg bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                                @foreach($manualJenisTindakanList as $jKey => $jLbl)
                                                    <option value="{{ $jKey }}" {{ $jKey === 'ditugaskan_teknisi' ? 'selected' : '' }}>{{ $jLbl }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-semibold text-foreground mb-1">
                                                Nama / Judul Langkah <span class="text-rose-500">*</span>
                                            </label>
                                            <input type="text" name="tahap" required
                                                placeholder="Contoh: Pemeriksaan Teknisi / Instruksi Tindak Ulang"
                                                class="w-full px-3 py-2 rounded-lg bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-semibold text-foreground mb-1">
                                                Aktor / Pihak Pelaksana
                                            </label>
                                            <input type="text" name="aktor_peran"
                                                placeholder="Contoh: Koordinator: Pak Budi / Teknisi: Andi"
                                                class="w-full px-3 py-2 rounded-lg bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-semibold text-foreground mb-1">Tanggal Tindakan</label>
                                            <input type="datetime-local" name="tanggal_tindakan" value="{{ now()->format('Y-m-d\TH:i') }}"
                                                class="w-full px-3 py-2 rounded-lg bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-semibold text-foreground mb-1">Tanggal Hasil (Opsional)</label>
                                            <input type="datetime-local" name="tanggal_hasil"
                                                class="w-full px-3 py-2 rounded-lg bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-semibold text-foreground mb-1">
                                            Tindakan / Hasil Langkah Penanganan <span class="text-rose-500">*</span>
                                        </label>
                                        <textarea name="tindakan_atau_hasil" rows="3" required
                                            placeholder="Tuliskan tindakan yang dilakukan dan/atau hasil yang diperoleh..."
                                            class="w-full px-3 py-2 rounded-lg bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary resize-none"></textarea>
                                    </div>

                                    <div class="p-3.5 rounded-lg bg-card border border-border">
                                        <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1">
                                            Update Status &amp; Progres Item
                                        </label>
                                        <select name="status_item"
                                            class="w-full px-3 py-2 rounded-lg bg-muted/40 border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                                            <option value="menunggu_triase" {{ $item->status_item === 'menunggu_triase' ? 'selected' : '' }}>⏳ Menunggu Triase</option>
                                            <option value="proses_perbaikan" {{ $item->status_item === 'proses_perbaikan' ? 'selected' : '' }}>🔧 Proses Perbaikan</option>
                                            <option value="selesai_perbaikan" {{ $item->status_item === 'selesai_perbaikan' ? 'selected' : '' }}>✓ Selesai Perbaikan</option>
                                            <option value="menunggu_pengadaan" {{ $item->status_item === 'menunggu_pengadaan' ? 'selected' : '' }}>📦 Menunggu Pengadaan</option>
                                            <option value="terdistribusi" {{ $item->status_item === 'terdistribusi' ? 'selected' : '' }}>🚚 Terdistribusi ke Lab</option>
                                            <option value="selesai" {{ $item->status_item === 'selesai' ? 'selected' : '' }}>✓ Selesai</option>
                                        </select>
                                    </div>

                                    <div class="flex justify-end pt-2">
                                        <button type="submit"
                                            class="px-5 py-2 rounded-xl bg-primary text-white text-xs font-bold shadow-sm shadow-primary/25 hover:bg-primary/90 transition-transform active:scale-95">
                                            Simpan Langkah Penanganan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </details>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

<!-- Modal Edit Item Laporan -->
<div id="modal-edit-item" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-card border border-border rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-border">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 border border-primary/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-foreground">Edit Item Permasalahan</h3>
                    <p class="text-[11px] text-muted-foreground">Perbarui data item barang, jumlah, dan status tindak lanjut.</p>
                </div>
            </div>
            <button type="button" onclick="closeEditItemModal()" class="text-muted-foreground hover:text-foreground text-sm font-bold w-7 h-7 rounded-lg bg-muted flex items-center justify-center">
                ✕
            </button>
        </div>

        <form id="form-edit-item" method="POST" action="" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[11px] font-semibold text-foreground mb-1">Nama Barang / Komponen <span class="text-rose-500">*</span></label>
                <input type="text" id="edit-nama-barang" name="nama_barang" required
                    class="w-full px-3 py-2 rounded-xl bg-muted/40 border border-border text-foreground text-xs font-semibold focus:ring-1 focus:ring-primary">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-foreground mb-1">Jumlah <span class="text-rose-500">*</span></label>
                    <input type="number" id="edit-jumlah" name="jumlah" min="1" required
                        class="w-full px-3 py-2 rounded-xl bg-muted/40 border border-border text-foreground text-xs font-bold focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-foreground mb-1">Satuan <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit-satuan" name="satuan" required placeholder="unit / pcs"
                        class="w-full px-3 py-2 rounded-xl bg-muted/40 border border-border text-foreground text-xs focus:ring-1 focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-foreground mb-1">Keluhan / Kerusakan Awal</label>
                <textarea id="edit-deskripsi" name="deskripsi" rows="2" placeholder="Deskripsikan masalah kerusakan fisik..."
                    class="w-full px-3 py-2 rounded-xl bg-muted/40 border border-border text-foreground text-xs focus:ring-1 focus:ring-primary resize-none"></textarea>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-foreground mb-1">Catatan Ringkas / Tambahan</label>
                <input type="text" id="edit-keterangan" name="keterangan" placeholder="Contoh: Port kendor, perlu kabel baru..."
                    class="w-full px-3 py-2 rounded-xl bg-muted/40 border border-border text-foreground text-xs focus:ring-1 focus:ring-primary">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3 rounded-xl bg-muted/30 border border-border">
                <div>
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1">Jenis Tindak Lanjut</label>
                    <select id="edit-jenis-tindak-lanjut" name="jenis_tindak_lanjut" required
                        class="w-full px-2.5 py-1.5 rounded-lg bg-card border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                        <option value="pending">Pending (Menunggu Triase)</option>
                        <option value="tindak_ulang">Tindak Ulang Laboran</option>
                        <option value="perbaikan">Perbaikan (Teknisi / Lab)</option>
                        <option value="penggantian">Penggantian (Stok / Pengadaan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1">Status Progres Item</label>
                    <select id="edit-status-item" name="status_item" required
                        class="w-full px-2.5 py-1.5 rounded-lg bg-card border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                        <option value="menunggu_triase">⏳ Menunggu Triase</option>
                        <option value="proses_perbaikan">🔧 Proses Perbaikan</option>
                        <option value="selesai_perbaikan">✓ Selesai Perbaikan</option>
                        <option value="menunggu_pengadaan">📦 Menunggu Pengadaan</option>
                        <option value="terdistribusi">🚚 Terdistribusi ke Lab</option>
                        <option value="selesai">✓ Selesai</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-border">
                <button type="button" onclick="closeEditItemModal()"
                    class="px-3.5 py-2 rounded-xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold shadow-sm shadow-primary/25 hover:bg-primary/90 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Item Laporan -->
<div id="modal-add-item" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-card border border-border rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-border">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 border border-primary/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-foreground">Tambah Item Permasalahan Baru</h3>
                    <p class="text-[11px] text-muted-foreground">Tambahkan barang atau komponen bermasalah ke laporan ini.</p>
                </div>
            </div>
            <button type="button" onclick="closeAddItemModal()" class="text-muted-foreground hover:text-foreground text-sm font-bold w-7 h-7 rounded-lg bg-muted flex items-center justify-center">
                ✕
            </button>
        </div>

        <form method="POST" action="{{ route('laporan.item.store', $laporan) }}" class="space-y-3.5">
            @csrf

            <div>
                <label class="block text-[11px] font-semibold text-foreground mb-1">Nama Barang / Komponen <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_barang" required placeholder="Contoh: Mouse Logitech / Kabel LAN / Monitor LG"
                    class="w-full px-3 py-2 rounded-xl bg-muted/40 border border-border text-foreground text-xs font-semibold focus:ring-1 focus:ring-primary">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-foreground mb-1">Jumlah <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah" min="1" value="1" required
                        class="w-full px-3 py-2 rounded-xl bg-muted/40 border border-border text-foreground text-xs font-bold focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-foreground mb-1">Satuan <span class="text-rose-500">*</span></label>
                    <input type="text" name="satuan" value="unit" required placeholder="unit / pcs / buah"
                        class="w-full px-3 py-2 rounded-xl bg-muted/40 border border-border text-foreground text-xs focus:ring-1 focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-foreground mb-1">Keluhan / Kerusakan Awal</label>
                <textarea name="deskripsi" rows="2" placeholder="Deskripsikan masalah kerusakan fisik atau gejala error..."
                    class="w-full px-3 py-2 rounded-xl bg-muted/40 border border-border text-foreground text-xs focus:ring-1 focus:ring-primary resize-none"></textarea>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-foreground mb-1">Catatan Ringkas / Tambahan</label>
                <input type="text" name="keterangan" placeholder="Contoh: PC-05, port kendor, perlu kabel baru..."
                    class="w-full px-3 py-2 rounded-xl bg-muted/40 border border-border text-foreground text-xs focus:ring-1 focus:ring-primary">
            </div>

            <div class="p-3 rounded-xl bg-muted/30 border border-border">
                <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1">Usulan Tindak Lanjut <span class="text-rose-500">*</span></label>
                <select name="jenis_tindak_lanjut" required
                    class="w-full px-2.5 py-1.5 rounded-lg bg-card border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                    <option value="pending">Pending (Menunggu Triase)</option>
                    <option value="tindak_ulang">Tindak Ulang Laboran</option>
                    <option value="perbaikan">Perbaikan (Teknisi / Lab)</option>
                    <option value="penggantian">Penggantian (Stok / Pengadaan)</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-border">
                <button type="button" onclick="closeAddItemModal()"
                    class="px-3.5 py-2 rounded-xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold shadow-sm shadow-primary/25 hover:bg-primary/90 transition-colors">
                    Tambahkan Item
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleEditForm(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.toggle('hidden');
        }
    }

    function openAddItemModal() {
        document.getElementById('modal-add-item').classList.remove('hidden');
    }

    function closeAddItemModal() {
        document.getElementById('modal-add-item').classList.add('hidden');
    }

    function openEditItemModal(id, namaBarang, jumlah, satuan, deskripsi, keterangan, jenisTindakLanjut, statusItem, updateUrl) {
        document.getElementById('form-edit-item').action = updateUrl;
        document.getElementById('edit-nama-barang').value = namaBarang;
        document.getElementById('edit-jumlah').value = jumlah;
        document.getElementById('edit-satuan').value = satuan;
        document.getElementById('edit-deskripsi').value = deskripsi || '';
        document.getElementById('edit-keterangan').value = keterangan || '';
        document.getElementById('edit-jenis-tindak-lanjut').value = jenisTindakLanjut;
        document.getElementById('edit-status-item').value = statusItem;
        
        document.getElementById('modal-edit-item').classList.remove('hidden');
    }

    function closeEditItemModal() {
        document.getElementById('modal-edit-item').classList.add('hidden');
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditItemModal();
            closeAddItemModal();
        }
    });
</script>
@endsection
