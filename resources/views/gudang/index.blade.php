@extends('layouts.app')

@section('title', 'Gudang & Inventaris Stok')
@section('header_title', 'Pengelolaan Stok Gudang & Inventaris')

@section('content')
<div class="flex flex-col gap-6 max-w-7xl mx-auto pb-10">

    {{-- Top Action & Filter Bar --}}
    <div class="bg-card border border-border p-6 sm:p-7 rounded-3xl shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
            <div>
                <h3 class="text-base font-bold text-foreground">Inventaris Stok Gudang Laboratorium</h3>
                <p class="text-sm text-muted-foreground mt-0.5">Pencatatan stok terpusat untuk suku cadang, monitor, periferal, dan komponen 14 lab.</p>
            </div>

            <div class="flex items-center gap-2.5 flex-shrink-0">
                <a href="{{ route('ekspor.stok.excel', request()->query()) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-sm font-medium transition-colors"
                    title="Unduh format Excel (.xlsx)">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Ekspor Excel</span>
                </a>

                <a href="{{ route('ekspor.stok.pdf', request()->query()) }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-sm font-medium transition-colors"
                    title="Cetak atau simpan PDF">
                    <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span>Ekspor PDF</span>
                </a>

                <button type="button" onclick="document.getElementById('modal-tambah-barang').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold shadow-md shadow-primary/20 hover:bg-primary/90 transition-transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>+ Tambah Barang</span>
                </button>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" action="{{ route('gudang.index') }}" class="flex flex-wrap items-end gap-3.5 pt-5 border-t border-border">
            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Cari Barang</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama barang di gudang..."
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground placeholder-muted-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
            </div>

            <div class="w-56">
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Kondisi Stok</label>
                <select name="status_stok" class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    <option value="">Semua Kondisi</option>
                    <option value="kritis" {{ request('status_stok') === 'kritis' ? 'selected' : '' }}>🔴 Kritis (Di Bawah Minimum)</option>
                    <option value="aman" {{ request('status_stok') === 'aman' ? 'selected' : '' }}>🟢 Aman</option>
                </select>
            </div>

            <div class="w-48">
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Kategori</label>
                <select name="kategori" class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                        <option value="{{ $kat }}" {{ request('kategori') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="py-2.5 px-6 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors shadow-sm">
                Filter
            </button>

            <a href="{{ route('gudang.index') }}"
                class="py-2.5 px-5 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-sm font-semibold transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset
            </a>
        </form>
    </div>

    {{-- Kritis Alert --}}
    @php $kritisItems = $stoks->filter(fn($s) => $s->isKritis()); @endphp
    @if($kritisItems->isNotEmpty() && !request('status_stok'))
        <div class="flex items-center gap-3.5 p-5 rounded-3xl bg-red-500/10 border border-red-500/25 shadow-xs">
            <span class="w-10 h-10 rounded-2xl bg-red-500 text-white flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </span>
            <div>
                <h4 class="text-sm font-bold text-red-600 dark:text-red-400">Peringatan Stok Gudang Kritis</h4>
                <p class="text-xs text-foreground/80 mt-0.5">
                    Terdapat <span class="font-bold text-red-600 dark:text-red-400">{{ $kritisItems->count() }} barang</span> dengan sisa stok fisik di bawah batas minimum yang disarankan.
                </p>
            </div>
        </div>
    @endif

    {{-- Stock Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($stoks as $stok)
            @php
                $isKritis  = $stok->isKritis();
                $min       = $stok->barangMaster->stok_minimum ?? 0;
                $current   = $stok->stok_saat_ini;
                $percentage = ($min > 0) ? min(100, ($current / ($min * 2)) * 100) : 100;
            @endphp
            <div class="bg-card border {{ $isKritis ? 'border-red-500/30 dark:border-red-500/30 shadow-red-500/5' : 'border-border' }} rounded-3xl shadow-xs hover:shadow-md hover:border-primary/40 transition-all flex flex-col overflow-hidden group">

                {{-- Card Header: Kategori + Status --}}
                <div class="flex items-center justify-between px-5 pt-4 pb-2">
                    <span class="px-2 py-0.5 rounded-md bg-muted text-[10px] font-semibold text-muted-foreground uppercase tracking-wider">
                        {{ $stok->barangMaster->kategori ?? 'Umum' }}
                    </span>

                    @if($isKritis)
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400 font-semibold text-[10px]">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span>
                            </span>
                            Stok Kritis
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 font-semibold text-[10px]">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Aman
                        </span>
                    @endif
                </div>

                {{-- Nama Barang, Spesifikasi & Catatan --}}
                <div class="px-5 pb-3">
                    <h4 class="font-bold text-base text-foreground line-clamp-1 leading-snug group-hover:text-primary transition-colors">{{ $stok->barangMaster->nama_barang }}</h4>
                    @if(!empty($stok->barangMaster->spesifikasi))
                        <p class="text-xs font-semibold text-primary mt-0.5 line-clamp-1 flex items-center gap-1.5" title="{{ $stok->barangMaster->spesifikasi }}">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-primary/70 flex-shrink-0"></span>
                            <span class="truncate">{{ $stok->barangMaster->spesifikasi }}</span>
                        </p>
                    @endif
                    @if($stok->catatan)
                        <p class="text-[11px] text-muted-foreground line-clamp-1 mt-1 flex items-center gap-1" title="{{ $stok->catatan }}">
                            <svg class="w-3 h-3 text-muted-foreground/70 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="italic truncate">{{ $stok->catatan }}</span>
                        </p>
                    @endif
                </div>

                {{-- Stok Count --}}
                <div class="px-5 pb-4 mt-auto">
                    <div class="flex items-baseline justify-between mb-2">
                        <div>
                            <span class="text-3xl font-black {{ $isKritis ? 'text-red-600 dark:text-red-400' : 'text-foreground' }}">
                                {{ $current }}
                            </span>
                            <span class="text-sm font-medium text-muted-foreground ml-1">{{ $stok->barangMaster->satuan ?? 'unit' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-muted-foreground">Min: <strong>{{ $min }}</strong></span>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="w-full h-1.5 bg-muted rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all {{ $isKritis ? 'bg-red-500' : 'bg-primary' }}" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>

                {{-- Footer Actions: Polished Button Pair --}}
                <div class="px-4 py-3 border-t border-border bg-muted/15 grid grid-cols-2 gap-2">
                    <a href="{{ route('gudang.show', $stok) }}"
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-xs font-semibold shadow-2xs hover:border-primary/40 transition-all active:scale-95">
                        <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>Detail</span>
                    </a>

                    <a href="{{ route('distribusi.create', ['stok_gudang_id' => $stok->id]) }}"
                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-primary text-white text-xs font-bold shadow-sm shadow-primary/25 hover:bg-primary/90 transition-all active:scale-95"
                        title="Distribusi Barang Ini">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <span>Distribusi</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-card border border-border rounded-3xl shadow-xs">
                <div class="flex flex-col items-center gap-3 text-muted-foreground">
                    <svg class="w-12 h-12 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <div>
                        <p class="font-semibold text-foreground">Belum ada barang di inventaris</p>
                        <p class="text-sm mt-0.5">Tambahkan barang baru untuk mulai melacak stok gudang.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($stoks->hasPages())
        <div class="p-5 bg-card border border-border rounded-3xl shadow-xs">
            {{ $stoks->links() }}
        </div>
    @endif

</div>

{{-- Modal Tambah Barang Master --}}
<div id="modal-tambah-barang" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-card border border-border rounded-3xl max-w-md w-full shadow-2xl overflow-hidden animate-fade-in">

        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-border">
            <div>
                <h3 class="text-base font-bold text-foreground">Daftarkan Barang Baru</h3>
                <p class="text-sm text-muted-foreground mt-0.5">Tambahkan barang ke inventaris master gudang.</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-tambah-barang').classList.add('hidden')"
                class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <form method="POST" action="{{ route('gudang.store') }}" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-foreground mb-1.5 flex items-center justify-between">
                    <span>Nama Barang <span class="text-destructive">*</span></span>
                    <span class="text-[11px] font-normal text-muted-foreground">Pilih saran atau ketik baru</span>
                </label>
                <input type="text" name="nama_barang" id="input-tambah-nama" required list="datalist-nama-barang" autocomplete="off"
                    placeholder="Contoh: Mouse, Keyboard, SSD NVMe, RAM..."
                    oninput="updateTambahPreview()"
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                <datalist id="datalist-nama-barang">
                    @foreach($masterNamaList as $nama)
                        <option value="{{ $nama }}">{{ $nama }}</option>
                    @endforeach
                </datalist>
            </div>

            <div>
                <label class="block text-sm font-semibold text-foreground mb-1.5 flex items-center justify-between">
                    <span>Spesifikasi / Merk (Opsional)</span>
                    <span class="text-[11px] font-normal text-muted-foreground">Model, brand, kapasitas</span>
                </label>
                <input type="text" name="spesifikasi" id="input-tambah-spek" list="datalist-spesifikasi-barang" autocomplete="off"
                    placeholder="Contoh: Logitech M90, 512GB NVMe, DDR4 8GB..."
                    oninput="updateTambahPreview()"
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                <datalist id="datalist-spesifikasi-barang">
                    @foreach($masterSpesifikasiList as $spek)
                        <option value="{{ $spek }}">{{ $spek }}</option>
                    @endforeach
                </datalist>
            </div>

            <div class="p-2.5 rounded-xl bg-muted/40 border border-border/80 text-[11px] flex items-center justify-between gap-2">
                <span class="text-muted-foreground">📦 Format Lengkap Master:</span>
                <strong class="text-primary font-mono text-xs" id="preview-tambah-lengkap">-</strong>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-1.5">Kategori <span class="text-destructive">*</span></label>
                    <select name="kategori" required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Komponen PC">Komponen PC</option>
                        <option value="Peripheral">Peripheral</option>
                        <option value="Jaringan">Jaringan</option>
                        <option value="Sarana & Prasarana">Sarana & Prasarana</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-foreground mb-1.5">Satuan <span class="text-destructive">*</span></label>
                    <select name="satuan" required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        <option value="unit" selected>unit</option>
                        <option value="buah">buah</option>
                        <option value="keping">keping</option>
                        <option value="set">set</option>
                        <option value="pasang">pasang</option>
                        <option value="lembar">lembar</option>
                        <option value="meter">meter</option>
                        <option value="rol">rol</option>
                        <option value="pack">pack</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-1.5">Stok Minimum Alert</label>
                    <input type="number" name="stok_minimum" value="2" min="0"
                        class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-foreground mb-1.5">Stok Awal Fisik <span class="text-destructive">*</span></label>
                    <input type="number" name="stok_awal" value="0" min="0" required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-bold focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-foreground mb-1.5">Keterangan / Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" rows="2" placeholder="Contoh: Kondisi segel baru, rak penyimpanan B2, dsb..."
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"></textarea>
            </div>

            {{-- Modal Footer --}}
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-border">
                <button type="button" onclick="document.getElementById('modal-tambah-barang').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl border border-border text-foreground text-sm font-semibold hover:bg-muted transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/25 hover:bg-primary/90 transition-colors">
                    Simpan Barang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateTambahPreview() {
    const nama = document.getElementById('input-tambah-nama')?.value.trim() || '';
    const spek = document.getElementById('input-tambah-spek')?.value.trim() || '';
    const preview = document.getElementById('preview-tambah-lengkap');
    if (!preview) return;

    if (!nama) {
        preview.textContent = '-';
    } else if (spek) {
        preview.textContent = `${nama} - ${spek}`;
    } else {
        preview.textContent = nama;
    }
}
</script>
@endsection
