@extends('layouts.app')

@section('title', 'Detail Pengajuan ' . $pengajuan->nomor_pengajuan)
@section('header_title', 'Detail Pengajuan ' . $pengajuan->nomor_pengajuan)

@section('content')
<div class="flex flex-col gap-6 max-w-7xl mx-auto pb-10">

    {{-- Top Navigation & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('pengajuan.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-muted-foreground hover:text-primary transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar Pengajuan</span>
        </a>

        <div class="flex items-center gap-2 flex-wrap">
            @if($pengajuan->status === 'disetujui')
                <a href="{{ route('penerimaan.show', $pengajuan) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl bg-teal-500 text-white text-xs font-bold shadow-lg shadow-teal-500/20 hover:bg-teal-600 transition-transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Verifikasi Penerimaan Barang</span>
                </a>
            @endif

            {{-- Ekspor PDF --}}
            <a href="{{ route('ekspor.pengajuan.detail.pdf', $pengajuan) }}" target="_blank"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors shadow-2xs">
                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span>Cetak / PDF</span>
            </a>

            {{-- Ekspor Excel --}}
            <a href="{{ route('ekspor.pengajuan.detail.excel', $pengajuan) }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors shadow-2xs">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Ekspor Excel</span>
            </a>

            {{-- Edit Header Info Button --}}
            <button type="button" onclick="openModal('modal-edit-pengajuan')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl border border-border text-muted-foreground hover:text-foreground hover:bg-muted text-xs font-semibold transition-colors shadow-2xs">
                <svg class="w-3.5 h-3.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Edit Info Pengajuan</span>
            </button>

            {{-- Delete Pengajuan Button --}}
            <form method="POST" action="{{ route('pengajuan.destroy', $pengajuan) }}" onsubmit="return confirm('Hapus seluruh pengajuan ini beserta seluruh itemnya? Tindakan ini tidak dapat dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 rounded-2xl bg-destructive/10 text-destructive text-xs font-semibold hover:bg-destructive hover:text-white transition-colors" title="Hapus Pengajuan">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Header Info & Decision Status Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Main Info & Item List -->
        <div class="lg:col-span-2 bg-card border border-border rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
            <div class="flex items-start justify-between gap-4 pb-6 border-b border-border">
                <div>
                    <span class="text-xs font-bold text-primary font-mono tracking-wider uppercase block">Pengajuan Pengadaan</span>
                    <h2 class="text-2xl font-black text-foreground mt-1">{{ $pengajuan->nomor_pengajuan }}</h2>
                    <p class="text-xs text-muted-foreground mt-1">
                        Tujuan: <span class="font-semibold text-foreground">{{ $pengajuan->divisi_tujuan }}</span>
                        • Tanggal Ajuan: <span class="font-semibold text-foreground">{{ $pengajuan->tanggal_ajuan?->format('d F Y') }}</span>
                    </p>
                </div>

                <x-status-badge :status="$pengajuan->status" />
            </div>

            <!-- List of Items in this submission -->
            <div class="space-y-4">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                    <div>
                        <h3 class="text-sm font-bold text-foreground">Daftar Barang yang Diajukan ({{ $pengajuan->items->count() }} Item)</h3>
                        <p class="text-xs text-muted-foreground">Kelola, edit rincian spesifikasi & kuantitas, atau tambahkan barang baru.</p>
                    </div>

                    <button type="button" onclick="toggleAddForm()" id="btn-toggle-add"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary/10 hover:bg-primary text-primary hover:text-white font-bold text-xs transition-colors shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>+ Tambah Barang</span>
                    </button>
                </div>

                {{-- Add New Item Form Container (Hidden by default) --}}
                <div id="add-item-container" class="hidden p-5 rounded-3xl border border-primary/40 bg-primary/[0.03] space-y-4 animate-fade-in">
                    <div class="flex items-center justify-between pb-3 border-b border-border">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            <h4 class="text-xs font-bold text-foreground uppercase tracking-wider">Tambah Item Barang ke Pengajuan Ini</h4>
                        </div>
                        <button type="button" onclick="toggleAddForm()" class="text-xs text-muted-foreground hover:text-foreground font-semibold">
                            ✕ Tutup
                        </button>
                    </div>

                    {{-- Quick Import from Pending Laporan Items --}}
                    @if(isset($availableItems) && $availableItems->isNotEmpty())
                        <div class="p-3.5 rounded-2xl bg-input/40 border border-border/80 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-[11px] font-bold text-primary uppercase tracking-wide flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    Item dari Laporan Lab (Menunggu Pengadaan)
                                </span>
                                <span class="text-[10px] text-muted-foreground">{{ $availableItems->count() }} item tersedia</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-40 overflow-y-auto pr-1">
                                @foreach($availableItems as $avail)
                                    <div class="p-2.5 rounded-xl bg-card border border-border flex items-center justify-between gap-2">
                                        <div class="min-w-0">
                                            <div class="font-bold text-xs text-foreground truncate">{{ $avail->nama_barang }}</div>
                                            <div class="text-[10px] text-muted-foreground truncate">Lab {{ $avail->laporan->lab_nama ?? '-' }} • {{ $avail->jumlah }} {{ $avail->satuan }}</div>
                                        </div>
                                        <button type="button" onclick="populateNewItem('{{ $avail->id }}', '{{ addslashes($avail->nama_barang) }}', {{ $avail->jumlah }}, '{{ addslashes($avail->satuan) }}', '{{ addslashes($avail->deskripsi ?? '') }}')"
                                            class="px-2 py-1 rounded-lg bg-primary/15 text-primary hover:bg-primary hover:text-white font-bold text-[10px] transition-colors flex-shrink-0">
                                            Pilih
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pengajuan.item.store', $pengajuan) }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="laporan_item_id" id="new-item-laporan-id" value="">

                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                            {{-- Nama Barang dengan Searchable Dropdown --}}
                            <div class="sm:col-span-2 relative master-dropdown-wrapper" data-index="new">
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-[11px] font-semibold text-muted-foreground uppercase">
                                        Nama Barang <span class="text-destructive">*</span>
                                    </label>
                                    <span id="master-status-new" class="text-[10px] font-semibold px-2 py-0.5 rounded-md hidden"></span>
                                </div>

                                <div class="relative">
                                    <input type="text"
                                        name="nama_barang"
                                        id="item-nama-new"
                                        required
                                        autocomplete="off"
                                        placeholder="Pilih master atau ketik nama barang..."
                                        class="w-full pl-3 pr-8 py-2 rounded-xl bg-input border border-border text-xs text-foreground font-medium focus:ring-1 focus:ring-primary input-nama-barang"
                                        onfocus="openDropdown('new')"
                                        oninput="handleSearchInput('new', this.value)">

                                    <button type="button"
                                        onclick="toggleDropdown('new')"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground p-1 toggle-dropdown-btn"
                                        tabindex="-1"
                                        title="Tampilkan daftar master barang">
                                        <svg id="chevron-new" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Dropdown Menu -->
                                <div id="dropdown-menu-new"
                                    class="master-dropdown-menu absolute left-0 right-0 top-full mt-1.5 z-40 bg-card border border-border rounded-2xl shadow-xl overflow-hidden hidden">
                                    <div class="px-3 py-2 border-b border-border bg-muted/40 flex items-center justify-between text-[11px]">
                                        <span class="font-bold text-foreground flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                            Master Barang (Stok Gudang)
                                        </span>
                                        <span class="text-[10px] text-muted-foreground">Ketik untuk memfilter</span>
                                    </div>

                                    <div id="dropdown-list-new" class="max-h-52 overflow-y-auto divide-y divide-border/60">
                                        <!-- Rendered dynamically -->
                                    </div>

                                    <div class="p-2 border-t border-border bg-muted/20">
                                        <button type="button"
                                            onclick="selectCustomItem('new')"
                                            class="w-full text-left px-3 py-1.5 rounded-xl text-xs font-semibold text-primary hover:bg-primary/10 transition-colors flex items-center justify-between">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                <span>Input sebagai barang non-master (baru)</span>
                                            </span>
                                            <span class="text-[10px] text-muted-foreground font-normal">Bebas</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-muted-foreground uppercase mb-1">Jumlah Diajukan *</label>
                                <input type="number" name="jumlah_diajukan" id="item-jumlah-new" value="1" min="1" required
                                    class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-muted-foreground uppercase mb-1">Satuan *</label>
                                <select name="satuan" id="item-satuan-new" required
                                    class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
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

                        <div>
                            <label class="block text-[11px] font-semibold text-muted-foreground uppercase mb-1">Spesifikasi Detail Barang</label>
                            <input type="text" name="spesifikasi" id="item-spesifikasi-new" placeholder="Contoh: Speed PC-2666 / 3200MHz, Garansi resmi 1 tahun..."
                                class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" onclick="toggleAddForm()" class="px-4 py-2 rounded-xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary/90 transition-colors shadow-md shadow-primary/20">
                                Tambahkan Barang ke Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
                
                {{-- Items Cards List --}}
                <div class="space-y-3">
                    @foreach($pengajuan->items as $itemIndex => $item)
                        <div class="p-4 rounded-2xl border border-border bg-input/30 space-y-3 transition-colors hover:border-border/80 group">
                            
                            {{-- Item Main Row --}}
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[11px] font-black flex-shrink-0">
                                            {{ $itemIndex + 1 }}
                                        </span>
                                        <span class="font-bold text-sm text-foreground break-words">{{ $item->nama_barang }}</span>
                                    </div>

                                    @if($item->spesifikasi)
                                        <p class="text-xs text-muted-foreground mt-1 pl-7">
                                            <span class="font-semibold text-foreground">Spesifikasi:</span> {{ $item->spesifikasi }}
                                        </p>
                                    @endif

                                    @if($item->laporanItem)
                                        <div class="text-[11px] text-muted-foreground flex items-center gap-1.5 mt-1 pl-7">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                            <span>Terkait Laporan: <strong class="text-foreground">Lab {{ $item->laporanItem->laporan->lab_nama ?? '-' }}</strong> ({{ $item->laporanItem->deskripsi ?? 'Penggantian unit' }})</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Badges & Actions --}}
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="px-2.5 py-1 rounded-xl bg-primary/10 text-primary font-bold text-xs">
                                        Diajukan: {{ $item->jumlah_diajukan }} {{ $item->satuan }}
                                    </span>

                                    {{-- Edit Item Button --}}
                                    <button type="button"
                                        onclick="toggleEditItemForm({{ $item->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-border bg-card text-muted-foreground hover:text-amber-500 hover:border-amber-500/30 hover:bg-amber-500/10 transition-colors"
                                        title="Edit Item Ini">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    {{-- Delete Item Button (if > 1 items) --}}
                                    @if($pengajuan->items->count() > 1)
                                        <form method="POST" action="{{ route('pengajuan.item.destroy', [$pengajuan, $item]) }}" onsubmit="return confirm('Hapus barang {{ addslashes($item->nama_barang) }} dari daftar pengajuan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-xl border border-destructive/20 bg-card text-destructive/80 hover:text-destructive hover:bg-destructive/10 hover:border-destructive transition-colors"
                                                title="Hapus Item">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Verification Status if already verified -->
                            @if($item->status_kesesuaian)
                                <div class="mt-2 pt-2 border-t border-border/60 flex flex-col gap-1 text-xs">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-muted-foreground font-medium">Hasil Verifikasi:</span>
                                        @if($item->status_kesesuaian === 'sesuai')
                                            <span class="px-2 py-0.5 rounded bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 font-bold text-[11px]">
                                                ✅ Sesuai Penuh ({{ $item->jumlah_diterima }} {{ $item->satuan }} Diterima)
                                            </span>
                                        @elseif($item->status_kesesuaian === 'sesuai_beda_spesifikasi')
                                            <span class="px-2 py-0.5 rounded bg-amber-500/15 text-amber-600 dark:text-amber-400 font-bold text-[11px]">
                                                🔄 Sesuai Beda Spesifikasi/Merk ({{ $item->jumlah_diterima }} {{ $item->satuan }} Diterima)
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-rose-500/15 text-rose-600 dark:text-rose-400 font-bold text-[11px]">
                                                ❌ Tidak Sesuai ({{ $item->jumlah_diterima }} {{ $item->satuan }} Diterima)
                                            </span>
                                        @endif
                                    </div>
                                    @if($item->spesifikasi_diterima)
                                        <p class="text-xs text-foreground/80 font-medium mt-0.5">
                                            Fisik Diterima: <span class="font-semibold text-amber-600 dark:text-amber-400">{{ $item->spesifikasi_diterima }}</span>
                                        </p>
                                    @endif
                                    @if($item->catatan_selisih)
                                        <p class="text-xs text-muted-foreground font-medium">
                                            Catatan / Alasan: {{ $item->catatan_selisih }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            {{-- Inline Edit Item Form (Hidden by default) --}}
                            <div id="edit-item-form-{{ $item->id }}" class="hidden pt-3 mt-2 border-t border-dashed border-border/80 bg-card/80 p-4 rounded-2xl animate-fade-in space-y-3">
                                <div class="flex items-center justify-between pb-2 border-b border-border/60">
                                    <span class="text-xs font-bold text-amber-500 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit Rincian Barang #{{ $itemIndex + 1 }}
                                    </span>
                                    <button type="button" onclick="toggleEditItemForm({{ $item->id }})" class="text-xs text-muted-foreground hover:text-foreground font-semibold">
                                        ✕ Batal
                                    </button>
                                </div>

                                <form method="POST" action="{{ route('pengajuan.item.update', [$pengajuan, $item]) }}" class="space-y-3">
                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                        <div class="sm:col-span-2">
                                            <label class="block text-[11px] font-semibold text-muted-foreground uppercase mb-1">
                                                Nama Barang <span class="text-destructive">*</span>
                                            </label>
                                            <input type="text" name="nama_barang" value="{{ old('nama_barang', $item->nama_barang) }}" required
                                                class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground font-medium focus:ring-1 focus:ring-primary">
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-semibold text-muted-foreground uppercase mb-1">
                                                Jumlah Diajukan <span class="text-destructive">*</span>
                                            </label>
                                            <input type="number" name="jumlah_diajukan" value="{{ old('jumlah_diajukan', $item->jumlah_diajukan) }}" min="1" required
                                                class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-semibold text-muted-foreground uppercase mb-1">
                                                Satuan <span class="text-destructive">*</span>
                                            </label>
                                            <select name="satuan" required
                                                class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                                @php
                                                    $satuanList = ['unit', 'buah', 'keping', 'set', 'pasang', 'lembar', 'meter', 'rol', 'pack'];
                                                    if (!in_array(strtolower($item->satuan), $satuanList) && $item->satuan) {
                                                        $satuanList[] = $item->satuan;
                                                    }
                                                @endphp
                                                @foreach($satuanList as $sat)
                                                    <option value="{{ $sat }}" {{ strtolower(old('satuan', $item->satuan)) === strtolower($sat) ? 'selected' : '' }}>{{ $sat }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-semibold text-muted-foreground uppercase mb-1">
                                            Spesifikasi Detail Barang
                                        </label>
                                        <input type="text" name="spesifikasi" value="{{ old('spesifikasi', $item->spesifikasi) }}" placeholder="Spesifikasi teknis / garansi..."
                                            class="w-full px-3 py-2 rounded-xl bg-input border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                                    </div>

                                    <div class="flex items-center justify-end gap-2 pt-1">
                                        <button type="button" onclick="toggleEditItemForm({{ $item->id }})" class="px-3.5 py-1.5 rounded-xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors">
                                            Batal
                                        </button>
                                        <button type="submit" class="px-4 py-1.5 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary/90 transition-colors shadow-sm shadow-primary/20">
                                            Simpan Perubahan Item
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Col: Update Keputusan Divisi Form -->
        <div class="bg-card border border-border rounded-3xl p-6 shadow-sm space-y-4 h-fit">
            <h3 class="text-sm font-bold text-foreground border-b border-border pb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-primary"></span>
                Pencatatan Keputusan Divisi
            </h3>
            <p class="text-xs text-muted-foreground leading-relaxed">
                Diisi manual oleh admin setelah menerima surat/jawaban persetujuan dari divisi terkait di luar sistem.
            </p>

            <form method="POST" action="{{ route('pengajuan.keputusan', $pengajuan) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Status Keputusan</label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                        <option value="menunggu" {{ $pengajuan->status === 'menunggu' ? 'selected' : '' }}>Menunggu Keputusan</option>
                        <option value="disetujui" {{ $pengajuan->status === 'disetujui' ? 'selected' : '' }}>Disetujui (Cair)</option>
                        <option value="ditolak" {{ $pengajuan->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Tanggal Keputusan / Cair</label>
                    <input type="date" name="tanggal_keputusan" value="{{ old('tanggal_keputusan', $pengajuan->tanggal_keputusan?->format('Y-m-d') ?? date('Y-m-d')) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Catatan Keputusan</label>
                    <textarea name="catatan_keputusan" rows="3" placeholder="Contoh: Disetujui melalui DIPA 2026, barang akan dikirim tanggal..."
                        class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">{{ old('catatan_keputusan', $pengajuan->catatan_keputusan) }}</textarea>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-primary text-white text-xs font-bold hover:bg-primary/90 transition-colors shadow-md shadow-primary/20">
                    Simpan Keputusan Divisi
                </button>
            </form>
        </div>

    </div>

</div>

{{-- Modal Edit Informasi Pengajuan Header --}}
<div id="modal-edit-pengajuan" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden animate-fade-in">
    <div class="bg-card border border-border rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-6">
        <div class="flex items-center justify-between pb-3 border-b border-border">
            <div>
                <h3 class="text-base font-bold text-foreground flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-black">✎</span>
                    Edit Informasi Pengajuan
                </h3>
                <p class="text-xs text-muted-foreground mt-0.5">Perbarui nomor pengajuan, divisi tujuan, atau tanggal ajuan.</p>
            </div>
            <button type="button" onclick="closeModal('modal-edit-pengajuan')" class="p-1 rounded-xl text-muted-foreground hover:text-foreground hover:bg-muted transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('pengajuan.update', $pengajuan) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                    Nomor Pengajuan <span class="text-destructive">*</span>
                </label>
                <input type="text" name="nomor_pengajuan" value="{{ old('nomor_pengajuan', $pengajuan->nomor_pengajuan) }}" required
                    class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-primary font-mono font-bold text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                    Divisi Tujuan <span class="text-destructive">*</span>
                </label>
                <input type="text" name="divisi_tujuan" value="{{ old('divisi_tujuan', $pengajuan->divisi_tujuan) }}" required placeholder="Contoh: BIUM (Biro Umum)"
                    class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm font-medium focus:ring-2 focus:ring-primary/40 focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                    Tanggal Pengajuan <span class="text-destructive">*</span>
                </label>
                <input type="date" name="tanggal_ajuan" value="{{ old('tanggal_ajuan', $pengajuan->tanggal_ajuan?->format('Y-m-d') ?? date('Y-m-d')) }}" required
                    class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm font-medium focus:ring-2 focus:ring-primary/40 focus:border-primary">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                <button type="button" onclick="closeModal('modal-edit-pengajuan')" class="px-4 py-2.5 rounded-2xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-2xl bg-primary text-white text-xs font-bold shadow-lg shadow-primary/25 hover:bg-primary/90 transition-transform active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const initialTopBarangs = @json($topBarangs ?? []);
    const searchEndpoint = "{{ route('gudang.search-barang') }}";
    const searchCache = {};
    let searchDebounceTimeout = null;

    document.addEventListener('DOMContentLoaded', function() {
        renderDropdownList('new', initialTopBarangs);
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.master-dropdown-wrapper')) {
            closeAllDropdowns();
        }
    });

    function toggleEditItemForm(itemId) {
        const form = document.getElementById(`edit-item-form-${itemId}`);
        if (form) {
            form.classList.toggle('hidden');
        }
    }

    function toggleAddForm() {
        const container = document.getElementById('add-item-container');
        const btn = document.getElementById('btn-toggle-add');
        if (container) {
            const isHidden = container.classList.contains('hidden');
            if (isHidden) {
                container.classList.remove('hidden');
                if (btn) btn.innerHTML = `✕ Batal Tambah`;
                document.getElementById('item-nama-new')?.focus();
            } else {
                container.classList.add('hidden');
                if (btn) btn.innerHTML = `
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>+ Tambah Barang</span>
                `;
            }
        }
    }

    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function openDropdown(idx) {
        closeAllDropdowns(idx);
        const menu = document.getElementById(`dropdown-menu-${idx}`);
        const chevron = document.getElementById(`chevron-${idx}`);
        if (menu) menu.classList.remove('hidden');
        if (chevron) chevron.classList.add('rotate-180');

        const inputVal = (document.getElementById(`item-nama-${idx}`)?.value || '').trim();
        if (inputVal === '') {
            renderDropdownList(idx, initialTopBarangs);
        } else {
            handleSearchInput(idx, inputVal);
        }
    }

    function toggleDropdown(idx) {
        const menu = document.getElementById(`dropdown-menu-${idx}`);
        if (menu && !menu.classList.contains('hidden')) {
            closeDropdown(idx);
        } else {
            openDropdown(idx);
            document.getElementById(`item-nama-${idx}`)?.focus();
        }
    }

    function closeDropdown(idx) {
        const menu = document.getElementById(`dropdown-menu-${idx}`);
        const chevron = document.getElementById(`chevron-${idx}`);
        if (menu) menu.classList.add('hidden');
        if (chevron) chevron.classList.remove('rotate-180');
    }

    function closeAllDropdowns(exceptIdx = null) {
        document.querySelectorAll('.master-dropdown-menu').forEach(menu => {
            const menuIdx = menu.id.replace('dropdown-menu-', '');
            if (exceptIdx === null || menuIdx != exceptIdx) {
                menu.classList.add('hidden');
            }
        });
        document.querySelectorAll('[id^="chevron-"]').forEach(chevron => {
            const chevronIdx = chevron.id.replace('chevron-', '');
            if (exceptIdx === null || chevronIdx != exceptIdx) {
                chevron.classList.remove('rotate-180');
            }
        });
    }

    function handleSearchInput(idx, value) {
        const query = (value || '').trim();
        clearTimeout(searchDebounceTimeout);

        if (!query) {
            renderDropdownList(idx, initialTopBarangs);
            return;
        }

        searchDebounceTimeout = setTimeout(() => {
            fetchMasterBarang(query, (data) => {
                renderDropdownList(idx, data, query);
            });
        }, 200);
    }

    function fetchMasterBarang(query, callback) {
        const q = query.trim();
        if (!q) {
            callback(initialTopBarangs);
            return;
        }

        if (searchCache[q]) {
            callback(searchCache[q]);
            return;
        }

        fetch(`${searchEndpoint}?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                searchCache[q] = data;
                callback(data);
            })
            .catch(err => {
                console.error('Error fetching master barang:', err);
                callback([]);
            });
    }

    function renderDropdownList(idx, items, currentQuery = '') {
        const container = document.getElementById(`dropdown-list-${idx}`);
        if (!container) return;

        if (!items || items.length === 0) {
            container.innerHTML = `
                <div class="px-4 py-3 text-center text-xs text-muted-foreground">
                    <p class="font-semibold text-foreground">Tidak ditemukan di master gudang</p>
                    <p class="text-[11px] mt-0.5">Barang "${escapeHtml(currentQuery)}" dapat diajukan sebagai barang baru.</p>
                </div>
            `;
            return;
        }

        let html = '';
        items.forEach(item => {
            const itemJson = encodeURIComponent(JSON.stringify(item));
            const isHabis = item.stok_saat_ini <= 0;
            html += `
                <button type="button"
                    onclick="selectMasterItem('${idx}', decodeURIComponent('${itemJson}'))"
                    class="w-full text-left px-3.5 py-2.5 hover:bg-muted/70 transition-colors flex items-center justify-between gap-3 group">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-bold text-xs text-foreground group-hover:text-primary transition-colors truncate">
                                ${escapeHtml(item.nama_barang)}
                            </span>
                            ${item.spesifikasi ? `
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-primary/10 text-primary border border-primary/20">
                                    ${escapeHtml(item.spesifikasi)}
                                </span>
                            ` : ''}
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-muted text-muted-foreground uppercase flex-shrink-0">
                                ${escapeHtml(item.kategori || 'Umum')}
                            </span>
                        </div>
                        ${item.catatan ? `
                            <p class="text-[10px] text-muted-foreground truncate mt-0.5 italic flex items-center gap-1">
                                <svg class="w-2.5 h-2.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <span>${escapeHtml(item.catatan)}</span>
                            </p>
                        ` : ''}
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-[11px] font-semibold ${isHabis ? 'text-red-500 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400'}">
                            Stok: ${item.stok_saat_ini} ${escapeHtml(item.satuan || 'unit')}
                        </span>
                    </div>
                </button>
            `;
        });

        container.innerHTML = html;
    }

    function selectMasterItem(idx, rawItem) {
        const item = typeof rawItem === 'string' ? JSON.parse(rawItem) : rawItem;
        const namaInput = document.getElementById(`item-nama-${idx}`);
        const spekInput = document.getElementById(`item-spesifikasi-${idx}`);
        const badge = document.getElementById(`master-status-${idx}`);

        if (namaInput) {
            namaInput.value = item.nama_barang;
        }

        if (item.satuan) {
            selectSatuan(idx, item.satuan);
        }

        // Auto-fill spesifikasi dari master jika tersedia
        if (spekInput && item.spesifikasi) {
            spekInput.value = item.spesifikasi;
        } else if (item.catatan && spekInput && spekInput.value.trim() === '') {
            spekInput.value = item.catatan;
        }

        if (badge) {
            badge.className = 'text-[10px] font-semibold px-2 py-0.5 rounded-md bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 inline-block';
            badge.textContent = `✓ Master (${item.kategori || 'Gudang'} • Stok: ${item.stok_saat_ini})`;
            badge.classList.remove('hidden');
        }

        closeDropdown(idx);
    }

    function selectCustomItem(idx) {
        const badge = document.getElementById(`master-status-${idx}`);
        if (badge) {
            badge.className = 'text-[10px] font-semibold px-2 py-0.5 rounded-md bg-amber-500/15 text-amber-600 dark:text-amber-400 inline-block';
            badge.textContent = 'ℹ Barang Baru (Non-Master)';
            badge.classList.remove('hidden');
        }
        closeDropdown(idx);
        document.getElementById(`item-nama-${idx}`)?.focus();
    }

    function selectSatuan(idx, satuan) {
        const select = document.getElementById(`item-satuan-${idx}`);
        if (!select || !satuan) return;

        const lower = satuan.toLowerCase().trim();
        let matched = false;

        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value.toLowerCase() === lower) {
                select.selectedIndex = i;
                matched = true;
                break;
            }
        }

        if (!matched) {
            const opt = document.createElement('option');
            opt.value = satuan;
            opt.textContent = satuan;
            opt.selected = true;
            select.appendChild(opt);
        }
    }

    function populateNewItem(id, nama, jumlah, satuan, deskripsi) {
        document.getElementById('new-item-laporan-id').value = id;
        document.getElementById('item-nama-new').value = nama;
        document.getElementById('item-jumlah-new').value = jumlah;
        selectSatuan('new', satuan);
        document.getElementById('item-spesifikasi-new').value = deskripsi;

        const badge = document.getElementById('master-status-new');
        if (badge) {
            badge.className = 'text-[10px] font-semibold px-2 py-0.5 rounded-md bg-blue-500/15 text-blue-600 dark:text-blue-400 inline-block';
            badge.textContent = '📋 Dari Laporan Lab';
            badge.classList.remove('hidden');
        }
    }
</script>
@endpush
