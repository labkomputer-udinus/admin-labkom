@extends('layouts.app')

@section('title', 'Log Distribusi Barang')
@section('header_title', 'Log Distribusi & Dokumentasi Akreditasi')

@section('content')
<div class="flex flex-col gap-6 max-w-7xl mx-auto pb-10">

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="flex items-center gap-3 p-4 rounded-2xl bg-destructive/10 border border-destructive/30 text-destructive text-sm font-medium">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Top Action & Info Bar --}}
    <div class="bg-card border border-border p-6 sm:p-7 rounded-3xl shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
            <div>
                <h3 class="text-base font-bold text-foreground">Riwayat Distribusi Barang ke 14 Laboratorium</h3>
                <p class="text-sm text-muted-foreground mt-0.5">
                    Catatan asal-usul barang (Pengadaan / Stok Lama) dan tujuan spesifik (Lab & Kode PC) untuk dokumentasi audit akreditasi.
                </p>
            </div>

            <div class="flex items-center gap-2.5 flex-shrink-0">
                <a href="{{ route('ekspor.distribusi.excel', request()->query()) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-sm font-medium transition-colors"
                    title="Unduh format Excel (.xlsx)">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Ekspor Excel</span>
                </a>

                <a href="{{ route('ekspor.distribusi.pdf', request()->query()) }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-sm font-medium transition-colors"
                    title="Cetak atau simpan PDF">
                    <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span>Ekspor PDF</span>
                </a>

                <a href="{{ route('distribusi.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold shadow-md shadow-primary/20 hover:bg-primary/90 transition-transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>+ Distribusi Baru</span>
                </a>
            </div>
        </div>

        {{-- Filter Row --}}
        <form method="GET" action="{{ route('distribusi.index') }}"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-3.5 pt-5 border-t border-border">

            <div>
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Cari PC / Penerima / Barang</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor PC / nama..."
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground placeholder-muted-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Jenis Distribusi</label>
                <select name="jenis_distribusi" class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    <option value="">Semua Jenis</option>
                    <option value="laporan" {{ request('jenis_distribusi') === 'laporan' ? 'selected' : '' }}>📋 Berdasarkan Laporan</option>
                    <option value="mandiri" {{ request('jenis_distribusi') === 'mandiri' ? 'selected' : '' }}>⚡ Distribusi Mandiri</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Laboratorium Tujuan</label>
                <select name="lab" class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    <option value="">Semua Lab</option>
                    @foreach($labs as $l)
                        <option value="{{ $l }}" {{ request('lab') === $l ? 'selected' : '' }}>Lab {{ $l }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Asal Sumber Barang</label>
                <select name="asal_barang" class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    <option value="">Semua Asal</option>
                    <option value="pengajuan" {{ request('asal_barang') === 'pengajuan' ? 'selected' : '' }}>Pengajuan Pengadaan</option>
                    <option value="stok_lama" {{ request('asal_barang') === 'stok_lama' ? 'selected' : '' }}>Stok Lama Gudang</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="flex-1 py-2.5 px-4 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors shadow-sm">
                    Filter
                </button>
                @if(request()->anyFilled(['search', 'lab', 'asal_barang', 'jenis_distribusi', 'start_date', 'end_date']))
                    <a href="{{ route('distribusi.index') }}"
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
                        <th class="py-4 px-6 whitespace-nowrap">Tanggal Distribusi</th>
                        <th class="py-4 px-4">Nama Barang</th>
                        <th class="py-4 px-4 whitespace-nowrap">Jumlah</th>
                        <th class="py-4 px-4 whitespace-nowrap">Asal Barang (Audit)</th>
                        <th class="py-4 px-4 whitespace-nowrap">Lab Tujuan</th>
                        <th class="py-4 px-4 whitespace-nowrap">Kode PC</th>
                        <th class="py-4 px-6 whitespace-nowrap">Penerima</th>
                        <th class="py-4 px-4 whitespace-nowrap">Konfirmasi Terima</th>
                        <th class="py-4 px-4 whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                    @forelse($distribusis as $dist)
                        <tr class="hover:bg-muted/30 transition-colors">
                            <td class="py-4 px-6 text-sm font-medium text-foreground whitespace-nowrap">
                                {{ $dist->tanggal_distribusi?->format('d M Y') }}
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-foreground">
                                    {{ $dist->stokGudang?->barangMaster?->nama_barang ?? 'Barang' }}
                                </div>
                                @if(!empty($dist->stokGudang?->barangMaster?->spesifikasi))
                                    <div class="text-[11px] text-muted-foreground font-medium">
                                        {{ $dist->stokGudang->barangMaster->spesifikasi }}
                                    </div>
                                @endif
                                @if($dist->isMandiri())
                                    <div class="mt-1 flex items-center gap-1.5 flex-wrap">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300 font-bold text-[10px]">
                                            ⚡ Mandiri: {{ $dist->kategori_alasan ?? 'Umum' }}
                                        </span>
                                        @if(!empty($dist->alasan_distribusi))
                                            <span class="text-[11px] text-muted-foreground italic truncate max-w-xs" title="{{ $dist->alasan_distribusi }}">
                                                — {{ $dist->alasan_distribusi }}
                                            </span>
                                        @endif
                                    </div>
                                @elseif($dist->laporanItem)
                                    <div class="mt-1 flex items-center gap-1.5 flex-wrap">
                                        <a href="{{ route('laporan.show', $dist->laporanItem->laporan_id) }}"
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 font-bold text-[10px] hover:underline"
                                            title="Lihat Detail Laporan">
                                            📋 {{ $dist->laporanItem->laporan->nomor_laporan ?? '#LAP-' . str_pad($dist->laporanItem->laporan_id, 4, '0', STR_PAD_LEFT) }}
                                        </a>
                                        @if(!empty($dist->laporanItem->deskripsi))
                                            <span class="text-[11px] text-muted-foreground italic truncate max-w-xs" title="{{ $dist->laporanItem->deskripsi }}">
                                                ({{ $dist->laporanItem->deskripsi }})
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-4 font-bold text-primary whitespace-nowrap">
                                {{ $dist->jumlah }}
                                <span class="text-muted-foreground font-normal text-xs ml-0.5">{{ $dist->stokGudang?->barangMaster?->satuan ?? 'unit' }}</span>
                            </td>
                            <td class="py-4 px-4">
                                @if($dist->asal_barang === 'pengajuan')
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-primary/10 text-primary font-bold text-xs">
                                            Pengadaan
                                        </span>
                                        @if($dist->pengajuanItem?->pengajuan?->nomor_pengajuan)
                                            <div class="text-xs text-muted-foreground font-mono pl-0.5">
                                                {{ $dist->pengajuanItem->pengajuan->nomor_pengajuan }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-800/60 dark:text-slate-400 font-semibold text-xs">
                                        Stok Lama
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-xl bg-accent text-accent-foreground font-bold text-sm">
                                    {{ $dist->lab_tujuan }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-mono font-bold text-foreground text-sm">
                                @if(!empty($dist->kode_pc_tujuan))
                                    {{ $dist->kode_pc_tujuan }}
                                @else
                                    <span class="text-xs text-muted-foreground/70 font-sans italic font-normal">Umum / Lab</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-medium text-foreground">
                                {{ $dist->diterima_oleh ?? '—' }}
                            </td>

                            {{-- Kolom Konfirmasi Terima --}}
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($dist->tanggal_terima_lab)
                                    <div class="inline-flex flex-col gap-0.5">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold text-xs">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Diterima
                                        </span>
                                        <span class="text-[10px] text-muted-foreground pl-0.5">
                                            {{ $dist->tanggal_terima_lab->format('d M Y') }}
                                        </span>
                                    </div>
                                @else
                                    <button type="button"
                                        onclick="openKonfirmasiModal({{ $dist->id }}, '{{ addslashes($dist->stokGudang?->barangMaster?->nama_barang ?? 'Barang') }}', 'Lab {{ $dist->lab_tujuan }}')"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-dashed border-amber-400 dark:border-amber-600 bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 font-semibold text-xs hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Belum diterima
                                    </button>
                                @endif
                            </td>

                            {{-- Kolom Aksi --}}
                            <td class="py-4 px-4">
                                @if(!$dist->tanggal_terima_lab)
                                    <form method="POST" action="{{ route('distribusi.destroy', $dist) }}"
                                        onsubmit="return confirm('Yakin hapus data distribusi ini? Stok akan dikembalikan ke gudang.')"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 rounded-lg text-muted-foreground hover:text-destructive hover:bg-destructive/10 transition-colors"
                                            title="Hapus">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted-foreground/40 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-muted-foreground">
                                    <svg class="w-12 h-12 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-foreground">Belum ada log distribusi</p>
                                        <p class="text-sm mt-0.5">Catat distribusi barang ke laboratorium untuk dokumentasi akreditasi.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($distribusis->hasPages())
            <div class="px-6 py-4 border-t border-border">
                {{ $distribusis->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Modal Konfirmasi Terima --}}
<div id="modal-konfirmasi" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeKonfirmasiModal()"></div>

    {{-- Modal Card --}}
    <div class="relative bg-card border border-border rounded-3xl shadow-2xl max-w-sm w-full p-6 sm:p-7 z-10">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center flex-shrink-0">
                <svg class="w-4.5 h-4.5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-foreground">Konfirmasi Terima Barang</h3>
                <p id="modal-subtitle" class="text-xs text-muted-foreground mt-0.5">—</p>
            </div>
        </div>

        <form id="form-konfirmasi" method="POST" action="">
            @csrf
            @method('PATCH')

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-foreground mb-1.5">Tanggal Diterima</label>
                    <input type="date" name="tanggal_terima_lab"
                        id="input-tanggal-terima"
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm font-medium focus:ring-2 focus:ring-emerald-400/30 focus:border-emerald-500 transition-colors">
                    <p class="text-[11px] text-muted-foreground mt-1">Kosongkan untuk menggunakan tanggal hari ini secara otomatis.</p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="button" onclick="closeKonfirmasiModal()"
                        class="flex-1 py-2.5 px-4 rounded-2xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-2.5 px-4 rounded-2xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors shadow-md shadow-emerald-600/20">
                        ✓ Konfirmasi Diterima
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openKonfirmasiModal(distribusiId, namaBarang, labTujuan) {
        const modal = document.getElementById('modal-konfirmasi');
        const form  = document.getElementById('form-konfirmasi');
        const sub   = document.getElementById('modal-subtitle');
        const dateInput = document.getElementById('input-tanggal-terima');

        form.action = `/distribusi/${distribusiId}/konfirmasi-terima`;
        sub.textContent = `${namaBarang} → ${labTujuan}`;

        // Set default ke hari ini
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeKonfirmasiModal() {
        const modal = document.getElementById('modal-konfirmasi');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeKonfirmasiModal();
    });
</script>
@endpush
@endsection
