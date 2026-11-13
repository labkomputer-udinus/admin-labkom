@extends('layouts.app')

@section('title', 'Dashboard Ringkasan')
@section('header_title', 'Dashboard Pengelolaan Laboratorium')

@section('content')
<div class="space-y-6">

    <!-- Top Alert if Critical Stock exists -->
    @if($stokKritis->isNotEmpty())
        <div class="p-4 rounded-3xl bg-destructive/10 border border-destructive/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-destructive text-white flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-destructive">Peringatan Stok Kritis Gudang!</h3>
                    <p class="text-xs text-foreground/80">Terdapat <span class="font-bold text-destructive">{{ $stokKritis->count() }} barang</span> dengan jumlah stok berada di bawah batas minimum.</p>
                </div>
            </div>
            <a href="{{ route('gudang.index', ['status_stok' => 'kritis']) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-destructive text-white text-xs font-semibold hover:bg-destructive/90 transition-colors">
                Periksa Gudang
            </a>
        </div>
    @endif

    <!-- Alert if Stuck Reports (>14 Hari Tanpa Update Log) exists -->
    @if(isset($laporanTertahan) && $laporanTertahan->isNotEmpty())
        <div class="p-5 rounded-3xl bg-amber-500/10 border border-amber-500/30 space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-amber-700 dark:text-amber-300 flex items-center gap-2">
                            <span>Perhatian: {{ $laporanTertahan->count() }} Laporan Tertahan (> 1 Bulan Tanpa Progres Log)</span>
                        </h3>
                        <p class="text-xs text-muted-foreground mt-0.5">Laporan aktif berikut belum memiliki entri tindakan atau pembaruan status baru selama lebih dari 1 bulan.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 pt-2">
                @foreach($laporanTertahan->take(6) as $stuck)
                    <div class="p-3.5 rounded-2xl bg-card border border-amber-500/20 flex flex-col justify-between gap-2 shadow-2xs">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-lg bg-amber-500/15 text-amber-800 dark:text-amber-300 font-bold text-xs">
                                    Lab {{ $stuck->lab_nama }}
                                </span>
                                <span class="font-mono text-xs font-semibold text-foreground">#LAP-{{ str_pad($stuck->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-md bg-destructive/10 text-destructive font-bold text-[11px]">
                                Inaktif {{ $stuck->days_inactive }} hari
                            </span>
                        </div>

                        <div class="text-xs text-muted-foreground space-y-0.5">
                            <p class="font-medium text-foreground truncate">
                                @if($stuck->items->isNotEmpty())
                                    {{ $stuck->items->pluck('nama_barang')->join(', ') }}
                                @else
                                    {{ $stuck->bentuk_ketidaksesuaian ?? 'Laporan Permasalahan' }}
                                @endif
                            </p>
                            <p class="text-[11px]">Pelapor: <span class="text-foreground font-medium">{{ $stuck->nama_pelapor ?? '-' }}</span> ({{ $stuck->tanggal_laporan?->format('d/m/Y') }})</p>
                        </div>

                        <div class="pt-2 border-t border-border/60 flex items-center justify-between">
                            <x-status-badge :status="$stuck->status" />
                            <a href="{{ route('laporan.show', $stuck) }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                                <span>Tindak Lanjuti</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- 4 Main Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Laporan -->
        <div class="bg-card border border-border p-5 rounded-3xl shadow-sm hover:border-primary/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Total Laporan</span>
                <div class="w-10 h-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-foreground">{{ $totalLaporan }}</div>
                <p class="text-xs text-muted-foreground mt-0.5">Seluruh laporan dari 14 lab</p>
            </div>
        </div>

        <!-- Laporan Berjalan / Proses -->
        <div class="bg-card border border-border p-5 rounded-3xl shadow-sm hover:border-amber-500/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Laporan Aktif</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-amber-500">{{ $laporanProses }}</div>
                <p class="text-xs text-muted-foreground mt-0.5">Sedang triase / tindak lanjut</p>
            </div>
        </div>

        <!-- Pengajuan Menunggu -->
        <div class="bg-card border border-border p-5 rounded-3xl shadow-sm hover:border-orange-500/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Pengajuan Menunggu</span>
                <div class="w-10 h-10 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-orange-500">{{ $pengajuanMenunggu }}</div>
                <p class="text-xs text-muted-foreground mt-0.5">Menunggu keputusan divisi</p>
            </div>
        </div>

        <!-- Selesai -->
        <div class="bg-card border border-border p-5 rounded-3xl shadow-sm hover:border-emerald-500/40 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-muted-foreground uppercase tracking-wider">Selesai Ditangani</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-emerald-500">{{ $laporanSelesai }}</div>
                <p class="text-xs text-muted-foreground mt-0.5">Terdistribusi / diperbaiki</p>
            </div>
        </div>
    </div>

    <!-- Frekuensi Laporan per Laboratorium & Top 5 Barang Belum Didistribusikan -->
    <div class="bg-card border border-border p-6 rounded-3xl space-y-6 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-border/80">
            <div>
                <h3 class="text-base font-bold text-foreground flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                    Frekuensi Laporan per Laboratorium
                </h3>
                <p class="text-xs text-muted-foreground mt-0.5">Distribusi akumulasi permasalahan di 14 laboratorium komputer (D2A – D3N).</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-xl bg-primary/10 text-primary self-start sm:self-auto">
                Total 14 Laboratorium
            </span>
        </div>

        {{-- 14 Labs Grid (2 rows of 7 on desktop) --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3">
            @foreach($labReports as $lab => $count)
                <a href="{{ route('laporan.index', ['lab' => $lab]) }}"
                    class="p-3.5 rounded-2xl border border-border bg-muted/30 hover:border-primary hover:bg-primary/5 hover:shadow-2xs transition-all text-center group flex flex-col justify-between">
                    <div class="text-xs font-bold text-muted-foreground group-hover:text-primary transition-colors">{{ $lab }}</div>
                    <div class="text-2xl font-black text-foreground my-1">{{ $count }}</div>
                    <div class="text-[11px] text-muted-foreground font-medium">laporan</div>
                </a>
            @endforeach
        </div>

        <!-- 5 Barang Terbanyak yang Perlu Diganti dan Belum Didistribusikan -->
        <div class="pt-5 border-t border-border space-y-3.5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h4 class="text-xs font-bold text-muted-foreground uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>5 Barang Terbanyak yang Perlu Diganti & Belum Didistribusikan</span>
                    </h4>
                    <p class="text-xs text-muted-foreground mt-0.5">Prioritas pemenuhan barang rusak berkategori penggantian yang masih menunggu stok atau distribusi ke lab.</p>
                </div>
                <a href="{{ route('distribusi.create') }}" class="text-xs font-bold text-primary hover:underline inline-flex items-center gap-1 self-start sm:self-auto">
                    <span>Distribusi Sekarang</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 pt-1">
                @forelse($topPenggantianBelumDistribusi as $index => $item)
                    <div class="p-4 rounded-2xl bg-muted/40 border border-border/80 hover:border-amber-500/50 hover:bg-muted/60 transition-all flex flex-col justify-between gap-2.5 shadow-2xs">
                        <div class="flex items-center justify-between gap-2">
                            <span class="w-6 h-6 rounded-full bg-amber-500/15 text-amber-700 dark:text-amber-400 font-black text-xs flex items-center justify-center">
                                #{{ $index + 1 }}
                            </span>
                            <span class="text-[11px] font-semibold text-muted-foreground">
                                {{ $item->frekuensi }} Laporan
                            </span>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-foreground truncate" title="{{ $item->nama_barang }}">
                                {{ $item->nama_barang }}
                            </div>
                            <div class="text-xs text-amber-600 dark:text-amber-400 font-extrabold mt-1">
                                {{ $item->total_belum_distribusi }} {{ $item->satuan }}
                            </div>
                        </div>
                        <div class="text-[11px] text-muted-foreground pt-2 border-t border-border/60 flex items-center justify-between">
                            <span>Diajukan: {{ $item->total_diajukan }} {{ $item->satuan }}</span>
                            <span class="text-amber-600 dark:text-amber-400 font-semibold">Belum Kirim</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-6 rounded-2xl bg-muted/20 border border-dashed border-border text-center text-xs text-muted-foreground">
                        Tidak ada barang yang perlu diganti dan belum didistribusikan saat ini. Semua kebutuhan telah terpenuhi! 🎉
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Ringkasan Kebutuhan Barang per Laboratorium (Item Aktif yang Belum Selesai) -->
    <div class="bg-card border border-border p-6 rounded-2xl space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-base font-bold text-foreground flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                    Ringkasan Kebutuhan Barang per Laboratorium
                </h3>
                <p class="text-xs text-muted-foreground mt-0.5">Daftar item barang rusak atau sedang diajukan penggantian di masing-masing lab untuk mempercepat identifikasi kebutuhan.</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 self-start sm:self-auto">
                {{ count($labKebutuhan) }} Lab Memerlukan Penanganan
            </span>
        </div>

        @if(count($labKebutuhan) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-1">
                @foreach($labKebutuhan as $labName => $laporans)
                    @php
                        $allItems = $laporans->flatMap->items;
                        $totalSisa = $allItems->sum(fn($it) => max(0, (int) $it->jumlah - (int) $it->distribusis->sum('jumlah')));
                        $totalButuh = $allItems->sum('jumlah');
                    @endphp
                    <div class="p-4 rounded-2xl border border-border bg-input/20 hover:border-primary/40 hover:bg-input/40 transition-all flex flex-col justify-between space-y-3">
                        <div class="flex items-center justify-between pb-2.5 border-b border-border/80">
                            <div class="flex items-center gap-2.5">
                                <span class="w-8 h-8 rounded-xl bg-primary text-white font-black text-xs flex items-center justify-center shadow-sm">
                                    {{ $labName }}
                                </span>
                                <div>
                                    <h4 class="font-bold text-sm text-foreground">Lab {{ $labName }}</h4>
                                    <span class="text-[11px] text-muted-foreground">{{ $laporans->count() }} laporan aktif</span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-primary/10 text-primary font-bold text-xs" title="Total sisa kebutuhan barang yang belum terdistribusi">
                                @if($totalSisa < $totalButuh)
                                    {{ $totalSisa }} dari {{ $totalButuh }} Item
                                @else
                                    {{ $totalButuh }} Item
                                @endif
                            </span>
                        </div>

                        <!-- List of needed items in this lab -->
                        <div class="space-y-2 flex-1">
                            @foreach($allItems as $item)
                                @php
                                    $terdistribusi = (int) $item->distribusis->sum('jumlah');
                                    $sisa = max(0, (int) $item->jumlah - $terdistribusi);
                                    $satuan = ucfirst($item->satuan ?? 'unit');
                                @endphp
                                <div class="p-2.5 rounded-xl bg-card border border-border/60 text-xs space-y-1.5 shadow-2xs">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-bold text-foreground truncate">{{ $item->nama_barang }}</span>
                                        <span class="font-bold text-primary flex-shrink-0 text-xs">
                                            {{ $sisa }} dari {{ $item->jumlah }} {{ $satuan }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between gap-1 text-[11px]">
                                        <span class="text-muted-foreground truncate">{{ $item->deskripsi ?? ($item->jenis_tindak_lanjut === 'penggantian' ? 'Perlu Penggantian' : 'Perlu Perbaikan') }}</span>
                                        <x-status-badge :status="$item->status_item" />
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Action Link -->
                        <div class="pt-2 border-t border-border/60 flex items-center justify-between">
                            <a href="{{ route('laporan.index', ['lab' => $labName]) }}" class="text-xs font-semibold text-primary hover:underline flex items-center gap-1">
                                <span>Lihat Laporan Lab {{ $labName }}</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-8 text-center bg-muted/20 rounded-2xl border border-dashed border-border text-muted-foreground">
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="font-semibold text-foreground text-sm">Semua Laboratorium Terkondisikan</p>
                    <p class="text-xs">Tidak ada item kerusakan aktif yang sedang menunggu penanganan saat ini.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Recent Laporans & Recent Distributions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Recent Laporans Table -->
        <div class="bg-card border border-border p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-foreground">Laporan Tertulis Terbaru</h3>
                <a href="{{ route('laporan.index') }}" class="text-sm font-semibold text-primary hover:underline">Lihat Semua →</a>
            </div>

            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-border text-muted-foreground text-xs font-semibold uppercase tracking-wide">
                        <th class="pb-2.5 pr-4">Lab</th>
                        <th class="pb-2.5 pr-4">Pelapor / Tanggal</th>
                        <th class="pb-2.5 pr-4">Status</th>
                        <th class="pb-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                    @forelse($recentLaporans as $lap)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="py-3 pr-4">
                                <span class="w-8 h-8 rounded-xl bg-primary/10 text-primary font-black text-sm flex items-center justify-center">{{ $lap->lab_nama }}</span>
                            </td>
                            <td class="py-3 pr-4">
                                <div class="text-sm font-semibold text-foreground">{{ $lap->nama_pelapor ?? 'Laboran' }}</div>
                                <div class="text-xs text-muted-foreground mt-0.5">{{ $lap->tanggal_laporan?->format('d M Y') }}</div>
                            </td>
                            <td class="py-3 pr-4">
                                <x-status-badge :status="$lap->status" />
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('laporan.show', $lap) }}"
                                    class="px-3 py-1.5 rounded-xl bg-primary text-white font-semibold text-xs hover:bg-primary/90 transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-sm text-muted-foreground">Belum ada laporan dicatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Recent Distributions -->
        <div class="bg-card border border-border p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-foreground">Distribusi Barang Terakhir</h3>
                <a href="{{ route('distribusi.index') }}" class="text-sm font-semibold text-primary hover:underline">Lihat Semua →</a>
            </div>

            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-border text-muted-foreground text-xs font-semibold uppercase tracking-wide">
                        <th class="pb-2.5 pr-4">Barang</th>
                        <th class="pb-2.5 pr-4">Tujuan Lab</th>
                        <th class="pb-2.5 pr-4">Asal Sumber</th>
                        <th class="pb-2.5 text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                    @forelse($recentDistribusi as $dist)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="py-3 pr-4">
                                <div class="text-sm font-bold text-foreground">{{ $dist->stokGudang?->barangMaster?->nama_barang ?? 'Barang' }}</div>
                                <div class="text-xs text-muted-foreground mt-0.5">{{ $dist->jumlah }} {{ $dist->stokGudang?->barangMaster?->satuan ?? 'unit' }}</div>
                            </td>
                            <td class="py-3 pr-4">
                                <div class="text-sm font-bold text-primary">Lab {{ $dist->lab_tujuan }}</div>
                                <div class="text-xs text-muted-foreground mt-0.5">{{ $dist->kode_pc_tujuan ?? '—' }}</div>
                            </td>
                            <td class="py-3 pr-4">
                                @if($dist->asal_barang === 'pengajuan')
                                    <span class="px-2.5 py-1 rounded-lg bg-primary/10 text-primary text-xs font-bold">Pengadaan</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-800/60 dark:text-slate-400 text-xs font-semibold">Stok Lama</span>
                                @endif
                            </td>
                            <td class="py-3 text-right text-sm text-muted-foreground">
                                {{ $dist->tanggal_distribusi?->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-sm text-muted-foreground">Belum ada aktivitas distribusi barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
