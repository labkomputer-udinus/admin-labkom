@extends('layouts.app')

@section('title', 'Distribusi Barang ke Lab')
@section('header_title', 'Form Distribusi Barang ke Laboratorium')

@section('content')
<div class="max-w-3xl mx-auto flex flex-col gap-6 pb-10">

    <a href="{{ route('distribusi.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-muted-foreground hover:text-primary transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Log Distribusi</span>
    </a>

    <form method="POST" action="{{ route('distribusi.store') }}" class="bg-card border border-border rounded-3xl p-6 sm:p-8 space-y-8 shadow-sm" id="distribusi-form">
        @csrf

        @php
            $currentMode = old('jenis_distribusi', $initialMode ?? 'laporan');
        @endphp

        <!-- Pemilih Mode Distribusi (Laporan vs Mandiri) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider">
                    Pilih Mode Distribusi <span class="text-destructive">*</span>
                </label>
                <span class="text-[11px] text-muted-foreground">Sesuaikan dengan keperluan distribusi</span>
            </div>

            <input type="hidden" name="jenis_distribusi" id="input-jenis-distribusi" value="{{ $currentMode }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <!-- Mode A: Berdasarkan Laporan Masuk -->
                <div id="card-mode-laporan"
                    onclick="switchMode('laporan')"
                    class="relative flex items-start gap-3.5 p-4 rounded-2xl border-2 cursor-pointer transition-all {{ $currentMode === 'laporan' ? 'border-primary bg-primary/5 shadow-xs' : 'border-border bg-card hover:border-primary/40' }}">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $currentMode === 'laporan' ? 'bg-primary text-white' : 'bg-muted text-muted-foreground' }} icon-box transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-bold text-sm text-foreground">Berdasarkan Laporan</span>
                            <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center radio-dot {{ $currentMode === 'laporan' ? 'border-primary' : 'border-muted-foreground/40' }}">
                                <span class="w-2 h-2 rounded-full bg-primary dot-inner {{ $currentMode === 'laporan' ? '' : 'hidden' }}"></span>
                            </span>
                        </div>
                        <p class="text-xs text-muted-foreground mt-1 leading-relaxed">
                            Penyelesaian keluhan/kerusakan barang dari laporan laboran.
                        </p>
                    </div>
                </div>

                <!-- Mode B: Distribusi Mandiri / Proaktif -->
                <div id="card-mode-mandiri"
                    onclick="switchMode('mandiri')"
                    class="relative flex items-start gap-3.5 p-4 rounded-2xl border-2 cursor-pointer transition-all {{ $currentMode === 'mandiri' ? 'border-primary bg-primary/5 shadow-xs' : 'border-border bg-card hover:border-primary/40' }}">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $currentMode === 'mandiri' ? 'bg-primary text-white' : 'bg-muted text-muted-foreground' }} icon-box transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-bold text-sm text-foreground">Distribusi Mandiri</span>
                            <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center radio-dot {{ $currentMode === 'mandiri' ? 'border-primary' : 'border-muted-foreground/40' }}">
                                <span class="w-2 h-2 rounded-full bg-primary dot-inner {{ $currentMode === 'mandiri' ? '' : 'hidden' }}"></span>
                            </span>
                        </div>
                        <p class="text-xs text-muted-foreground mt-1 leading-relaxed">
                            Upgrade, pemeliharaan, atau unit tambahan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 1: Item & Sumber Barang -->
        <div class="space-y-4">
            <div class="border-b border-border pb-3">
                <h3 class="text-base font-bold text-foreground flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-black">1</span>
                    <span id="section-1-title">{{ $currentMode === 'mandiri' ? 'Pilih Stok Barang di Gudang' : 'Item Laporan & Sumber Barang di Gudang' }}</span>
                </h3>
                <p class="text-xs text-muted-foreground mt-0.5" id="section-1-subtitle">
                    {{ $currentMode === 'mandiri' ? 'Pilih stok barang yang akan diambil dari gudang dan tentukan jumlahnya.' : 'Pilih laporan lab yang akan diselesaikan dan stok barang yang diambil.' }}
                </p>
            </div>

            <!-- Target Laporan Item (Hanya untuk Mode Laporan) -->
            <div class="relative custom-searchable-select {{ $currentMode === 'mandiri' ? 'hidden' : '' }}" id="wrapper-laporan-item">
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                    Target Item Laporan Bermasalah <span class="text-destructive">*</span>
                </label>

                <!-- Hidden Native Select for Form Submission & Validation -->
                <select name="laporan_item_id" id="select-laporan-item" {{ $currentMode === 'laporan' ? 'required' : '' }} class="sr-only" tabindex="-1">
                    <option value="">-- Pilih Item Laporan --</option>
                    @foreach($pendingItems as $pItem)
                        <option value="{{ $pItem->id }}"
                            data-lab="{{ $pItem->laporan->lab_nama ?? '' }}"
                            data-nama="{{ $pItem->nama_barang }}"
                            data-jumlah="{{ $pItem->jumlah }}"
                            data-sisa="{{ $pItem->sisa_kebutuhan }}"
                            data-terkirim="{{ $pItem->sudah_terdistribusi }}"
                            data-satuan="{{ $pItem->satuan }}"
                            data-deskripsi="{{ $pItem->deskripsi ?? 'Penggantian' }}"
                            {{ (old('laporan_item_id', $selectedItemId) == $pItem->id) ? 'selected' : '' }}>
                            [Lab {{ $pItem->laporan->lab_nama ?? '-' }}] {{ $pItem->nama_barang }} (Sisa: {{ $pItem->sisa_kebutuhan }} dari {{ $pItem->jumlah }} {{ $pItem->satuan }}) — {{ $pItem->deskripsi ?? 'Penggantian' }}
                        </option>
                    @endforeach
                </select>

                <!-- Custom Trigger Button -->
                <button type="button"
                    id="btn-laporan-item"
                    class="w-full px-4 py-3 rounded-2xl bg-input border border-border text-foreground text-xs font-semibold focus:ring-2 focus:ring-primary flex items-center justify-between gap-3 text-left transition-all hover:border-primary/50"
                    onclick="toggleSearchableSelect('laporan-item')">
                    <span id="label-laporan-item" class="truncate text-muted-foreground font-normal">-- Pilih Item Laporan --</span>
                    <svg class="w-4 h-4 text-muted-foreground flex-shrink-0 transition-transform duration-200 chevron-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="dropdown-laporan-item"
                    class="absolute left-0 right-0 top-full mt-1.5 z-40 bg-card border border-border rounded-2xl shadow-xl overflow-hidden hidden">
                    <!-- Search Header -->
                    <div class="p-2.5 border-b border-border bg-muted/40 relative">
                        <svg class="w-3.5 h-3.5 absolute left-5 top-1/2 -translate-y-1/2 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                            id="search-input-laporan-item"
                            placeholder="Ketik untuk mencari (nama barang, lab, atau deskripsi)..."
                            class="w-full pl-8 pr-3 py-2 rounded-xl bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary"
                            autocomplete="off"
                            oninput="filterSearchableSelect('laporan-item', this.value)">
                    </div>

                    <!-- Items List -->
                    <div id="list-laporan-item" class="max-h-60 overflow-y-auto divide-y divide-border/60">
                        @forelse($pendingItems as $pItem)
                            <button type="button"
                                class="w-full text-left px-4 py-3 hover:bg-muted/70 transition-colors flex flex-col gap-1 option-item group"
                                data-value="{{ $pItem->id }}"
                                data-search="{{ strtolower(($pItem->laporan->lab_nama ?? '') . ' ' . $pItem->nama_barang . ' ' . ($pItem->deskripsi ?? '')) }}"
                                onclick="selectLaporanOption('{{ $pItem->id }}', '{{ addslashes($pItem->nama_barang) }}', '{{ $pItem->laporan->lab_nama ?? '-' }}', '{{ $pItem->sisa_kebutuhan }}', '{{ $pItem->jumlah }}', '{{ $pItem->satuan }}', '{{ addslashes($pItem->deskripsi ?? 'Penggantian') }}')">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-bold text-xs text-foreground group-hover:text-primary transition-colors truncate">
                                        {{ $pItem->nama_barang }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-primary/10 text-primary flex-shrink-0">
                                        Lab {{ $pItem->laporan->lab_nama ?? '-' }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-muted-foreground">
                                    <span class="truncate italic">{{ $pItem->deskripsi ?? 'Perlu unit pengganti' }}</span>
                                    <span class="font-semibold text-foreground flex-shrink-0 ml-2">
                                        Sisa: <strong class="text-primary font-bold">{{ $pItem->sisa_kebutuhan }}</strong> / {{ $pItem->jumlah }} {{ $pItem->satuan }}
                                    </span>
                                </div>
                            </button>
                        @empty
                            <div class="p-4 text-center text-xs text-muted-foreground">
                                Tidak ada item laporan yang menunggu penggantian.
                            </div>
                        @endforelse
                        <div id="empty-laporan-item" class="p-4 text-center text-xs text-muted-foreground hidden">
                            Tidak ada item laporan yang sesuai dengan kata kunci pencarian.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Source Warehouse Stock (Searchable) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="relative custom-searchable-select" id="wrapper-stok-gudang">
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Ambil dari Stok Gudang <span class="text-destructive">*</span>
                    </label>

                    <!-- Hidden Native Select -->
                    <select name="stok_gudang_id" id="select-stok-gudang" required class="sr-only" tabindex="-1">
                        <option value="">-- Pilih Stok Barang --</option>
                        @foreach($stoks as $stk)
                            <option value="{{ $stk->id }}"
                                data-nama="{{ $stk->barangMaster->nama_lengkap }}"
                                data-stok="{{ $stk->stok_saat_ini }}"
                                data-satuan="{{ $stk->barangMaster->satuan }}"
                                data-kategori="{{ $stk->barangMaster->kategori ?? 'Umum' }}"
                                {{ old('stok_gudang_id', $selectedStokId ?? null) == $stk->id ? 'selected' : '' }}>
                                {{ $stk->barangMaster->nama_lengkap }} (Tersedia: {{ $stk->stok_saat_ini }} {{ $stk->barangMaster->satuan }})
                            </option>
                        @endforeach
                    </select>

                    <!-- Custom Trigger Button -->
                    <button type="button"
                        id="btn-stok-gudang"
                        class="w-full px-4 py-3 rounded-2xl bg-input border border-border text-foreground text-xs font-semibold focus:ring-2 focus:ring-primary flex items-center justify-between gap-3 text-left transition-all hover:border-primary/50"
                        onclick="toggleSearchableSelect('stok-gudang')">
                        <span id="label-stok-gudang" class="truncate text-muted-foreground font-normal">-- Pilih Stok Barang --</span>
                        <svg class="w-4 h-4 text-muted-foreground flex-shrink-0 transition-transform duration-200 chevron-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="dropdown-stok-gudang"
                        class="absolute left-0 right-0 top-full mt-1.5 z-40 bg-card border border-border rounded-2xl shadow-xl overflow-hidden hidden">
                        <!-- Search Header -->
                        <div class="p-2.5 border-b border-border bg-muted/40 relative">
                            <svg class="w-3.5 h-3.5 absolute left-5 top-1/2 -translate-y-1/2 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text"
                                id="search-input-stok-gudang"
                                placeholder="Ketik untuk mencari stok barang gudang..."
                                class="w-full pl-8 pr-3 py-2 rounded-xl bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary"
                                autocomplete="off"
                                oninput="filterSearchableSelect('stok-gudang', this.value)">
                        </div>

                        <!-- Items List -->
                        <div id="list-stok-gudang" class="max-h-60 overflow-y-auto divide-y divide-border/60">
                            @forelse($stoks as $stk)
                                <button type="button"
                                    class="w-full text-left px-4 py-3 hover:bg-muted/70 transition-colors flex items-center justify-between gap-2 option-item group"
                                    data-value="{{ $stk->id }}"
                                    data-search="{{ strtolower($stk->barangMaster->nama_lengkap . ' ' . ($stk->barangMaster->kategori ?? '')) }}"
                                    onclick="selectStokOption('{{ $stk->id }}', '{{ addslashes($stk->barangMaster->nama_lengkap) }}', '{{ $stk->stok_saat_ini }}', '{{ $stk->barangMaster->satuan }}', '{{ addslashes($stk->barangMaster->kategori ?? 'Umum') }}')">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-bold text-xs text-foreground group-hover:text-primary transition-colors truncate">
                                                {{ $stk->barangMaster->nama_barang }}
                                            </span>
                                            @if(!empty($stk->barangMaster->spesifikasi))
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-primary/10 text-primary border border-primary/20">
                                                    {{ $stk->barangMaster->spesifikasi }}
                                                </span>
                                            @endif
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-muted text-muted-foreground uppercase flex-shrink-0">
                                                {{ $stk->barangMaster->kategori ?? 'Umum' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                                            Tersedia: {{ $stk->stok_saat_ini }} {{ $stk->barangMaster->satuan }}
                                        </span>
                                    </div>
                                </button>
                            @empty
                                <div class="p-4 text-center text-xs text-muted-foreground">
                                    Tidak ada stok barang tersedia di gudang.
                                </div>
                            @endforelse
                            <div id="empty-stok-gudang" class="p-4 text-center text-xs text-muted-foreground hidden">
                                Tidak ada stok barang yang cocok dengan kata kunci pencarian.
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Jumlah yang Dikirim <span class="text-destructive">*</span>
                    </label>
                    <input type="number" name="jumlah" id="input-jumlah" value="{{ old('jumlah', 1) }}" min="1" required
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-xs font-bold focus:ring-2 focus:ring-primary">
                    <p id="helper-jumlah" class="text-[11px] text-muted-foreground mt-1.5 flex items-center gap-1">
                        <span>💡 Anda dapat mendistribusikan sebagian (parsial). Masukkan jumlah sesuai barang yang dikirim.</span>
                    </p>
                </div>
            </div>

            <!-- Asal-usul Barang (Audit Akreditasi) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Asal Sumber Barang (Audit) <span class="text-destructive">*</span>
                    </label>
                    <select name="asal_barang" id="select-asal-barang" required onchange="togglePengajuanSelect()"
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-xs font-semibold focus:ring-2 focus:ring-primary">
                        <option value="stok_lama" {{ old('asal_barang') === 'stok_lama' ? 'selected' : '' }}>Stok Lama Gudang (Sebelumnya)</option>
                        <option value="pengajuan" {{ old('asal_barang') === 'pengajuan' ? 'selected' : '' }}>Dari Pengajuan Pengadaan Tertentu</option>
                    </select>
                </div>

                <div id="pengajuan-item-group" class="{{ old('asal_barang') === 'pengajuan' ? '' : 'hidden' }}">
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Pilih No. Pengajuan Asal & Spesifikasi Diterima
                    </label>
                    <select name="pengajuan_item_id" id="select-pengajuan-item" onchange="updatePengajuanItemDetail()"
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-xs focus:ring-2 focus:ring-primary">
                        <option value="">-- Pilih Pengajuan Asal --</option>
                        @foreach($pengajuanItems as $pItem)
                            @php
                                $spekFinal = $pItem->spesifikasi_diterima ?: ($pItem->spesifikasi ?: '-');
                            @endphp
                            <option value="{{ $pItem->id }}"
                                data-spek-diterima="{{ $spekFinal }}"
                                data-jumlah="{{ $pItem->jumlah_diterima }}"
                                data-satuan="{{ $pItem->satuan }}"
                                data-status="{{ $pItem->status_kesesuaian }}"
                                {{ old('pengajuan_item_id') == $pItem->id ? 'selected' : '' }}>
                                {{ $pItem->pengajuan->nomor_pengajuan ?? 'PGD' }} — {{ $pItem->nama_barang }} (Diterima: {{ $pItem->jumlah_diterima }} {{ $pItem->satuan }}) | Spek Diterima: {{ $spekFinal }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Detail Spesifikasi Diterima Preview --}}
                    <div id="pengajuan-item-detail" class="hidden mt-2.5 p-3 rounded-2xl bg-card border border-border text-xs space-y-1 animate-fade-in shadow-2xs">
                        <div class="flex items-center justify-between text-[11px] text-muted-foreground">
                            <span class="font-semibold flex items-center gap-1.5 text-foreground/80">
                                <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Spesifikasi Fisik Diterima:
                            </span>
                            <span id="detail-pengajuan-status" class="px-2 py-0.5 rounded-lg text-[10px] font-bold"></span>
                        </div>
                        <p class="font-medium text-foreground text-xs break-words pl-5" id="detail-pengajuan-spek">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2 (Khusus Mandiri): Alasan & Kebutuhan Distribusi -->
        <div id="section-alasan-mandiri" class="space-y-4 {{ $currentMode === 'mandiri' ? '' : 'hidden' }}">
            <div class="border-b border-border pb-3">
                <h3 class="text-base font-bold text-foreground flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-black">2</span>
                    Alasan & Kebutuhan Distribusi Mandiri
                </h3>
                <p class="text-xs text-muted-foreground mt-0.5">Tentukan keperluan distribusi proaktif untuk dokumentasi inventaris dan akreditasi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Kategori Alasan Distribusi <span class="text-destructive">*</span>
                    </label>
                    <select name="kategori_alasan" id="select-kategori-alasan" onchange="toggleKategoriKustom()"
                        {{ $currentMode === 'mandiri' ? 'required' : '' }}
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-xs font-semibold focus:ring-2 focus:ring-primary">
                        <option value="">-- Pilih Kategori Alasan --</option>
                        @foreach($kategoriAlasanList as $kat)
                            <option value="{{ $kat }}" {{ old('kategori_alasan') === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="group-kategori-kustom" class="{{ old('kategori_alasan') === 'Lainnya' ? '' : 'hidden' }}">
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Tulis Kategori Khusus <span class="text-muted-foreground font-normal lowercase">(opsional)</span>
                    </label>
                    <input type="text" name="kategori_alasan_kustom" id="input-kategori-kustom" value="{{ old('kategori_alasan_kustom') }}"
                        placeholder="Contoh: Peminjaman Workshop, Cadangan Event..."
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-xs focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                    Keterangan / Rincian Kebutuhan <span class="text-muted-foreground font-normal lowercase">(opsional)</span>
                </label>
                <textarea name="alasan_distribusi" id="input-alasan-distribusi" rows="2"
                    placeholder="Contoh: Upgrade 15 unit PC dari HDD 500GB ke SSD 256GB untuk peningkatan kinerja praktikum..."
                    class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-xs focus:ring-2 focus:ring-primary">{{ old('alasan_distribusi') }}</textarea>
                <p class="text-[11px] text-muted-foreground mt-1">
                    Catatan ini akan tersimpan di log mutasi stok gudang dan rekapitulasi akreditasi.
                </p>
            </div>
        </div>

        <!-- Section Terakhir: Tujuan Detail (Lab & Kode PC) -->
        <div class="space-y-4">
            <div class="border-b border-border pb-3">
                <h3 class="text-base font-bold text-foreground flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-black" id="step-tujuan-number">
                        {{ $currentMode === 'mandiri' ? '3' : '2' }}
                    </span>
                    Tujuan Spesifik Lab & Unit PC
                </h3>
                <p class="text-xs text-muted-foreground mt-0.5">Catatan detail laboratorium dan nama penerima untuk penelusuran akreditasi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Laboratorium Tujuan <span class="text-destructive">*</span>
                    </label>
                    <select name="lab_tujuan" id="select-lab-tujuan" required class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-xs font-bold focus:ring-2 focus:ring-primary">
                        <option value="">-- Pilih Lab (14 Lab) --</option>
                        @foreach($labs as $lab)
                            <option value="{{ $lab }}" {{ old('lab_tujuan') === $lab ? 'selected' : '' }}>Lab {{ $lab }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Peruntukan / Kode PC <span class="text-muted-foreground font-normal lowercase">(opsional)</span>
                    </label>
                    <input type="text" name="kode_pc_tujuan" id="input-kode-pc" value="{{ old('kode_pc_tujuan') }}"
                        placeholder="Contoh: PC-04, PC Dosen, Switch Utama, atau kosongkan..."
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-xs focus:ring-2 focus:ring-primary">
                    <p class="text-[11px] text-muted-foreground mt-1">Kosongkan jika untuk kebutuhan umum lab / bukan unit PC tertentu.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Nama Penerima / Laboran Lab <span class="text-muted-foreground font-normal lowercase">(opsional)</span>
                    </label>
                    <input type="text" name="diterima_oleh" value="{{ old('diterima_oleh') }}"
                        placeholder="Nama laboran / teknisi lab penerima..."
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-xs focus:ring-2 focus:ring-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Tanggal & Jam Distribusi <span class="text-destructive">*</span>
                    </label>
                    <input type="datetime-local" name="tanggal_distribusi" value="{{ old('tanggal_distribusi', now()->format('Y-m-d\TH:i')) }}" required
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-xs font-medium focus:ring-2 focus:ring-primary">
                    <p class="text-[11px] text-muted-foreground mt-1">
                        Otomatis terisi tanggal dan jam saat ini (WIB), dapat disesuaikan jika perlu.
                    </p>
                </div>
            </div>

            {{-- Tanggal Terima Lab (Konfirmasi) --}}
            <div class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50/60 dark:border-emerald-800/50 dark:bg-emerald-900/10">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider mb-1">
                            Tanggal Terima Lab <span class="text-muted-foreground font-normal normal-case">(Konfirmasi — opsional)</span>
                        </label>
                        <p class="text-[11px] text-emerald-600/80 dark:text-emerald-500/70 mb-2.5">
                            Isi jika barang sudah dikonfirmasi diterima oleh lab. Bisa juga dikonfirmasi nanti melalui tombol di halaman daftar distribusi.
                        </p>
                        <input type="date" name="tanggal_terima_lab" value="{{ old('tanggal_terima_lab') }}"
                            class="w-full sm:w-64 px-4 py-2.5 rounded-2xl bg-white dark:bg-card border border-emerald-300 dark:border-emerald-700/60 text-foreground text-xs font-medium focus:ring-2 focus:ring-emerald-400/30 focus:border-emerald-500 transition-colors">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
            <a href="{{ route('distribusi.index') }}" class="px-5 py-2.5 rounded-2xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-2xl bg-primary text-white text-xs font-bold shadow-lg shadow-primary/25 hover:bg-primary/90 transition-transform active:scale-95">
                Simpan & Distribusikan Barang
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    function switchMode(mode) {
        const isMandiri = (mode === 'mandiri');
        document.getElementById('input-jenis-distribusi').value = mode;

        const cardLaporan = document.getElementById('card-mode-laporan');
        const cardMandiri = document.getElementById('card-mode-mandiri');

        if (isMandiri) {
            cardMandiri.classList.add('border-primary', 'bg-primary/5', 'shadow-xs');
            cardMandiri.classList.remove('border-border', 'bg-card');
            cardMandiri.querySelector('.icon-box').classList.add('bg-primary', 'text-white');
            cardMandiri.querySelector('.icon-box').classList.remove('bg-muted', 'text-muted-foreground');
            cardMandiri.querySelector('.radio-dot').classList.add('border-primary');
            cardMandiri.querySelector('.dot-inner').classList.remove('hidden');

            cardLaporan.classList.remove('border-primary', 'bg-primary/5', 'shadow-xs');
            cardLaporan.classList.add('border-border', 'bg-card');
            cardLaporan.querySelector('.icon-box').classList.remove('bg-primary', 'text-white');
            cardLaporan.querySelector('.icon-box').classList.add('bg-muted', 'text-muted-foreground');
            cardLaporan.querySelector('.radio-dot').classList.remove('border-primary');
            cardLaporan.querySelector('.dot-inner').classList.add('hidden');

            // Toggle sections
            document.getElementById('wrapper-laporan-item').classList.add('hidden');
            document.getElementById('section-alasan-mandiri').classList.remove('hidden');
            document.getElementById('step-tujuan-number').textContent = '3';
            document.getElementById('section-1-title').textContent = 'Pilih Stok Barang di Gudang';
            document.getElementById('section-1-subtitle').textContent = 'Pilih stok barang yang akan diambil dari gudang dan tentukan jumlahnya.';

            // Validation attributes
            document.getElementById('select-laporan-item').removeAttribute('required');
            document.getElementById('select-kategori-alasan').setAttribute('required', 'required');

            // Reset max jumlah constraint to stok gudang max
            updateMandiriJumlahHelper();
        } else {
            cardLaporan.classList.add('border-primary', 'bg-primary/5', 'shadow-xs');
            cardLaporan.classList.remove('border-border', 'bg-card');
            cardLaporan.querySelector('.icon-box').classList.add('bg-primary', 'text-white');
            cardLaporan.querySelector('.icon-box').classList.remove('bg-muted', 'text-muted-foreground');
            cardLaporan.querySelector('.radio-dot').classList.add('border-primary');
            cardLaporan.querySelector('.dot-inner').classList.remove('hidden');

            cardMandiri.classList.remove('border-primary', 'bg-primary/5', 'shadow-xs');
            cardMandiri.classList.add('border-border', 'bg-card');
            cardMandiri.querySelector('.icon-box').classList.remove('bg-primary', 'text-white');
            cardMandiri.querySelector('.icon-box').classList.add('bg-muted', 'text-muted-foreground');
            cardMandiri.querySelector('.radio-dot').classList.remove('border-primary');
            cardMandiri.querySelector('.dot-inner').classList.add('hidden');

            // Toggle sections
            document.getElementById('wrapper-laporan-item').classList.remove('hidden');
            document.getElementById('section-alasan-mandiri').classList.add('hidden');
            document.getElementById('step-tujuan-number').textContent = '2';
            document.getElementById('section-1-title').textContent = 'Item Laporan & Sumber Barang di Gudang';
            document.getElementById('section-1-subtitle').textContent = 'Pilih laporan lab yang akan diselesaikan dan stok barang yang diambil.';

            // Validation attributes
            document.getElementById('select-laporan-item').setAttribute('required', 'required');
            document.getElementById('select-kategori-alasan').removeAttribute('required');

            // Re-trigger laporan item change for helper
            const itemSelect = document.getElementById('select-laporan-item');
            if (itemSelect && itemSelect.value) {
                itemSelect.dispatchEvent(new Event('change'));
            }
        }
    }

    function toggleKategoriKustom() {
        const val = document.getElementById('select-kategori-alasan').value;
        const group = document.getElementById('group-kategori-kustom');
        if (val === 'Lainnya') {
            group.classList.remove('hidden');
            document.getElementById('input-kategori-kustom').focus();
        } else {
            group.classList.add('hidden');
        }
    }

    function updateMandiriJumlahHelper() {
        const stokSelect = document.getElementById('select-stok-gudang');
        const helper = document.getElementById('helper-jumlah');
        const inputJumlah = document.getElementById('input-jumlah');

        if (!stokSelect || !helper || !inputJumlah) return;

        const opt = stokSelect.options[stokSelect.selectedIndex];
        if (opt && opt.value) {
            const stok = opt.dataset.stok ? parseInt(opt.dataset.stok) : 0;
            const satuan = opt.dataset.satuan || 'unit';
            inputJumlah.max = stok;
            helper.innerHTML = `<span class="text-emerald-600 dark:text-emerald-400 font-semibold">ℹ️ Stok Gudang Tersedia: ${stok} ${satuan}. Masukkan jumlah unit yang akan didistribusikan.</span>`;
        } else {
            helper.innerHTML = `<span>💡 Masukkan jumlah sesuai unit yang dikirim. Maksimum sesuai stok fisik tersedia.</span>`;
        }
    }

    function togglePengajuanSelect() {
        const asal = document.getElementById('select-asal-barang').value;
        const group = document.getElementById('pengajuan-item-group');
        if (asal === 'pengajuan') {
            group.classList.remove('hidden');
            updatePengajuanItemDetail();
        } else {
            group.classList.add('hidden');
        }
    }

    function updatePengajuanItemDetail() {
        const select = document.getElementById('select-pengajuan-item');
        const container = document.getElementById('pengajuan-item-detail');
        const spekEl = document.getElementById('detail-pengajuan-spek');
        const statusEl = document.getElementById('detail-pengajuan-status');

        if (!select || !container || !spekEl) return;

        const opt = select.options[select.selectedIndex];
        if (opt && opt.value) {
            const spekDiterima = opt.dataset.spekDiterima || '-';
            const status = opt.dataset.status || '';
            spekEl.textContent = spekDiterima;

            if (statusEl) {
                if (status === 'sesuai_beda_spesifikasi') {
                    statusEl.textContent = '🔄 Beda Merk/Spek';
                    statusEl.className = 'px-2 py-0.5 rounded-lg text-[10px] font-bold bg-amber-500/15 text-amber-600 dark:text-amber-400';
                } else if (status === 'sesuai') {
                    statusEl.textContent = '✅ Sesuai Penuh';
                    statusEl.className = 'px-2 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-500/15 text-emerald-600 dark:text-emerald-400';
                } else {
                    statusEl.textContent = '';
                    statusEl.className = '';
                }
            }

            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    // Auto fill lab and quantity when target report item is selected (mode laporan)
    const itemSelect = document.getElementById('select-laporan-item');
    if (itemSelect) {
        itemSelect.addEventListener('change', function () {
            const mode = document.getElementById('input-jenis-distribusi').value;
            if (mode !== 'laporan') return;

            const opt = this.options[this.selectedIndex];
            if (opt && opt.dataset.lab) {
                document.getElementById('select-lab-tujuan').value = opt.dataset.lab;
            }
            if (opt) {
                const sisa = opt.dataset.sisa ? parseInt(opt.dataset.sisa) : (opt.dataset.jumlah ? parseInt(opt.dataset.jumlah) : 1);
                const inputJumlah = document.getElementById('input-jumlah');
                if (inputJumlah) {
                    inputJumlah.value = sisa;
                    inputJumlah.max = sisa;
                }
                const helper = document.getElementById('helper-jumlah');
                if (helper && opt.dataset.jumlah) {
                    const total = opt.dataset.jumlah;
                    const terkirim = opt.dataset.terkirim || 0;
                    const satuan = opt.dataset.satuan || 'unit';
                    helper.innerHTML = `<span class="text-primary font-semibold">ℹ️ Kuota: Total butuh ${total} ${satuan} (sudah terdistribusi ${terkirim} ${satuan}). Sisa kebutuhan: ${sisa} ${satuan}. Anda dapat mendistribusikan sebagian.</span>`;
                }
            }
        });
    }

    function toggleSearchableSelect(type) {
        const dropdown = document.getElementById(`dropdown-${type}`);
        if (!dropdown) return;
        const isHidden = dropdown.classList.contains('hidden');
        closeAllSearchableSelects();
        if (isHidden) {
            dropdown.classList.remove('hidden');
            const chevron = document.querySelector(`#wrapper-${type} .chevron-icon`);
            if (chevron) chevron.classList.add('rotate-180');
            const searchInput = document.getElementById(`search-input-${type}`);
            if (searchInput) {
                searchInput.value = '';
                filterSearchableSelect(type, '');
                setTimeout(() => searchInput.focus(), 50);
            }
        }
    }

    function closeAllSearchableSelects() {
        document.querySelectorAll('[id^="dropdown-"]').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.chevron-icon').forEach(el => el.classList.remove('rotate-180'));
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.custom-searchable-select')) {
            closeAllSearchableSelects();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllSearchableSelects();
        }
    });

    function filterSearchableSelect(type, query) {
        const q = query.toLowerCase().trim();
        const list = document.getElementById(`list-${type}`);
        if (!list) return;
        const items = list.querySelectorAll('.option-item');
        let visibleCount = 0;

        items.forEach(item => {
            const searchStr = item.dataset.search || '';
            if (!q || searchStr.includes(q)) {
                item.classList.remove('hidden');
                visibleCount++;
            } else {
                item.classList.add('hidden');
            }
        });

        const emptyEl = document.getElementById(`empty-${type}`);
        if (emptyEl) {
            if (visibleCount === 0) {
                emptyEl.classList.remove('hidden');
            } else {
                emptyEl.classList.add('hidden');
            }
        }
    }

    function selectLaporanOption(id, nama, lab, sisa, total, satuan, deskripsi) {
        const select = document.getElementById('select-laporan-item');
        const label = document.getElementById('label-laporan-item');
        const btn = document.getElementById('btn-laporan-item');

        if (select) {
            select.value = id;
            select.dispatchEvent(new Event('change'));
        }

        if (btn) {
            btn.classList.remove('ring-2', 'ring-destructive');
        }

        if (label) {
            label.innerHTML = `
                <div class="flex items-center gap-2 truncate">
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-primary/10 text-primary flex-shrink-0">Lab ${lab}</span>
                    <span class="font-bold text-foreground truncate">${nama}</span>
                    <span class="text-muted-foreground text-[11px] truncate">(Sisa ${sisa}/${total} ${satuan})</span>
                </div>
            `;
            label.classList.remove('text-muted-foreground', 'font-normal');
        }

        closeAllSearchableSelects();
    }

    function selectStokOption(id, nama, stok, satuan, kategori) {
        const select = document.getElementById('select-stok-gudang');
        const label = document.getElementById('label-stok-gudang');
        const btn = document.getElementById('btn-stok-gudang');

        if (select) {
            select.value = id;
            select.dispatchEvent(new Event('change'));
        }

        if (btn) {
            btn.classList.remove('ring-2', 'ring-destructive');
        }

        if (label) {
            label.innerHTML = `
                <div class="flex items-center justify-between w-full gap-2 truncate">
                    <div class="flex items-center gap-1.5 truncate">
                        <span class="font-bold text-foreground truncate">${nama}</span>
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold bg-muted text-muted-foreground uppercase flex-shrink-0">${kategori}</span>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 flex-shrink-0">Tersedia: ${stok} ${satuan}</span>
                </div>
            `;
            label.classList.remove('text-muted-foreground', 'font-normal');
        }

        const mode = document.getElementById('input-jenis-distribusi').value;
        if (mode === 'mandiri') {
            updateMandiriJumlahHelper();
        }

        closeAllSearchableSelects();
    }

    // Intercept form submission to validate custom selectors nicely
    document.getElementById('distribusi-form')?.addEventListener('submit', function (e) {
        const mode = document.getElementById('input-jenis-distribusi').value;
        const stokVal = document.getElementById('select-stok-gudang').value;

        if (mode === 'laporan') {
            const laporanVal = document.getElementById('select-laporan-item').value;
            if (!laporanVal) {
                e.preventDefault();
                const btn = document.getElementById('btn-laporan-item');
                btn.classList.add('ring-2', 'ring-destructive');
                btn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                toggleSearchableSelect('laporan-item');
                return;
            }
        } else if (mode === 'mandiri') {
            const katVal = document.getElementById('select-kategori-alasan').value;
            if (!katVal) {
                e.preventDefault();
                const sel = document.getElementById('select-kategori-alasan');
                sel.focus();
                sel.classList.add('ring-2', 'ring-destructive');
                sel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
        }

        if (!stokVal) {
            e.preventDefault();
            const btn = document.getElementById('btn-stok-gudang');
            btn.classList.add('ring-2', 'ring-destructive');
            btn.scrollIntoView({ behavior: 'smooth', block: 'center' });
            toggleSearchableSelect('stok-gudang');
            return;
        }
    });

    // Initialize labels on page load
    document.addEventListener('DOMContentLoaded', function () {
        // Init Laporan Item
        const laporanSelect = document.getElementById('select-laporan-item');
        if (laporanSelect && laporanSelect.value) {
            const opt = laporanSelect.options[laporanSelect.selectedIndex];
            if (opt && opt.value) {
                selectLaporanOption(
                    opt.value,
                    opt.dataset.nama || '',
                    opt.dataset.lab || '-',
                    opt.dataset.sisa || opt.dataset.jumlah || '',
                    opt.dataset.jumlah || '',
                    opt.dataset.satuan || '',
                    opt.dataset.deskripsi || ''
                );
            }
        }

        // Init Stok Gudang
        const stokSelect = document.getElementById('select-stok-gudang');
        if (stokSelect && stokSelect.value) {
            const opt = stokSelect.options[stokSelect.selectedIndex];
            if (opt && opt.value) {
                selectStokOption(
                    opt.value,
                    opt.dataset.nama || opt.text,
                    opt.dataset.stok || '',
                    opt.dataset.satuan || '',
                    opt.dataset.kategori || 'Umum'
                );
            }
        }

        // Init Pengajuan Item Detail
        updatePengajuanItemDetail();

        // Init Mode
        const initialMode = document.getElementById('input-jenis-distribusi').value;
        if (initialMode === 'mandiri') {
            switchMode('mandiri');
        }
    });
</script>
@endpush
