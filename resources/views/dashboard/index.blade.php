@extends('layouts.app')

@section('title', 'Dashboard Pengelolaan Laboratorium')
@section('header_title', 'Dashboard Pengelolaan Laboratorium')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto pb-12">

        <!-- Top Alert if Critical Stock exists -->
        @if($stokKritis->isNotEmpty())
            <!-- <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-rose-700 dark:text-rose-400">Peringatan Stok Kritis Gudang!</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-300">Terdapat <span class="font-bold text-rose-600 dark:text-rose-400">{{ $stokKritis->count() }} barang</span> dengan jumlah stok berada di bawah batas minimum.</p>
                        </div>
                    </div>
                    <a href="{{ route('gudang.index', ['status_stok' => 'kritis']) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700 transition-colors shadow-sm self-start sm:self-auto">
                        Periksa Gudang
                    </a>
                </div> -->
        @endif

        <!-- 4 Main Stat Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" data-purpose="metrics-summary">
            <!-- Card 1: Total Laporan -->
            <a href="{{ route('laporan.index') }}"
                class="bg-card border border-border hover:border-blue-500/50 p-5 rounded-2xl shadow-xs flex items-center justify-between relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md group cursor-pointer block"
                title="Lihat Semua Laporan">
                <div>
                    <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wider group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Total Laporan</p>
                    <h3 class="text-3xl font-extrabold text-foreground mt-1">{{ $totalLaporan }}</h3>
                    <p class="text-xs text-muted-foreground mt-1 flex items-center gap-1">
                        <span>Seluruh laporan dari 14 lab</span>
                        <span class="opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all text-blue-600 dark:text-blue-400 font-bold">&rarr;</span>
                    </p>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 group-hover:bg-blue-500 group-hover:text-white transition-all shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
            </a>

            <!-- Card 2: Laporan Aktif -->
            <a href="{{ route('laporan.index', ['status' => 'aktif']) }}"
                class="bg-card border border-border hover:border-amber-500/50 p-5 rounded-2xl shadow-xs flex items-center justify-between relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md group cursor-pointer block"
                title="Lihat Laporan Aktif">
                <div>
                    <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Laporan Aktif</p>
                    <h3 class="text-3xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">{{ $laporanProses }}</h3>
                    <p class="text-xs text-muted-foreground mt-1 flex items-center gap-1">
                        <span>Sedang triase / tindak lanjut</span>
                        <span class="opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all text-amber-600 dark:text-amber-400 font-bold">&rarr;</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0 group-hover:bg-amber-500 group-hover:text-white transition-all shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </div>
            </a>

            <!-- Card 3: Total Barang Tertinggal -->
            <a href="{{ route('barang-tertinggal.index') }}"
                class="bg-card border border-border hover:border-primary/50 p-5 rounded-2xl shadow-xs flex items-center justify-between relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md group cursor-pointer block"
                title="Lihat Semua Barang Tertinggal">
                <div>
                    <p class="text-xs font-semibold text-primary uppercase tracking-wider">Total Barang Tertinggal</p>
                    <h3 class="text-3xl font-extrabold text-foreground mt-1">{{ $totalBarangTertinggal }}</h3>
                    <p class="text-xs text-muted-foreground mt-1 flex items-center gap-1">
                        <span>Ditemukan di laboratorium</span>
                        <span class="opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all text-primary font-bold">&rarr;</span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-white transition-all shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </a>

            <!-- Card 4: Barang Tertinggal Belum Diambil -->
            <a href="{{ route('barang-tertinggal.index', ['status' => 'belum_diambil']) }}"
                class="bg-card border border-border hover:border-rose-500/50 p-5 rounded-2xl shadow-xs flex items-center justify-between relative overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md group cursor-pointer block"
                title="Lihat Barang Tertinggal Belum Diambil">
                <div>
                    <p class="text-xs font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Belum Diambil</p>
                    <h3 class="text-3xl font-extrabold text-rose-600 dark:text-rose-400 mt-1">
                        {{ $barangTertinggalBelumDiambil }}</h3>
                    <p class="text-xs text-muted-foreground mt-1 flex items-center gap-1">
                        <span>Menunggu konfirmasi pemilik</span>
                        <span class="opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all text-rose-600 dark:text-rose-400 font-bold">&rarr;</span>
                    </p>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0 group-hover:bg-rose-600 group-hover:text-white transition-all shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </a>
        </section>

        <!-- Ringkasan Kebutuhan Barang per Laboratorium (Item Aktif yang Belum Selesai) -->
        <section class="bg-card border border-border rounded-2xl p-6 shadow-xs space-y-4"
            data-purpose="lab-requirements-section">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                        <h2 class="text-base font-bold text-foreground">Ringkasan Kebutuhan Barang per Laboratorium</h2>
                    </div>
                    <p class="text-xs text-muted-foreground mt-1 ml-4.5">Daftar item barang rusak atau sedang diajukan
                        penggantian di masing-masing lab untuk mempercepat identifikasi kebutuhan.</p>
                </div>
                <span
                    class="self-start sm:self-auto text-xs font-semibold text-amber-700 dark:text-amber-300 bg-amber-500/10 border border-amber-500/30 px-3 py-1 rounded-full">
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
                        <div
                            class="bg-muted/30 border border-border rounded-xl p-4 flex flex-col justify-between hover:bg-muted/50 hover:border-primary/40 transition-all space-y-3">
                            <div>
                                <div class="flex items-center justify-between pb-3 border-b border-border/80">
                                    <div class="flex items-center gap-2.5">
                                        <span
                                            class="w-8 h-8 rounded-lg bg-primary text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                            {{ $labName }}
                                        </span>
                                        <div>
                                            <h4 class="text-sm font-bold text-foreground">Lab {{ $labName }}</h4>
                                            <p class="text-[11px] text-muted-foreground">{{ $laporans->count() }} laporan aktif</p>
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-primary bg-primary/10 border border-primary/20 px-2.5 py-0.5 rounded-md"
                                        title="Total sisa kebutuhan barang">
                                        @if($totalSisa < $totalButuh)
                                            {{ $totalSisa }} dari {{ $totalButuh }} Item
                                        @else
                                            {{ $totalButuh }} Item
                                        @endif
                                    </span>
                                </div>

                                <!-- Item Entries -->
                                <div class="mt-3 space-y-2">
                                    @foreach($allItems as $item)
                                        @php
                                            $terdistribusi = (int) $item->distribusis->sum('jumlah');
                                            $sisa = max(0, (int) $item->jumlah - $terdistribusi);
                                            $satuan = ucfirst($item->satuan ?? 'unit');
                                        @endphp
                                        <div class="bg-card p-2.5 rounded-lg border border-border/80 text-xs space-y-1.5 shadow-2xs">
                                            <div class="flex items-center justify-between font-semibold text-foreground">
                                                <span class="truncate">{{ $item->nama_barang }}</span>
                                                <span class="text-primary font-bold text-xs shrink-0 ml-2">
                                                    {{ $sisa }} dari {{ $item->jumlah }} {{ $satuan }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between gap-1 text-[11px]">
                                                <span
                                                    class="text-muted-foreground truncate">{{ $item->deskripsi ?? ($item->jenis_tindak_lanjut === 'penggantian' ? 'Perlu Penggantian' : 'Perlu Perbaikan') }}</span>
                                                <x-status-badge :status="$item->status_item" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Link -->
                            <div class="pt-2 border-t border-border/60">
                                <a href="{{ route('laporan.index', ['lab' => $labName]) }}"
                                    class="inline-flex items-center text-xs font-semibold text-primary hover:underline">
                                    <span>Lihat Laporan Lab {{ $labName }}</span>
                                    <span class="ml-1">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center bg-muted/20 rounded-xl border border-dashed border-border text-muted-foreground">
                    <div class="flex flex-col items-center gap-2">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="font-semibold text-foreground text-sm">Semua Laboratorium Terkondisikan</p>
                        <p class="text-xs">Tidak ada item kerusakan aktif yang sedang menunggu penanganan saat ini.</p>
                    </div>
                </div>
            @endif
        </section>

        <!-- 5 Barang Terbanyak yang Perlu Diganti & Belum Didistribusikan -->
        <section
            class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-amber-500/15 rounded-2xl border border-amber-500/30 p-5 shadow-xs"
            data-purpose="priority-replacement-banner">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                <div class="flex items-center gap-2.5">
                    <div
                        class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-foreground">5 BARANG TERBANYAK YANG PERLU DIGANTI &amp; BELUM
                            DIDISTRIBUSIKAN</h3>
                        <p class="text-xs text-muted-foreground mt-0.5">Prioritas pemenuhan barang rusak berkategori
                            penggantian yang masih menunggu stok atau distribusi ke lab.</p>
                    </div>
                </div>
                <a href="{{ route('distribusi.create') }}"
                    class="text-xs font-bold text-primary hover:underline whitespace-nowrap self-start sm:self-auto flex items-center gap-1">
                    <span>Distribusi Sekarang</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mt-2">
                @forelse($topPenggantianBelumDistribusi as $index => $item)
                    <div
                        class="bg-card rounded-xl border border-amber-500/30 p-4 shadow-2xs flex flex-col justify-between gap-2.5">
                        <div class="flex items-center justify-between">
                            <span
                                class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-700 dark:text-amber-400">#{{ $index + 1 }}</span>
                            <span class="text-xs text-muted-foreground">{{ $item->frekuensi }} Laporan</span>
                        </div>
                        <div class="mt-1">
                            <h5 class="text-sm font-bold text-foreground truncate" title="{{ $item->nama_barang }}">
                                {{ $item->nama_barang }}</h5>
                            <p class="text-xs font-extrabold text-amber-600 dark:text-amber-400 mt-0.5">
                                {{ $item->total_belum_distribusi }} {{ $item->satuan }}</p>
                        </div>
                        <div class="flex items-center justify-between pt-2.5 border-t border-border/60 text-[11px]">
                            <span class="text-muted-foreground">Diajukan: {{ $item->total_diajukan }} {{ $item->satuan }}</span>
                            <span class="font-semibold text-amber-600 dark:text-amber-400">Belum Kirim</span>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full p-6 rounded-xl bg-card/60 border border-dashed border-border text-center text-xs text-muted-foreground">
                        Tidak ada barang yang perlu diganti dan belum didistribusikan saat ini. Semua kebutuhan telah terpenuhi!
                        🎉
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Frekuensi Laporan per Laboratorium (Grid 14 Lab) -->
        <section class="bg-card border border-border rounded-2xl p-6 shadow-xs space-y-4"
            data-purpose="report-frequency-section">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-border/80">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                        <h2 class="text-base font-bold text-foreground">Frekuensi Laporan per Laboratorium</h2>
                    </div>
                    <p class="text-xs text-muted-foreground mt-0.5 ml-4.5">Distribusi akumulasi permasalahan di 14
                        laboratorium komputer (D2A – D3N).</p>
                </div>
                <span
                    class="self-start sm:self-auto text-xs font-semibold text-primary bg-primary/10 border border-primary/20 px-3 py-1 rounded-full">
                    Total 14 Laboratorium
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                @foreach($labReports as $lab => $count)
                    <a href="{{ route('laporan.index', ['lab' => $lab]) }}"
                        class="bg-muted/40 hover:bg-primary/5 hover:border-primary/50 border border-border rounded-xl p-3.5 text-center transition-all group flex flex-col justify-between">
                        <span
                            class="text-xs font-semibold text-muted-foreground group-hover:text-primary transition-colors">{{ $lab }}</span>
                        <div class="text-2xl font-black text-foreground my-1">{{ $count }}</div>
                        <span class="text-[11px] text-muted-foreground font-medium">laporan</span>
                    </a>
                @endforeach
            </div>
        </section>


        <!-- 3 Data Tables: Laporan Terbaru, Barang Tertinggal Terbaru, Distribusi Terakhir -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6" data-purpose="recent-activities-tables">

            <!-- Table 1: Laporan Tertulis Terbaru -->
            <div class="bg-card rounded-2xl border border-border p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-border/80">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <h3 class="text-sm font-bold text-foreground">Laporan Terbaru</h3>
                        </div>
                        <a href="{{ route('laporan.index') }}"
                            class="text-xs font-semibold text-primary hover:underline">Lihat Semua →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr
                                    class="border-b border-border text-muted-foreground uppercase text-[10px] tracking-wider">
                                    <th class="py-2.5 px-2 font-semibold">LAB</th>
                                    <th class="py-2.5 px-2 font-semibold">PELAPOR</th>
                                    <th class="py-2.5 px-2 font-semibold text-center">STATUS</th>
                                    <th class="py-2.5 px-2 font-semibold text-right">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/60">
                                @forelse($recentLaporans as $lap)
                                    <tr class="hover:bg-muted/40 transition">
                                        <td class="py-3 px-2">
                                            <span
                                                class="font-bold text-primary bg-primary/10 px-2 py-1 rounded text-xs">{{ $lap->lab_nama }}</span>
                                        </td>
                                        <td class="py-3 px-2">
                                            <p class="font-semibold text-foreground truncate max-w-[100px]">
                                                {{ $lap->nama_pelapor ?? 'Laboran' }}</p>
                                            <p class="text-[11px] text-muted-foreground">
                                                {{ $lap->tanggal_laporan?->format('d M Y') }}</p>
                                        </td>
                                        <td class="py-3 px-2 text-center">
                                            <x-status-badge :status="$lap->status" />
                                        </td>
                                        <td class="py-3 px-2 text-right">
                                            <a href="{{ route('laporan.show', $lap) }}"
                                                class="px-2.5 py-1 text-xs font-medium text-primary bg-primary/10 hover:bg-primary/20 rounded-lg transition inline-block">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-xs text-muted-foreground">Belum ada laporan
                                            dicatat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Table 2: Barang Tertinggal Terbaru -->
            <div class="bg-card rounded-2xl border border-border p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-border/80">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <h3 class="text-sm font-bold text-foreground">Barang Tertinggal Terbaru</h3>
                        </div>
                        <a href="{{ route('barang-tertinggal.index') }}"
                            class="text-xs font-semibold text-primary hover:underline">Lihat Semua →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr
                                    class="border-b border-border text-muted-foreground uppercase text-[10px] tracking-wider">
                                    <th class="py-2.5 px-2 font-semibold">LAB</th>
                                    <th class="py-2.5 px-2 font-semibold">BARANG</th>
                                    <th class="py-2.5 px-2 font-semibold text-center">STATUS</th>
                                    <th class="py-2.5 px-2 font-semibold text-right">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/60">
                                @forelse($recentBarangTertinggal as $bt)
                                    <tr class="hover:bg-muted/40 transition">
                                        <td class="py-3 px-2">
                                            <span
                                                class="font-bold text-amber-600 dark:text-amber-400 bg-amber-500/10 px-2 py-1 rounded text-xs">{{ $bt->lab_nama }}</span>
                                        </td>
                                        <td class="py-3 px-2">
                                            <p class="font-semibold text-foreground truncate max-w-[100px]">
                                                {{ $bt->nama_barang }}</p>
                                            <p class="text-[11px] text-muted-foreground">
                                                {{ $bt->tanggal_ditemukan?->format('d M Y') }}</p>
                                        </td>
                                        <td class="py-3 px-2 text-center">
                                            <x-status-badge :status="$bt->status" />
                                        </td>
                                        <td class="py-3 px-2 text-right">
                                            <a href="{{ route('barang-tertinggal.edit', $bt) }}"
                                                class="px-2.5 py-1 text-xs font-medium text-amber-600 bg-amber-500/10 hover:bg-amber-500/20 rounded-lg transition inline-block">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-xs text-muted-foreground">Belum ada barang
                                            tertinggal.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Table 3: Distribusi Barang Terakhir -->
            <div class="bg-card rounded-2xl border border-border p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-border/80">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <h3 class="text-sm font-bold text-foreground">Distribusi Terakhir</h3>
                        </div>
                        <a href="{{ route('distribusi.index') }}"
                            class="text-xs font-semibold text-primary hover:underline">Lihat Semua →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr
                                    class="border-b border-border text-muted-foreground uppercase text-[10px] tracking-wider">
                                    <th class="py-2.5 px-2 font-semibold">BARANG</th>
                                    <th class="py-2.5 px-2 font-semibold">TUJUAN</th>
                                    <th class="py-2.5 px-2 font-semibold">SUMBER</th>
                                    <th class="py-2.5 px-2 font-semibold text-right">TANGGAL</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/60">
                                @forelse($recentDistribusi as $dist)
                                    <tr class="hover:bg-muted/40 transition">
                                        <td class="py-3 px-2">
                                            <p class="font-semibold text-foreground truncate max-w-[90px]">
                                                {{ $dist->stokGudang?->barangMaster?->nama_barang ?? 'Barang' }}</p>
                                            <p class="text-[11px] text-muted-foreground">{{ $dist->jumlah }}
                                                {{ $dist->stokGudang?->barangMaster?->satuan ?? 'unit' }}</p>
                                        </td>
                                        <td class="py-3 px-2">
                                            <p class="font-semibold text-primary">Lab {{ $dist->lab_tujuan }}</p>
                                            <p class="text-[11px] text-muted-foreground truncate">
                                                {{ $dist->kode_pc_tujuan ?? '—' }}</p>
                                        </td>
                                        <td class="py-3 px-2">
                                            @if($dist->asal_barang === 'pengajuan')
                                                <span
                                                    class="px-2 py-0.5 text-[10px] font-bold text-primary bg-primary/10 rounded">Pengadaan</span>
                                            @else
                                                <span
                                                    class="px-2 py-0.5 text-[10px] font-semibold text-muted-foreground bg-muted rounded">Stok</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-2 text-right text-muted-foreground">
                                            {{ $dist->tanggal_distribusi?->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-xs text-muted-foreground">Belum ada
                                            aktivitas distribusi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>

    </div>
@endsection