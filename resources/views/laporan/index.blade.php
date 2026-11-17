@extends('layouts.app')

@section('title', 'Laporan Permasalahan Laboratorium')
@section('header_title', 'Laporan Permasalahan Laboratorium')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-12">

    <!-- Filter Card -->
    <section class="bg-card rounded-2xl border border-border shadow-xs p-6" data-purpose="filter-section">
        <!-- Filter Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-border/80">
            <div>
                <h2 class="text-base font-bold text-foreground tracking-tight">Rekapitulasi Laporan Masuk</h2>
                <p class="text-xs text-muted-foreground mt-0.5">Pencatatan laporan manual dari laboran di 14 laboratorium komputer.</p>
            </div>
            
            <!-- Export and Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <!-- Ekspor Excel -->
                <a href="{{ route('ekspor.laporan.excel', request()->query()) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 rounded-xl transition-all"
                    title="Unduh format Excel (.xlsx)">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                    <span>Ekspor Excel</span>
                </a>

                <!-- Ekspor PDF -->
                <a href="{{ route('ekspor.laporan.pdf', request()->query()) }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-rose-700 dark:text-rose-300 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 rounded-xl transition-all"
                    title="Cetak atau simpan format PDF">
                    <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                    <span>Ekspor PDF</span>
                </a>

                <!-- Input Laporan Baru -->
                <a href="{{ route('laporan.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-primary hover:bg-primary/90 rounded-xl shadow-sm shadow-primary/25 transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                    </svg>
                    <span>Input Laporan Baru</span>
                </a>
            </div>
        </div>

        <!-- Filter Grid Form -->
        <form method="GET" action="{{ route('laporan.index') }}" class="mt-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 items-end">
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif

            <!-- Cari Laporan / Item -->
            <div class="space-y-1">
                <label class="block text-[11px] font-semibold text-muted-foreground truncate">Cari Laporan / Item</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-muted-foreground">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-9 pr-3 py-2 text-xs bg-muted/40 border border-border rounded-xl text-foreground placeholder-muted-foreground focus:bg-card focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                        placeholder="Nama barang / pelapor...">
                </div>
            </div>

            <!-- Pilih Lab -->
            <div class="space-y-1">
                <label class="block text-[11px] font-semibold text-muted-foreground truncate">Pilih Lab</label>
                <div class="relative">
                    <select name="lab"
                        class="w-full py-2 pl-3 pr-8 text-xs bg-muted/40 border border-border rounded-xl text-foreground focus:bg-card focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all appearance-none cursor-pointer">
                        <option value="">Semua Laboratorium</option>
                        @foreach($labs as $l)
                            <option value="{{ $l }}" {{ request('lab') === $l ? 'selected' : '' }}>Lab {{ $l }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-muted-foreground">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Status Laporan -->
            <div class="space-y-1">
                <label class="block text-[11px] font-semibold text-muted-foreground truncate">Status Laporan</label>
                <div class="relative">
                    <select name="status"
                        class="w-full py-2 pl-3 pr-8 text-xs bg-muted/40 border border-border rounded-xl text-foreground focus:bg-card focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all appearance-none cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif (Dalam Proses)</option>
                        <option value="dicatat" {{ request('status') === 'dicatat' ? 'selected' : '' }}>Dicatat</option>
                        <option value="ditriase" {{ request('status') === 'ditriase' ? 'selected' : '' }}>Ditriase</option>
                        <option value="dalam_proses" {{ request('status') === 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                        <option value="menunggu_keputusan_divisi" {{ request('status') === 'menunggu_keputusan_divisi' ? 'selected' : '' }}>Menunggu Divisi</option>
                        <option value="barang_diterima" {{ request('status') === 'barang_diterima' ? 'selected' : '' }}>Barang Diterima</option>
                        <option value="siap_distribusi" {{ request('status') === 'siap_distribusi' ? 'selected' : '' }}>Siap Distribusi</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-muted-foreground">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Dari Tanggal -->
            <div class="space-y-1">
                <label class="block text-[11px] font-semibold text-muted-foreground truncate">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full py-1.5 px-3 text-xs bg-muted/40 border border-border rounded-xl text-foreground focus:bg-card focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
            </div>

            <!-- Sampai Tanggal -->
            <div class="space-y-1">
                <label class="block text-[11px] font-semibold text-muted-foreground truncate">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="w-full py-1.5 px-3 text-xs bg-muted/40 border border-border rounded-xl text-foreground focus:bg-card focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <button type="submit"
                    class="flex-1 py-2 px-4 bg-primary hover:bg-primary/90 text-white font-semibold text-xs rounded-xl shadow-sm shadow-primary/25 transition-all text-center flex items-center justify-center gap-1.5 h-9">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                    <span>Filter</span>
                </button>

                @if(request()->anyFilled(['search', 'lab', 'status', 'start_date', 'end_date']) || request('sort'))
                    <a href="{{ route('laporan.index') }}"
                        class="p-2 h-9 w-9 rounded-xl border border-border text-muted-foreground hover:text-foreground hover:bg-muted flex items-center justify-center transition-colors shrink-0"
                        title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </section>

    <!-- Table Section -->
    <section class="bg-card rounded-2xl border border-border shadow-xs overflow-hidden" data-purpose="table-section">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="laporan-table">
                <thead>
                    <tr class="bg-muted/50 border-b border-border/80 text-[11px] font-bold uppercase tracking-wider text-muted-foreground">
                        <th class="py-3.5 px-5" scope="col">LAB &amp; ID</th>
                        <th class="py-3.5 px-4 whitespace-nowrap" scope="col">
                            <a href="{{ route('laporan.index', array_merge(request()->query(), ['sort' => ($sort ?? request('sort', 'desc')) === 'asc' ? 'desc' : 'asc'])) }}"
                                class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider hover:text-foreground transition-colors group select-none"
                                title="Urutkan berdasarkan tanggal (Saat ini: {{ ($sort ?? request('sort', 'desc')) === 'asc' ? 'Terlama' : 'Terbaru' }})">
                                <span>TANGGAL</span>
                                <span class="inline-flex items-center text-primary group-hover:scale-110 transition-transform">
                                    @if(($sort ?? request('sort', 'desc')) === 'asc')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M5 15l7-7 7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                                        </svg>
                                    @endif
                                </span>
                            </a>
                        </th>
                        <th class="py-3.5 px-4 whitespace-nowrap" scope="col">PELAPOR</th>
                        <th class="py-3.5 px-4" scope="col">ITEM BERMASALAH</th>
                        <th class="py-3.5 px-4 whitespace-nowrap" scope="col">STATUS</th>
                        <th class="py-3.5 px-4 whitespace-nowrap" scope="col">TGL. SELESAI</th>
                        <th class="py-3.5 px-5 text-right whitespace-nowrap" scope="col">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60 text-xs text-foreground font-medium">
                    @forelse($laporans as $lap)
                        <tr class="hover:bg-muted/40 transition-colors">
                            <!-- LAB & ID -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-1 rounded-md text-[11px] font-bold bg-primary/10 text-primary border border-primary/20 shrink-0">
                                        {{ $lap->lab_nama }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-foreground">Lab {{ $lap->lab_nama }}</div>
                                        <div class="text-[11px] text-muted-foreground font-mono">#LAP-{{ str_pad($lap->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Tanggal -->
                            <td class="py-4 px-4 text-muted-foreground whitespace-nowrap font-medium">
                                {{ $lap->tanggal_laporan?->format('d/m/Y') }}
                            </td>

                            <!-- Pelapor -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="font-semibold text-foreground">{{ $lap->nama_pelapor ?? 'Laboran' }}</div>
                                @if($lap->jabatan_pelapor)
                                    <div class="text-[11px] text-muted-foreground">{{ $lap->jabatan_pelapor }}</div>
                                @endif
                            </td>

                            <!-- Item Bermasalah -->
                            <td class="py-4 px-4">
                                @if($lap->bentuk_ketidaksesuaian)
                                    <div class="font-semibold text-foreground mb-1 line-clamp-1" title="{{ $lap->bentuk_ketidaksesuaian }}">
                                        {{ $lap->bentuk_ketidaksesuaian }}
                                    </div>
                                @endif
                                <div class="space-y-1 max-w-[280px]">
                                    @foreach($lap->items->take(3) as $item)
                                        <div class="flex items-center gap-1.5 text-[11px]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
                                            <span class="font-semibold text-foreground">{{ $item->nama_barang }}</span>
                                            <span class="text-muted-foreground">&times;{{ $item->jumlah }}</span>
                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-muted text-muted-foreground font-medium">
                                                {{ $item->jenis_tindak_lanjut }}
                                            </span>
                                        </div>
                                    @endforeach
                                    @if($lap->items->count() > 3)
                                        <div class="text-[10px] text-muted-foreground pl-3">
                                            +{{ $lap->items->count() - 3 }} item lainnya
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <x-status-badge :status="$lap->status" />
                                    @if($lap->status_akhir === 'closed')
                                        <div>
                                            <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/20">
                                                Closed
                                            </span>
                                        </div>
                                    @elseif($lap->status_akhir === 'follow_up')
                                        <div>
                                            <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/20">
                                                Follow Up
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Tgl. Selesai -->
                            <td class="py-4 px-4 text-muted-foreground whitespace-nowrap font-medium">
                                {{ $lap->tanggal_selesai ? $lap->tanggal_selesai->format('d/m/Y') : '—' }}
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-5 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Detail -->
                                    <a href="{{ route('laporan.show', $lap) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1 bg-primary hover:bg-primary/90 text-white text-[11px] font-semibold rounded-lg shadow-sm transition-colors"
                                        title="Lihat Detail & Tracking">
                                        <span>Detail</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                                        </svg>
                                    </a>

                                    <!-- Edit -->
                                    <a href="{{ route('laporan.edit', $lap) }}"
                                        class="p-1.5 text-muted-foreground hover:text-amber-600 hover:bg-amber-500/10 rounded-md transition-colors"
                                        title="Edit Laporan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                                        </svg>
                                    </a>

                                    <!-- Hapus -->
                                    <form method="POST" action="{{ route('laporan.destroy', $lap) }}" class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini beserta seluruh riwayat penanganannya? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-muted-foreground hover:text-rose-600 hover:bg-rose-500/10 rounded-md transition-colors"
                                            title="Hapus Laporan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-muted-foreground">
                                    <svg class="w-12 h-12 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-foreground text-sm">Belum ada laporan ditemukan</p>
                                        <p class="text-xs text-muted-foreground mt-0.5">Silakan sesuaikan filter atau input laporan baru.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($laporans->hasPages())
            <div class="px-5 py-3 border-t border-border">
                {{ $laporans->links() }}
            </div>
        @endif
    </section>

</div>
@endsection
