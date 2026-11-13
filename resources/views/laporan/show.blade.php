@extends('layouts.app')

@section('title', 'Detail Laporan #' . $laporan->id . ' — Lab ' . $laporan->lab_nama)
@section('header_title', 'Detail & Tracking Progres Laporan Lab ' . $laporan->lab_nama)

@section('content')
<div class="space-y-8 max-w-6xl mx-auto pb-12">

    {{-- Top Navigation & Action Buttons Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('laporan.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-muted-foreground hover:text-primary transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Rekap Laporan</span>
        </a>

        <div class="flex items-center gap-2.5 flex-wrap">
            {{-- Ekspor PDF --}}
            <a href="{{ route('ekspor.laporan.detail.pdf', $laporan) }}" target="_blank"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors shadow-2xs">
                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span>Cetak / PDF</span>
            </a>

            {{-- Ekspor CSV / Excel --}}
            <a href="{{ route('ekspor.laporan.detail.excel', $laporan) }}"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors shadow-2xs">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Ekspor CSV</span>
            </a>

            {{-- Edit Info Laporan (Icon Saja) --}}
            <a href="{{ route('laporan.edit', $laporan) }}"
                class="inline-flex items-center justify-center w-9 h-9 rounded-2xl border border-border text-muted-foreground hover:text-amber-600 hover:bg-amber-500/10 hover:border-amber-500/30 transition-colors shadow-2xs"
                title="Edit Informasi Laporan">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>

            {{-- Hapus Laporan (Icon Saja) --}}
            <form method="POST" action="{{ route('laporan.destroy', $laporan) }}"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-2xl border border-destructive/30 text-destructive hover:bg-destructive hover:text-white hover:border-destructive transition-colors shadow-2xs"
                    title="Hapus Laporan">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Macro Progress Tracker (Clean Stepper UI like reference image) --}}
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
            1 => [
                'label' => 'Laporan Masuk',
                'desc'  => 'Dicatat laboran',
                'icon'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
            ],
            2 => [
                'label' => 'Triase & Diagnosa',
                'desc'  => 'Pemeriksaan awal',
                'icon'  => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'
            ],
            3 => [
                'label' => 'Penanganan / Logistik',
                'desc'  => 'Troubleshooting / pengadaan',
                'icon'  => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'
            ],
            4 => [
                'label' => 'Distribusi Barang',
                'desc'  => 'Pemasangan ke lab',
                'icon'  => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'
            ],
            5 => [
                'label' => 'Selesai',
                'desc'  => 'Tuntas & terverifikasi',
                'icon'  => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
            ],
        ];
    @endphp

    <div class="bg-card border border-border rounded-3xl p-6 sm:p-8 shadow-xs">
        <div class="overflow-x-auto custom-scrollbar pb-2">
            <div class="min-w-[620px] flex items-center justify-between px-2">
                @foreach($macroSteps as $stepNum => $stepData)
                    @php
                        $isPassed  = $currentStepNumber > $stepNum;
                        $isCurrent = $currentStepNumber === $stepNum;
                        $isNext    = $currentStepNumber < $stepNum;
                    @endphp

                    {{-- Step Node --}}
                    <div class="flex flex-col items-center flex-1 z-10">
                        @if($isPassed)
                            {{-- Passed: Emerald Circle with White Checkmark --}}
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/25 flex-shrink-0 transition-transform">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        @elseif($isCurrent)
                            {{-- Current: Blue/Primary Circle with Location/Active Icon + Pulse Ring --}}
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/30 ring-4 ring-primary/20 flex-shrink-0 transition-transform">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        @else
                            {{-- Upcoming: Soft Blue/Muted Circle with Category Icon --}}
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-muted/70 text-muted-foreground border border-border/80 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-5 sm:h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stepData['icon'] }}"/>
                                </svg>
                            </div>
                        @endif

                        {{-- Step Label & Subtitle --}}
                        <div class="mt-3 text-center px-1">
                            <p class="text-xs sm:text-sm font-bold leading-tight {{ $isCurrent ? 'text-primary' : ($isPassed ? 'text-foreground' : 'text-muted-foreground') }}">
                                {{ $stepData['label'] }}
                            </p>
                            <p class="text-[11px] text-muted-foreground mt-0.5 hidden sm:block">
                                {{ $stepData['desc'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Connecting Bar between Steps (Except after the last step) --}}
                    @if($stepNum < count($macroSteps))
                        @php
                            $isLinePassed = $currentStepNumber > $stepNum;
                        @endphp
                        <div class="h-1.5 flex-1 rounded-full mx-1.5 sm:mx-3 -mt-8 sm:-mt-9 transition-colors {{ $isLinePassed ? 'bg-emerald-500' : 'bg-border dark:bg-muted' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Header Summary Card (Lab Identity & Timeline Info) --}}
    <div class="bg-card border border-border rounded-3xl shadow-xs overflow-hidden">
        <div class="h-1.5 w-full bg-primary"></div>

        <div class="p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                {{-- Lab Identity --}}
                <div class="flex items-start gap-4 sm:gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary font-black text-2xl flex items-center justify-center flex-shrink-0 border border-primary/20 shadow-xs">
                        {{ $laporan->lab_nama }}
                    </div>
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-xl font-bold text-foreground">Laboratorium {{ $laporan->lab_nama }}</h2>
                            <x-status-badge :status="$laporan->status" />
                            @if($laporan->status_akhir === 'closed')
                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700">
                                    ✓ Closed
                                </span>
                            @elseif($laporan->status_akhir === 'follow_up')
                                <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-300 dark:border-amber-700">
                                    ⏳ Follow Up
                                </span>
                            @endif

                            {{-- Edit & Hapus (Icon Saja) --}}
                            <div class="flex items-center gap-1.5 sm:ml-2">
                                <a href="{{ route('laporan.edit', $laporan) }}"
                                    class="p-1.5 rounded-xl border border-border text-muted-foreground hover:text-amber-600 hover:bg-amber-500/10 hover:border-amber-500/30 transition-colors shadow-2xs"
                                    title="Edit Informasi Laporan">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('laporan.destroy', $laporan) }}" class="inline"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 rounded-xl border border-destructive/30 text-destructive hover:bg-destructive hover:text-white hover:border-destructive transition-colors shadow-2xs"
                                        title="Hapus Laporan">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <p class="text-sm text-muted-foreground mt-1.5 flex items-center gap-2 flex-wrap">
                            <span>ID Laporan: <strong class="font-mono text-foreground font-bold">#LAP-{{ str_pad($laporan->id, 4, '0', STR_PAD_LEFT) }}</strong></span>
                            <span>•</span>
                            <span>Pelapor: <strong class="text-foreground font-semibold">{{ $laporan->nama_pelapor ?? 'Laboran' }}</strong>
                                @if($laporan->jabatan_pelapor)
                                    <span class="text-muted-foreground">({{ $laporan->jabatan_pelapor }})</span>
                                @endif
                                @if($laporan->bagian)
                                    <span class="text-xs px-2 py-0.5 rounded-lg bg-muted text-muted-foreground font-medium">{{ $laporan->bagian }}</span>
                                @endif
                            </span>
                        </p>
                    </div>
                </div>

                {{-- Date Info Boxes --}}
                <div class="flex flex-wrap gap-3">
                    @if($laporan->tanggal_kejadian)
                        <div class="px-5 py-3 rounded-2xl bg-muted/40 border border-border text-center min-w-[130px]">
                            <p class="text-[11px] font-bold text-muted-foreground uppercase tracking-wider mb-1">Tgl Kejadian</p>
                            <p class="font-bold text-foreground text-sm">{{ $laporan->tanggal_kejadian->format('d M Y') }}</p>
                        </div>
                    @endif

                    <div class="px-5 py-3 rounded-2xl bg-muted/40 border border-border text-center min-w-[130px]">
                        <p class="text-[11px] font-bold text-muted-foreground uppercase tracking-wider mb-1">Tanggal Masuk</p>
                        <p class="font-bold text-foreground text-sm">{{ $laporan->tanggal_laporan?->format('d M Y') }}</p>
                    </div>

                    <div class="px-5 py-3 rounded-2xl {{ $laporan->tanggal_selesai ? 'bg-emerald-50 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800' : 'bg-muted/40 border-border' }} border text-center min-w-[130px]">
                        <p class="text-[11px] font-bold text-muted-foreground uppercase tracking-wider mb-1">Tanggal Selesai</p>
                        <p class="font-bold text-sm {{ $laporan->tanggal_selesai ? 'text-emerald-700 dark:text-emerald-400' : 'text-muted-foreground' }}">
                            {{ $laporan->tanggal_selesai ? $laporan->tanggal_selesai->format('d M Y') : 'Belum Selesai' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form Permintaan Tindakan Perbaikan & Pencegahan Details --}}
            @if($laporan->bentuk_ketidaksesuaian || $laporan->uraian_pengamatan || $laporan->tindakan_langsung || $laporan->permintaan_tindakan)
                <div class="mt-6 pt-6 border-t border-border space-y-4">
                    <h4 class="text-xs font-bold text-primary uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Isian Form Permintaan Tindakan (Fisik)
                    </h4>

                    @if($laporan->bentuk_ketidaksesuaian)
                        <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20">
                            <p class="text-[11px] font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider mb-1">Bentuk Ketidaksesuaian / Pokok Masalah</p>
                            <p class="text-sm font-semibold text-foreground">{{ $laporan->bentuk_ketidaksesuaian }}</p>
                        </div>
                    @endif

                    @if($laporan->uraian_pengamatan)
                        <div>
                            <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Hasil / Uraian Pengamatan</p>
                            <p class="text-sm text-foreground leading-relaxed bg-muted/20 rounded-2xl p-4 border border-border/60">{{ $laporan->uraian_pengamatan }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($laporan->tindakan_langsung)
                            <div>
                                <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Tindakan Langsung yang Sudah Dicoba (Laboran)</p>
                                <p class="text-sm text-foreground leading-relaxed bg-muted/20 rounded-2xl p-4 border border-border/60">{{ $laporan->tindakan_langsung }}</p>
                            </div>
                        @endif

                        @if($laporan->permintaan_tindakan)
                            <div>
                                <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Permintaan Tindakan Perbaikan & Pencegahan</p>
                                <p class="text-sm text-foreground leading-relaxed bg-muted/20 rounded-2xl p-4 border border-border/60">{{ $laporan->permintaan_tindakan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($laporan->catatan_umum)
                <div class="mt-6 pt-6 border-t border-border">
                    <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider mb-2">Catatan Umum / Tambahan Admin</p>
                    <p class="text-sm text-foreground leading-relaxed bg-muted/20 rounded-2xl p-4 border border-border/60">{{ $laporan->catatan_umum }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Items & Troubleshooting Section --}}
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-foreground">Daftar Item Permasalahan</h3>
                <p class="text-xs sm:text-sm text-muted-foreground mt-0.5">
                    Klik dropdown pada setiap item untuk melihat keluhan, aksi logistik, dan riwayat tracking langkah penanganan.
                </p>
            </div>
            <span class="text-xs font-bold px-3.5 py-1.5 rounded-xl bg-primary/10 text-primary border border-primary/20">
                {{ $laporan->items->count() }} Item
            </span>
        </div>

        {{-- Item Cards with Dropdown / Accordion for Tracking --}}
        <div class="space-y-5">
            @foreach($laporan->items as $itemIndex => $item)
                @php
                    $stepCount = $item->penanganans->count();
                @endphp
                <details class="group bg-card border border-border rounded-3xl overflow-hidden shadow-xs transition-all hover:border-primary/40 open:border-primary/40 open:ring-1 open:ring-primary/20" {{ $itemIndex === 0 ? 'open' : '' }}>
                    
                    {{-- Item Summary Bar (Accordion Header) --}}
                    <summary class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 bg-muted/30 hover:bg-muted/50 cursor-pointer list-none select-none transition-colors border-b border-transparent group-open:border-border">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <span class="w-8 h-8 rounded-xl bg-primary/10 text-primary font-bold text-xs flex items-center justify-center flex-shrink-0 border border-primary/20">
                                #{{ $itemIndex + 1 }}
                            </span>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <h4 class="font-bold text-foreground text-sm sm:text-base truncate">{{ $item->nama_barang }}</h4>
                                    <span class="px-2.5 py-0.5 rounded-lg bg-card border border-border text-foreground font-bold text-xs whitespace-nowrap">
                                        &times;{{ $item->jumlah }} {{ $item->satuan }}
                                    </span>
                                </div>
                                <p class="text-xs text-muted-foreground truncate mt-1">
                                    Tindak Lanjut: <strong class="text-foreground capitalize">{{ $item->jenis_tindak_lanjut }}</strong>
                                    &nbsp;•&nbsp;
                                    <span class="text-foreground/80">{{ Str::limit($item->deskripsi ?? 'Tidak ada keluhan spesifik', 70) }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-center flex-shrink-0" onclick="event.stopPropagation();">
                            {{-- Step count badge --}}
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold {{ $stepCount > 0 ? 'bg-primary/10 text-primary border border-primary/20' : 'bg-muted text-muted-foreground border border-border' }}">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span>{{ $stepCount }} Langkah</span>
                            </span>

                            <x-status-badge :status="$item->status_item" />

                            {{-- Item Actions (Edit & Delete Icon Buttons) --}}
                            <div class="flex items-center gap-1.5 border-l border-border/80 pl-2">
                                {{-- Edit Item Icon Button --}}
                                <button type="button"
                                    onclick="openEditItemModal({{ $item->id }}, '{{ addslashes($item->nama_barang) }}', {{ (int)$item->jumlah }}, '{{ addslashes($item->satuan) }}', '{{ addslashes($item->deskripsi ?? '') }}', '{{ addslashes($item->keterangan ?? '') }}', '{{ $item->jenis_tindak_lanjut }}', '{{ $item->status_item }}', '{{ route('laporan.item.update', [$laporan, $item]) }}')"
                                    class="w-8 h-8 rounded-xl bg-card border border-border text-muted-foreground hover:text-primary hover:border-primary/40 hover:bg-primary/5 flex items-center justify-center transition-colors shadow-2xs"
                                    title="Edit Data Item">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                {{-- Delete Item Icon Button --}}
                                <form method="POST" action="{{ route('laporan.item.destroy', [$laporan, $item]) }}"
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus item \'{{ addslashes($item->nama_barang) }}\' ini beserta seluruh riwayat langkah penanganannya?');"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 rounded-xl border border-destructive/30 text-destructive bg-card hover:bg-destructive hover:text-white transition-colors flex items-center justify-center shadow-2xs"
                                        title="Hapus Item Ini">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            {{-- Dropdown Indicator Button --}}
                            <div class="w-8 h-8 rounded-xl bg-card border border-border flex items-center justify-center text-muted-foreground group-open:text-primary group-open:rotate-180 transition-transform">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </summary>

                    {{-- Item Details & Tracking Content --}}
                    <div class="p-6 sm:p-8 space-y-6">

                        {{-- Info Kerusakan & Aksi Logistik --}}
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-5 p-5 rounded-2xl bg-muted/20 border border-border/80">
                            <div class="space-y-1.5 flex-1">
                                <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Keluhan / Kerusakan Awal</p>
                                <p class="text-sm text-foreground leading-relaxed">{{ $item->deskripsi ?? 'Tidak ada deskripsi kerusakan spesifik.' }}</p>
                                @if($item->keterangan)
                                    <p class="text-xs text-muted-foreground mt-2 pt-2 border-t border-border/50">
                                        <span class="font-semibold text-foreground">Catatan Ringkas:</span> {{ $item->keterangan }}
                                    </p>
                                @endif
                            </div>

                            @if($item->jenis_tindak_lanjut === 'penggantian')
                                <div class="flex flex-wrap items-center gap-2.5 flex-shrink-0">
                                    <a href="{{ route('pengajuan.create') }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400 text-xs font-bold hover:bg-orange-500 hover:text-white transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Ajukan Pengadaan
                                    </a>
                                    <a href="{{ route('distribusi.create', ['laporan_item_id' => $item->id]) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 text-xs font-bold hover:bg-indigo-500 hover:text-white transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                        Distribusi dari Stok
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- Banner Konfirmasi Laboran (muncul saat status = terdistribusi) --}}
                        @if($item->status_item === 'terdistribusi')
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-emerald-700 dark:text-emerald-400">Menunggu Konfirmasi Laboran</p>
                                        <p class="text-xs text-emerald-700/80 dark:text-emerald-400/70 mt-0.5">
                                            Barang sudah didistribusikan. Setelah laboran memverifikasi barang berfungsi dengan baik di lab, klik tombol konfirmasi untuk menutup item ini.
                                        </p>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('laporan.item.konfirmasi-selesai', [$laporan, $item]) }}" class="flex-shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        onclick="return confirm('Konfirmasi bahwa barang sudah diperiksa laboran dan berfungsi baik di lapangan?')"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500 text-white text-xs font-bold hover:bg-emerald-600 transition-colors shadow-md shadow-emerald-500/20 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Konfirmasi Laboran — Selesai
                                    </button>
                                </form>
                            </div>
                        @endif

                        {{-- Riwayat Kronologi Penanganan (Clean Vertical Stepper Timeline Feed) --}}
                        <div class="space-y-4">
                            <h5 class="text-xs font-bold text-foreground uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Kronologi Solusi & Riwayat Langkah ({{ $stepCount }} Langkah)
                            </h5>

                            @if($item->penanganans->isNotEmpty())
                                <div class="space-y-4 pl-3 sm:pl-4 border-l-2 border-primary/30 my-3">
                                    @foreach($item->penanganans as $pIndex => $penanganan)
                                        @php
                                            $isStepDone = !empty($penanganan->tanggal_hasil);
                                        @endphp
                                        <div class="relative pl-6 sm:pl-8 group">
                                            {{-- Circular Step Node on Vertical Line (Emerald check if has result date, primary if pending) --}}
                                            <div class="absolute -left-[31px] sm:-left-[35px] top-3 w-6 h-6 rounded-full {{ $isStepDone ? 'bg-emerald-500 text-white shadow-emerald-500/20' : 'bg-primary text-white ring-4 ring-primary/20' }} flex items-center justify-center text-[10px] font-bold shadow-xs">
                                                @if($isStepDone)
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                @else
                                                    {{ $pIndex + 1 }}
                                                @endif
                                            </div>

                                             <div class="p-5 rounded-2xl bg-card border border-border space-y-4 shadow-2xs">
                                                {{-- Header Step --}}
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-border/60">
                                                    <div class="flex items-center gap-2.5 flex-wrap">
                                                        <span class="px-3 py-1 rounded-xl bg-primary/10 text-primary font-bold text-xs">
                                                            Langkah #{{ $pIndex + 1 }}: {{ $penanganan->tahap }}
                                                        </span>
                                                        @if($penanganan->jenis_tindakan)
                                                            <span class="px-2.5 py-0.5 rounded-lg bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 font-semibold text-[11px] border border-indigo-500/20">
                                                                {{ \App\Constants\JenisTindakan::label($penanganan->jenis_tindakan) }}
                                                            </span>
                                                        @endif
                                                        @if($penanganan->status_item_saat_itu)
                                                            <div class="flex items-center gap-1 text-xs">
                                                                @if($penanganan->status_sebelum && $penanganan->status_sebelum !== $penanganan->status_item_saat_itu)
                                                                    <span class="text-muted-foreground text-[11px] line-through">{{ str_replace('_', ' ', $penanganan->status_sebelum) }}</span>
                                                                    <span class="text-muted-foreground text-xs">→</span>
                                                                @endif
                                                                <x-status-badge :status="$penanganan->status_item_saat_itu" />
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="text-xs text-muted-foreground flex items-center gap-2 flex-wrap">
                                                        <span>Aktor / Pihak: <strong class="text-foreground font-semibold">{{ $penanganan->aktor_peran ?? ($penanganan->petugas ?? 'Admin Lab') }}</strong></span>
                                                    </div>
                                                </div>

                                                {{-- Dates Info: Tanggal Tindakan & Tanggal Hasil --}}
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    {{-- Tanggal Tindakan --}}
                                                    <div class="p-3 rounded-xl bg-muted/30 border border-border/60 flex items-start gap-2.5">
                                                        <div class="w-7 h-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 mt-0.5">
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Tanggal & Jam Tindakan</p>
                                                            <p class="text-xs font-bold text-foreground mt-0.5">
                                                                {{ $penanganan->tanggal_tindakan ? $penanganan->tanggal_tindakan->format('d M Y, H:i') : ($penanganan->tanggal_waktu ? $penanganan->tanggal_waktu->format('d M Y, H:i') : '-') }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    {{-- Tanggal Hasil --}}
                                                    <div class="p-3 rounded-xl {{ $penanganan->tanggal_hasil ? 'bg-emerald-500/10 border-emerald-500/25' : 'bg-amber-500/10 border-amber-500/25' }} border flex items-start gap-2.5">
                                                        <div class="w-7 h-7 rounded-lg {{ $penanganan->tanggal_hasil ? 'bg-emerald-500/20 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/20 text-amber-600 dark:text-amber-400' }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                                            @if($penanganan->tanggal_hasil)
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                                </svg>
                                                            @else
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="text-[10px] font-bold {{ $penanganan->tanggal_hasil ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400' }} uppercase tracking-wider">Tanggal & Jam Hasil</p>
                                                            <p class="text-xs font-bold {{ $penanganan->tanggal_hasil ? 'text-foreground' : 'text-amber-700 dark:text-amber-400' }} mt-0.5">
                                                                {{ $penanganan->tanggal_hasil ? $penanganan->tanggal_hasil->format('d M Y, H:i') : 'Menunggu Pengerjaan / Uji Coba' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Isi Tindakan / Hasil --}}
                                                <div>
                                                    <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Tindakan / Hasil Langkah Penanganan:</p>
                                                    <div class="text-sm text-foreground leading-relaxed whitespace-pre-line bg-muted/20 p-4 rounded-xl border border-border/50">
                                                        {{ $penanganan->tindakan_atau_hasil ?? '-' }}
                                                    </div>
                                                </div>

                                                {{-- Action Buttons: Edit / Update & Delete --}}
                                                <div class="flex items-center justify-between pt-2 border-t border-border/40">
                                                    <button type="button"
                                                        onclick="toggleEditForm('edit-form-{{ $penanganan->id }}')"
                                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-primary/10 text-primary text-xs font-bold hover:bg-primary hover:text-white transition-colors">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        <span>Edit Langkah / Update Tanggal & Hasil</span>
                                                    </button>

                                                    <form method="POST" action="{{ route('laporan.item.penanganan.destroy', [$laporan, $item, $penanganan]) }}"
                                                        onsubmit="return confirm('Hapus catatan langkah ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-muted-foreground hover:text-destructive text-xs font-semibold p-1.5 transition-colors" title="Hapus langkah">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>

                                                {{-- Inline Edit Form for this Step --}}
                                                <div id="edit-form-{{ $penanganan->id }}" class="hidden pt-4 mt-3 border-t border-dashed border-border bg-muted/20 p-4 rounded-2xl">
                                                    <form method="POST" action="{{ route('laporan.item.penanganan.update', [$laporan, $item, $penanganan]) }}" class="space-y-4">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="flex items-center justify-between pb-2 border-b border-border/60">
                                                            <span class="text-xs font-bold text-foreground">Edit Langkah & Tanggal Hasil (Langkah #{{ $pIndex + 1 }})</span>
                                                            <button type="button" onclick="toggleEditForm('edit-form-{{ $penanganan->id }}')" class="text-xs text-muted-foreground hover:text-foreground font-semibold">
                                                                ✕ Batal
                                                            </button>
                                                        </div>

                                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                                            <div>
                                                                <label class="block text-xs font-semibold text-foreground mb-1">
                                                                    Kategori / Jenis Tindakan
                                                                </label>
                                                                <select name="jenis_tindakan"
                                                                    class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                                                    @foreach($manualJenisTindakanList as $jKey => $jLbl)
                                                                        <option value="{{ $jKey }}" {{ old('jenis_tindakan', $penanganan->jenis_tindakan) === $jKey ? 'selected' : '' }}>{{ $jLbl }}</option>
                                                                    @endforeach
                                                                    @if(!array_key_exists($penanganan->jenis_tindakan, $manualJenisTindakanList) && $penanganan->jenis_tindakan)
                                                                        <option value="{{ $penanganan->jenis_tindakan }}" selected>{{ \App\Constants\JenisTindakan::label($penanganan->jenis_tindakan) }}</option>
                                                                    @endif
                                                                </select>
                                                            </div>

                                                            <div>
                                                                <label class="block text-xs font-semibold text-foreground mb-1">
                                                                    Tahap Penanganan
                                                                </label>
                                                                <input type="text" name="tahap" value="{{ $penanganan->tahap }}" required
                                                                    class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                                            </div>

                                                            <div>
                                                                <label class="block text-xs font-semibold text-foreground mb-1">
                                                                    Aktor / Pihak Pelaksana
                                                                </label>
                                                                <input type="text" name="aktor_peran" value="{{ $penanganan->aktor_peran }}" placeholder="Contoh: Koordinator: Pak X / Teknisi: Y"
                                                                    class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                                            </div>
                                                        </div>

                                                        {{-- Inputs Tanggal Tindakan & Tanggal Hasil --}}
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                            <div>
                                                                <label class="block text-xs font-semibold text-foreground mb-1">
                                                                    Tanggal & Waktu Tindakan
                                                                </label>
                                                                <input type="datetime-local" name="tanggal_tindakan"
                                                                    value="{{ $penanganan->tanggal_tindakan ? $penanganan->tanggal_tindakan->format('Y-m-d\TH:i') : ($penanganan->tanggal_waktu ? $penanganan->tanggal_waktu->format('Y-m-d\TH:i') : '') }}"
                                                                    class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                                            </div>

                                                            <div>
                                                                <label class="block text-xs font-semibold text-emerald-700 dark:text-emerald-400 mb-1 flex items-center gap-1">
                                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    </svg>
                                                                    Tanggal & Waktu Hasil Diperoleh
                                                                </label>
                                                                <input type="datetime-local" name="tanggal_hasil"
                                                                    value="{{ $penanganan->tanggal_hasil ? $penanganan->tanggal_hasil->format('Y-m-d\TH:i') : '' }}"
                                                                    class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-semibold text-foreground mb-1">
                                                                Tindakan / Hasil Langkah Penanganan <span class="text-destructive">*</span>
                                                            </label>
                                                            <textarea name="tindakan_atau_hasil" rows="3" required
                                                                placeholder="Tuliskan tindakan yang dilakukan dan/atau hasil yang diperoleh..."
                                                                class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-xs sm:text-sm text-foreground focus:ring-1 focus:ring-primary resize-none">{{ $penanganan->tindakan_atau_hasil }}</textarea>
                                                        </div>

                                                        {{-- Single Status Dropdown --}}
                                                        <div class="p-3 rounded-xl bg-card border border-border/70">
                                                            <label class="block text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1">Update Status & Progres Item</label>
                                                            <select name="status_item"
                                                                class="w-full px-2.5 py-1.5 rounded-lg bg-input border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                                                                <option value="menunggu_triase" {{ $item->status_item === 'menunggu_triase' ? 'selected' : '' }}>⏳ Menunggu Triase</option>
                                                                <option value="proses_perbaikan" {{ $item->status_item === 'proses_perbaikan' ? 'selected' : '' }}>🔧 Proses Perbaikan</option>
                                                                <option value="selesai_perbaikan" {{ $item->status_item === 'selesai_perbaikan' ? 'selected' : '' }}>✓ Selesai Perbaikan</option>
                                                                <option value="menunggu_pengadaan" {{ $item->status_item === 'menunggu_pengadaan' ? 'selected' : '' }}>📦 Menunggu Pengadaan / Penggantian</option>
                                                                <option value="terdistribusi" {{ $item->status_item === 'terdistribusi' ? 'selected' : '' }}>🚚 Terdistribusi ke Lab</option>
                                                                <option value="selesai" {{ $item->status_item === 'selesai' ? 'selected' : '' }}>✓ Selesai</option>
                                                            </select>
                                                        </div>

                                                        <div class="flex items-center justify-end gap-2 pt-1">
                                                            <button type="button" onclick="toggleEditForm('edit-form-{{ $penanganan->id }}')" class="px-4 py-2 rounded-xl border border-border text-xs font-semibold text-muted-foreground hover:text-foreground">
                                                                Batal
                                                            </button>
                                                            <button type="submit" class="px-5 py-2 rounded-xl bg-primary text-white text-xs font-bold shadow-xs hover:bg-primary/90">
                                                                Simpan Pembaruan Langkah
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-5 rounded-2xl bg-muted/20 border border-dashed border-border text-center text-xs sm:text-sm text-muted-foreground">
                                    Belum ada riwayat langkah penanganan. Gunakan form di bawah untuk menambahkan saran awal atau tindakan teknisi.
                                </div>
                            @endif
                        </div>

                        {{-- Form Tambah Catatan / Saran Penanganan Baru --}}
                        <div class="pt-4 border-t border-border">
                            <details class="group/form bg-muted/20 rounded-2xl border border-border/80 p-5 transition-all">
                                <summary class="flex items-center justify-between cursor-pointer font-bold text-sm text-foreground list-none select-none">
                                    <span class="flex items-center gap-2.5 text-primary">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        + Tambah Catatan / Langkah Penanganan Baru
                                    </span>
                                    <span class="text-xs text-muted-foreground group-open/form:rotate-180 transition-transform">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </span>
                                </summary>

                                <form method="POST" action="{{ route('laporan.item.penanganan.store', [$laporan, $item]) }}" class="mt-5 space-y-4 pt-4 border-t border-border">
                                    @csrf

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-foreground mb-1.5">
                                                Kategori / Jenis Tindakan <span class="text-destructive">*</span>
                                            </label>
                                            <select name="jenis_tindakan" required
                                                class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-xs sm:text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                                @foreach($manualJenisTindakanList as $jKey => $jLbl)
                                                    <option value="{{ $jKey }}" {{ $jKey === 'ditugaskan_teknisi' ? 'selected' : '' }}>{{ $jLbl }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-foreground mb-1.5">
                                                Nama / Judul Langkah <span class="text-destructive">*</span>
                                            </label>
                                            <input type="text" name="tahap" required
                                                placeholder="Contoh: Pemeriksaan Teknisi / Instruksi Tindak Ulang"
                                                class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-xs sm:text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-foreground mb-1.5">
                                                Aktor / Pihak Pelaksana
                                            </label>
                                            <input type="text" name="aktor_peran"
                                                placeholder="Contoh: Koordinator: Pak Budi / Teknisi: Andi"
                                                class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-xs sm:text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                        </div>
                                    </div>

                                    {{-- Tanggal Tindakan & Tanggal Hasil --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-foreground mb-1.5">
                                                Tanggal & Waktu Tindakan
                                            </label>
                                            <input type="datetime-local" name="tanggal_tindakan" value="{{ now()->format('Y-m-d\TH:i') }}"
                                                class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-xs sm:text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-foreground mb-1.5">
                                                Tanggal & Waktu Hasil <span class="text-muted-foreground font-normal">(Opsional / Diisi saat ada hasil)</span>
                                            </label>
                                            <input type="datetime-local" name="tanggal_hasil"
                                                class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-xs sm:text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-foreground mb-1.5">
                                            Tindakan / Hasil Langkah Penanganan <span class="text-destructive">*</span>
                                        </label>
                                        <textarea name="tindakan_atau_hasil" rows="3" required
                                            placeholder="Contoh: Koordinator menginstruksikan laboran untuk coba ganti kabel VGA / Teknisi memeriksa power supply rusak..."
                                            class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-xs sm:text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary resize-none"></textarea>
                                    </div>

                                    {{-- Single Status Dropdown --}}
                                    <div class="p-4 rounded-xl bg-card border border-border/80">
                                        <label class="block text-[11px] font-bold text-muted-foreground uppercase tracking-wider mb-1.5">
                                            Update Status & Progres Item
                                        </label>
                                        <select name="status_item"
                                            class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-xs sm:text-sm text-foreground font-semibold focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                            <option value="menunggu_triase" {{ $item->status_item === 'menunggu_triase' ? 'selected' : '' }}>⏳ Menunggu Triase / Pemeriksaan Awal</option>
                                            <option value="proses_perbaikan" {{ $item->status_item === 'proses_perbaikan' ? 'selected' : '' }}>🔧 Sedang Dalam Proses Perbaikan (Teknisi)</option>
                                            <option value="selesai_perbaikan" {{ $item->status_item === 'selesai_perbaikan' ? 'selected' : '' }}>✓ Selesai Perbaikan (Siap Digunakan)</option>
                                            <option value="menunggu_pengadaan" {{ $item->status_item === 'menunggu_pengadaan' ? 'selected' : '' }}>📦 Perlu Pengadaan / Penggantian Baru</option>
                                            <option value="terdistribusi" {{ $item->status_item === 'terdistribusi' ? 'selected' : '' }}>🚚 Sudah Terdistribusi ke Lab</option>
                                            <option value="selesai" {{ $item->status_item === 'selesai' ? 'selected' : '' }}>✓ Selesai / Ditutup (Closed)</option>
                                        </select>
                                        <p class="text-[11px] text-muted-foreground mt-1.5">
                                            Sistem akan otomatis menyesuaikan alur tindak lanjut dan progres item berdasarkan status yang Anda pilih di atas.
                                        </p>
                                    </div>

                                    <div class="flex justify-end pt-2">
                                        <button type="submit"
                                            class="px-6 py-2.5 rounded-xl bg-primary text-white text-xs sm:text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary/90 transition-transform active:scale-95">
                                            Simpan Langkah Penanganan
                                        </button>
                                    </div>
                                </form>
                            </details>
                        </div>

                    </div>
                </details>
            @endforeach
        </div>
    </div>

</div>

{{-- Modal Edit Item Laporan --}}
<div id="modal-edit-item" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-card border border-border rounded-3xl max-w-xl w-full p-6 sm:p-7 space-y-5 shadow-2xl animate-fade-in max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-border">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 border border-primary/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-foreground">Edit Item Permasalahan</h3>
                    <p class="text-xs text-muted-foreground">Perbarui data item barang, jumlah, keluhan, dan status tindak lanjut.</p>
                </div>
            </div>
            <button type="button" onclick="closeEditItemModal()" class="text-muted-foreground hover:text-foreground text-sm font-bold w-8 h-8 rounded-xl bg-muted/50 flex items-center justify-center">
                ✕
            </button>
        </div>

        <form id="form-edit-item" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-1.5">Nama Barang / Komponen <span class="text-destructive">*</span></label>
                <input type="text" id="edit-nama-barang" name="nama_barang" required
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-semibold focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-1.5">Jumlah <span class="text-destructive">*</span></label>
                    <input type="number" id="edit-jumlah" name="jumlah" min="1" required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-bold focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-1.5">Satuan <span class="text-destructive">*</span></label>
                    <input type="text" id="edit-satuan" name="satuan" required placeholder="unit / pcs / buah"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-1.5">Keluhan / Kerusakan Awal</label>
                <textarea id="edit-deskripsi" name="deskripsi" rows="2" placeholder="Deskripsikan masalah kerusakan fisik..."
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary resize-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-1.5">Catatan Ringkas / Tambahan</label>
                <input type="text" id="edit-keterangan" name="keterangan" placeholder="Contoh: Port kendor, perlu kabel baru..."
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3.5 rounded-2xl bg-muted/20 border border-border/80">
                <div>
                    <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Jenis Tindak Lanjut</label>
                    <select id="edit-jenis-tindak-lanjut" name="jenis_tindak_lanjut" required
                        class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                        <option value="pending">Pending (Menunggu Triase)</option>
                        <option value="tindak_ulang">Tindak Ulang Laboran</option>
                        <option value="perbaikan">Perbaikan (Teknisi / Lab)</option>
                        <option value="penggantian">Penggantian (Stok / Pengadaan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1.5">Status Progres Item</label>
                    <select id="edit-status-item" name="status_item" required
                        class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                        <option value="menunggu_triase">⏳ Menunggu Triase</option>
                        <option value="proses_perbaikan">🔧 Proses Perbaikan</option>
                        <option value="selesai_perbaikan">✓ Selesai Perbaikan</option>
                        <option value="menunggu_pengadaan">📦 Menunggu Pengadaan</option>
                        <option value="terdistribusi">🚚 Terdistribusi ke Lab</option>
                        <option value="selesai">✓ Selesai</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-border">
                <button type="button" onclick="closeEditItemModal()"
                    class="px-4 py-2.5 rounded-xl border border-border text-foreground text-sm font-semibold hover:bg-muted transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/25 hover:bg-primary/90 transition-colors">
                    Simpan Perubahan Item
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
        }
    });
</script>
@endsection
