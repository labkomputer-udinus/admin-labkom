@extends('layouts.app')

@section('title', 'Pengajuan Pengadaan')
@section('header_title', 'Pengajuan Pengadaan ke Divisi Lain')

@section('content')
<div class="flex flex-col gap-6 max-w-7xl mx-auto pb-10">

    {{-- Top Action & Filter Bar --}}
    <div class="bg-card border border-border p-6 sm:p-7 rounded-3xl shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
            <div>
                <h3 class="text-base font-bold text-foreground">Daftar Pengajuan Pengadaan Barang</h3>
                <p class="text-sm text-muted-foreground mt-0.5">
                    Lacak pengajuan barang pengganti ke divisi terkait (Sarpras dll.) hingga verifikasi penerimaan.
                </p>
            </div>

            <div class="flex items-center gap-2.5 flex-shrink-0">
                <a href="{{ route('ekspor.pengajuan.excel', request()->query()) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-sm font-medium transition-colors"
                    title="Unduh format Excel (.xlsx)">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Ekspor Excel</span>
                </a>

                <a href="{{ route('ekspor.pengajuan.pdf', request()->query()) }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-sm font-medium transition-colors"
                    title="Cetak atau simpan PDF">
                    <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span>Ekspor PDF</span>
                </a>

                <a href="{{ route('pengajuan.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold shadow-md shadow-primary/20 hover:bg-primary/90 transition-transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Buat Pengajuan Baru</span>
                </a>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('pengajuan.index') }}" class="flex flex-wrap items-end gap-3.5 pt-5 border-t border-border">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Cari Pengajuan</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="No. pengajuan / divisi / barang..."
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground placeholder-muted-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
            </div>

            <div class="w-48">
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Status Keputusan</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="w-40">
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
            </div>

            <div class="w-40">
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                    class="py-2.5 px-5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors shadow-sm">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date']))
                    <a href="{{ route('pengajuan.index') }}"
                        class="p-2.5 rounded-xl border border-border text-muted-foreground hover:text-foreground hover:bg-muted transition-colors"
                        title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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
                    <tr class="bg-muted/60 border-b border-border text-muted-foreground text-xs font-semibold uppercase tracking-wide">
                        <th class="py-4 px-6 whitespace-nowrap">No. Pengajuan</th>
                        <th class="py-4 px-4 whitespace-nowrap">Divisi Tujuan</th>
                        <th class="py-4 px-4 whitespace-nowrap">Tgl. Ajuan</th>
                        <th class="py-4 px-4 whitespace-nowrap">Jumlah Item</th>
                        <th class="py-4 px-4 whitespace-nowrap">Status</th>
                        <th class="py-4 px-6 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                    @forelse($pengajuans as $peng)
                        @php
                            $totalItems    = $peng->items->count();
                            $verifiedItems = $peng->items->whereNotNull('status_kesesuaian')->count();
                            $totalQty      = $peng->items->sum('jumlah_diajukan');
                            $itemsTooltip  = $peng->items->map(fn($i, $idx) => ($idx + 1) . '. ' . $i->nama_barang . ' (' . $i->jumlah_diajukan . ' ' . $i->satuan . ')')->join("\n");
                        @endphp
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="py-4 px-6">
                                <span class="font-mono font-bold text-primary text-sm">{{ $peng->nomor_pengajuan }}</span>
                            </td>
                            <td class="py-4 px-4 font-semibold text-foreground text-sm">
                                {{ $peng->divisi_tujuan }}
                            </td>
                            <td class="py-4 px-4 text-sm text-foreground whitespace-nowrap">
                                {{ $peng->tanggal_ajuan?->format('d M Y') }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-muted/60 border border-border/80 cursor-help"
                                    title="{{ $itemsTooltip }}">
                                    <span class="font-bold text-xs text-primary flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        {{ $totalItems }} Barang
                                    </span>
                                    <span class="text-xs text-muted-foreground font-medium border-l border-border/80 pl-2">
                                        {{ $totalQty }} total
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1 items-start">
                                    <x-status-badge :status="$peng->status" />
                                    @if($peng->status === 'disetujui')
                                        @if($verifiedItems === $totalItems && $totalItems > 0)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Diterima Lengkap
                                            </span>
                                        @elseif($verifiedItems > 0)
                                            <span class="text-[11px] font-semibold text-orange-600 dark:text-orange-400">
                                                Diterima {{ $verifiedItems }}/{{ $totalItems }}
                                            </span>
                                        @else
                                            <span class="text-[11px] text-muted-foreground italic">
                                                Belum diverifikasi
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($peng->status === 'disetujui')
                                        <a href="{{ route('penerimaan.show', $peng) }}"
                                            class="px-3.5 py-1.5 rounded-xl bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400 font-bold text-sm hover:bg-teal-500 hover:text-white transition-colors whitespace-nowrap">
                                            Verifikasi
                                        </a>
                                    @endif
                                    <a href="{{ route('pengajuan.show', $peng) }}"
                                        class="px-3.5 py-1.5 rounded-xl bg-primary text-white font-semibold text-sm shadow-sm hover:bg-primary/90 transition-colors">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-muted-foreground">
                                    <svg class="w-12 h-12 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-foreground">Belum ada pengajuan pengadaan</p>
                                        <p class="text-sm mt-0.5">Buat pengajuan baru ketika ada item yang membutuhkan penggantian barang.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuans->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $pengajuans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
