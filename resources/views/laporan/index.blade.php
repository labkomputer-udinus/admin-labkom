@extends('layouts.app')

@section('title', 'Daftar Laporan Permasalahan')
@section('header_title', 'Laporan Permasalahan Laboratorium')

@section('content')
    <div class="space-y-6 max-w-7xl mx-auto pb-10">

        {{-- Top Action Bar & Filters Container --}}
        <div class="bg-card border border-border p-6 sm:p-7 rounded-3xl shadow-xs">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <div>
                    <h3 class="text-base font-bold text-foreground">Rekapitulasi Laporan Masuk</h3>
                    <p class="text-sm text-muted-foreground mt-0.5">Pencatatan laporan manual dari laboran di 14
                        laboratorium komputer.</p>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('ekspor.laporan.excel', request()->query()) }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-sm font-medium transition-colors"
                        title="Unduh format Excel (.xlsx)">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Ekspor Excel</span>
                    </a>

                    <a href="{{ route('ekspor.laporan.pdf', request()->query()) }}" target="_blank"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-sm font-medium transition-colors"
                        title="Cetak atau simpan format PDF">
                        <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span>Ekspor PDF</span>
                    </a>

                    <a href="{{ route('laporan.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold shadow-md shadow-primary/20 hover:bg-primary/90 transition-transform active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Input Laporan Baru</span>
                    </a>
                </div>
            </div>

            {{-- Filter Form --}}
            <form method="GET" action="{{ route('laporan.index') }}"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 pt-4 border-t border-border">

                @if(request('sort'))
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                @endif

                <div>
                    <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Cari Laporan / Item</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama barang / pelapor..."
                        class="w-full px-3 py-2 rounded-xl bg-input border border-border text-sm text-foreground placeholder-muted-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Pilih Lab</label>
                    <select name="lab"
                        class="w-full px-3 py-2 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        <option value="">Semua Laboratorium</option>
                        @foreach($labs as $l)
                            <option value="{{ $l }}" {{ request('lab') === $l ? 'selected' : '' }}>Lab {{ $l }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Status Laporan</label>
                    <select name="status"
                        class="w-full px-3 py-2 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        <option value="">Semua Status</option>
                        <option value="dicatat" {{ request('status') === 'dicatat' ? 'selected' : '' }}>Dicatat</option>
                        <option value="ditriase" {{ request('status') === 'ditriase' ? 'selected' : '' }}>Ditriase</option>
                        <option value="dalam_proses" {{ request('status') === 'dalam_proses' ? 'selected' : '' }}>Dalam Proses
                        </option>
                        <option value="menunggu_keputusan_divisi" {{ request('status') === 'menunggu_keputusan_divisi' ? 'selected' : '' }}>Menunggu Divisi</option>
                        <option value="barang_diterima" {{ request('status') === 'barang_diterima' ? 'selected' : '' }}>Barang
                            Diterima</option>
                        <option value="siap_distribusi" {{ request('status') === 'siap_distribusi' ? 'selected' : '' }}>Siap
                            Distribusi</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full px-3 py-2 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full px-3 py-2 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 py-2 px-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'lab', 'status', 'start_date', 'end_date']) || request('sort'))
                        <a href="{{ route('laporan.index') }}"
                            class="p-2 rounded-xl border border-border text-muted-foreground hover:text-foreground hover:bg-muted transition-colors"
                            title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table Container --}}
        <div class="bg-card border border-border rounded-3xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr
                            class="bg-muted/60 border-b border-border text-muted-foreground text-xs font-semibold uppercase tracking-wide">
                            <th class="py-3 px-5 whitespace-nowrap">Lab & ID</th>
                            <th class="py-3 px-4 whitespace-nowrap">
                                <a href="{{ route('laporan.index', array_merge(request()->query(), ['sort' => ($sort ?? request('sort', 'desc')) === 'asc' ? 'desc' : 'asc'])) }}"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide hover:text-foreground transition-colors group select-none"
                                    title="Urutkan berdasarkan tanggal (Saat ini: {{ ($sort ?? request('sort', 'desc')) === 'asc' ? 'Terlama' : 'Terbaru' }})">
                                    <span>Tanggal</span>
                                    <span class="inline-flex items-center text-primary group-hover:scale-110 transition-transform">
                                        @if(($sort ?? request('sort', 'desc')) === 'asc')
                                            {{-- Arrow Up (Terlama ke Terbaru) --}}
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                                            </svg>
                                        @else
                                            {{-- Arrow Down (Terbaru ke Terlama) --}}
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        @endif
                                    </span>
                                </a>
                            </th>
                            <th class="py-3 px-4 whitespace-nowrap">Pelapor</th>
                            <th class="py-3 px-4">Item Bermasalah</th>
                            <th class="py-3 px-4 whitespace-nowrap">Status</th>
                            <th class="py-3 px-4 whitespace-nowrap">Tgl. Selesai</th>
                            <th class="py-3 px-5 text-right whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border/60">
                        @forelse($laporans as $lap)
                            <tr class="hover:bg-muted/30 transition-colors">
                                {{-- Lab & ID --}}
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-black text-sm flex-shrink-0">
                                            {{ $lap->lab_nama }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-foreground text-sm leading-tight">Lab
                                                {{ $lap->lab_nama }}</div>
                                            <div class="text-xs text-muted-foreground font-mono">
                                                #LAP-{{ str_pad($lap->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tanggal --}}
                                <td class="py-3.5 px-4 text-sm text-foreground font-medium whitespace-nowrap">
                                    {{ $lap->tanggal_laporan?->format('d/m/Y') }}
                                </td>

                                {{-- Pelapor --}}
                                <td class="py-3.5 px-4 text-sm text-foreground">
                                    <div class="font-medium">{{ $lap->nama_pelapor ?? 'Laboran' }}</div>
                                    @if($lap->jabatan_pelapor)
                                        <div class="text-xs text-muted-foreground">{{ $lap->jabatan_pelapor }}</div>
                                    @endif
                                </td>

                                {{-- Item Bermasalah -- ditampilkan lebih ringkas --}}
                                <td class="py-3.5 px-4">
                                    @if($lap->bentuk_ketidaksesuaian)
                                        <div class="text-xs font-semibold text-primary mb-1.5 line-clamp-1" title="{{ $lap->bentuk_ketidaksesuaian }}">
                                            {{ $lap->bentuk_ketidaksesuaian }}
                                        </div>
                                    @endif
                                    <div class="space-y-1.5 max-w-[280px]">
                                        @foreach($lap->items->take(3) as $item)
                                            <div class="flex items-start gap-2 text-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-primary mt-1.5 flex-shrink-0"></span>
                                                <div class="min-w-0">
                                                    <span class="font-semibold text-foreground">{{ $item->nama_barang }}</span>
                                                    <span class="text-muted-foreground"> &times;{{ $item->jumlah }}</span>
                                                    <span
                                                        class="ml-1 inline-block px-1.5 py-0.5 rounded-md bg-muted text-[11px] text-muted-foreground font-medium">
                                                        {{ $item->jenis_tindak_lanjut }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($lap->items->count() > 3)
                                            <span class="text-xs text-muted-foreground pl-3.5">+{{ $lap->items->count() - 3 }} item
                                                lainnya</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="py-3.5 px-4">
                                    <div class="space-y-1">
                                        <x-status-badge :status="$lap->status" />
                                        @if($lap->status_akhir === 'closed')
                                            <div>
                                                <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                                    Closed
                                                </span>
                                            </div>
                                        @elseif($lap->status_akhir === 'follow_up')
                                            <div>
                                                <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                                    Follow Up
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                {{-- Tgl. Selesai --}}
                                <td class="py-3.5 px-4 text-sm text-muted-foreground whitespace-nowrap">
                                    {{ $lap->tanggal_selesai ? $lap->tanggal_selesai->format('d/m/Y') : '—' }}
                                </td>

                                {{-- Aksi --}}
                                <td class="py-3.5 px-5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('laporan.show', $lap) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary text-white text-xs font-semibold shadow-2xs hover:bg-primary/90 transition-colors"
                                            title="Lihat Detail & Tracking">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                            </svg>
                                            Detail
                                        </a>

                                        {{-- Edit (Icon Saja) --}}
                                        <a href="{{ route('laporan.edit', $lap) }}"
                                            class="p-1.5 rounded-xl border border-border text-muted-foreground hover:text-amber-600 hover:bg-amber-500/10 hover:border-amber-500/30 transition-colors"
                                            title="Edit Informasi Laporan">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        {{-- Hapus (Icon Saja) --}}
                                        <form method="POST" action="{{ route('laporan.destroy', $lap) }}" class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini beserta seluruh riwayat penanganannya? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1.5 rounded-xl border border-destructive/30 text-destructive hover:bg-destructive hover:text-white hover:border-destructive transition-colors"
                                                title="Hapus Laporan">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
                                            <p class="font-semibold text-foreground">Belum ada laporan ditemukan</p>
                                            <p class="text-sm mt-0.5">Silakan input laporan baru dari catatan tertulis laboran.
                                            </p>
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
        </div>

    </div>
@endsection