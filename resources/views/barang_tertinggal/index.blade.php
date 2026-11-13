@extends('layouts.app')

@section('title', 'Barang Tertinggal (Lost & Found)')
@section('header_title', 'Pencatatan Barang Tertinggal')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto pb-10">

    {{-- Stats Cards (Klik untuk filter otomatis berdasarkan status) --}}
    @php
        $currStatus = request('status');
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
        {{-- Total Barang (Reset Filter Status) --}}
        <a href="{{ route('barang-tertinggal.index', request()->except(['page', 'status'])) }}"
            class="p-4 sm:p-5 rounded-3xl flex items-center gap-3.5 transition-all duration-200 cursor-pointer group hover:-translate-y-1 hover:shadow-md active:scale-[0.98] {{ empty($currStatus) ? 'bg-card border-2 border-primary ring-2 ring-primary/20 shadow-xs' : 'bg-card border border-border hover:border-primary/60 hover:bg-primary/5 hover:shadow-primary/5 shadow-2xs' }}"
            title="{{ empty($currStatus) ? 'Menampilkan semua barang (Filter aktif)' : 'Klik untuk tampilkan semua status' }}">
            <div class="w-11 h-11 rounded-2xl {{ empty($currStatus) ? 'bg-primary text-white shadow-xs' : 'bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white group-hover:shadow-xs' }} flex items-center justify-center font-bold text-lg flex-shrink-0 transition-all duration-200 group-hover:scale-105">
                <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-1.5">
                    <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wider group-hover:text-foreground transition-colors">Total Barang</p>
                    @if(empty($currStatus))
                        <span class="w-1.5 h-1.5 rounded-full bg-primary" title="Filter Aktif"></span>
                    @endif
                </div>
                <p class="text-xl sm:text-2xl font-black text-foreground group-hover:text-primary transition-colors">{{ number_format($stats['total']) }}</p>
            </div>
        </a>

        {{-- Belum Diambil --}}
        @php
            $isBelumDiambil = $currStatus === 'belum_diambil';
            $belumDiambilUrl = route('barang-tertinggal.index', array_merge(request()->except(['page', 'status']), $isBelumDiambil ? [] : ['status' => 'belum_diambil']));
        @endphp
        <a href="{{ $belumDiambilUrl }}"
            class="p-4 sm:p-5 rounded-3xl flex items-center gap-3.5 transition-all duration-200 cursor-pointer group hover:-translate-y-1 hover:shadow-md active:scale-[0.98] bg-gradient-to-br from-card to-amber-500/5 {{ $isBelumDiambil ? 'border-2 border-amber-500 ring-2 ring-amber-500/20 shadow-xs' : 'border border-amber-300/40 dark:border-amber-900/40 hover:border-amber-500 hover:bg-amber-500/10 hover:shadow-amber-500/5 shadow-2xs' }}"
            title="{{ $isBelumDiambil ? 'Filter Belum Diambil aktif (Klik untuk reset)' : 'Klik untuk filter barang belum diambil' }}">
            <div class="w-11 h-11 rounded-2xl {{ $isBelumDiambil ? 'bg-amber-500 text-white shadow-xs' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400 group-hover:bg-amber-500 group-hover:text-white group-hover:shadow-xs' }} flex items-center justify-center font-bold text-lg flex-shrink-0 transition-all duration-200 group-hover:scale-105">
                <span class="text-base font-bold transition-transform duration-200 group-hover:scale-110">○</span>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-1.5">
                    <p class="text-xs font-semibold text-amber-700 dark:text-amber-400 uppercase tracking-wider">Belum Diambil</p>
                    @if($isBelumDiambil)
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500" title="Filter Aktif"></span>
                    @endif
                </div>
                <p class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400 group-hover:scale-105 origin-left transition-transform duration-200">{{ number_format($stats['belum_diambil']) }}</p>
            </div>
        </a>

        {{-- Diumumkan --}}
        @php
            $isDiumumkan = $currStatus === 'diumumkan';
            $diumumkanUrl = route('barang-tertinggal.index', array_merge(request()->except(['page', 'status']), $isDiumumkan ? [] : ['status' => 'diumumkan']));
        @endphp
        <a href="{{ $diumumkanUrl }}"
            class="p-4 sm:p-5 rounded-3xl flex items-center gap-3.5 transition-all duration-200 cursor-pointer group hover:-translate-y-1 hover:shadow-md active:scale-[0.98] bg-gradient-to-br from-card to-sky-500/5 {{ $isDiumumkan ? 'border-2 border-sky-500 ring-2 ring-sky-500/20 shadow-xs' : 'border border-sky-300/40 dark:border-sky-900/40 hover:border-sky-500 hover:bg-sky-500/10 hover:shadow-sky-500/5 shadow-2xs' }}"
            title="{{ $isDiumumkan ? 'Filter Diumumkan aktif (Klik untuk reset)' : 'Klik untuk filter barang diumumkan' }}">
            <div class="w-11 h-11 rounded-2xl {{ $isDiumumkan ? 'bg-sky-500 text-white shadow-xs' : 'bg-sky-500/10 text-sky-600 dark:text-sky-400 group-hover:bg-sky-500 group-hover:text-white group-hover:shadow-xs' }} flex items-center justify-center font-bold text-lg flex-shrink-0 transition-all duration-200 group-hover:scale-105">
                <span class="text-base font-bold transition-transform duration-200 group-hover:scale-110">◐</span>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-1.5">
                    <p class="text-xs font-semibold text-sky-700 dark:text-sky-400 uppercase tracking-wider">Diumumkan</p>
                    @if($isDiumumkan)
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500" title="Filter Aktif"></span>
                    @endif
                </div>
                <p class="text-xl sm:text-2xl font-black text-sky-600 dark:text-sky-400 group-hover:scale-105 origin-left transition-transform duration-200">{{ number_format($stats['diumumkan']) }}</p>
            </div>
        </a>

        {{-- Sudah Diambil --}}
        @php
            $isSudahDiambil = $currStatus === 'sudah_diambil';
            $sudahDiambilUrl = route('barang-tertinggal.index', array_merge(request()->except(['page', 'status']), $isSudahDiambil ? [] : ['status' => 'sudah_diambil']));
        @endphp
        <a href="{{ $sudahDiambilUrl }}"
            class="p-4 sm:p-5 rounded-3xl flex items-center gap-3.5 transition-all duration-200 cursor-pointer group hover:-translate-y-1 hover:shadow-md active:scale-[0.98] bg-gradient-to-br from-card to-emerald-500/5 {{ $isSudahDiambil ? 'border-2 border-emerald-500 ring-2 ring-emerald-500/20 shadow-xs' : 'border border-emerald-300/40 dark:border-emerald-900/40 hover:border-emerald-500 hover:bg-emerald-500/10 hover:shadow-emerald-500/5 shadow-2xs' }}"
            title="{{ $isSudahDiambil ? 'Filter Sudah Diambil aktif (Klik untuk reset)' : 'Klik untuk filter barang sudah diambil' }}">
            <div class="w-11 h-11 rounded-2xl {{ $isSudahDiambil ? 'bg-emerald-500 text-white shadow-xs' : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white group-hover:shadow-xs' }} flex items-center justify-center font-bold text-lg flex-shrink-0 transition-all duration-200 group-hover:scale-105">
                <span class="text-base font-bold transition-transform duration-200 group-hover:scale-110">✓</span>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-1.5">
                    <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Sudah Diambil</p>
                    @if($isSudahDiambil)
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500" title="Filter Aktif"></span>
                    @endif
                </div>
                <p class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 group-hover:scale-105 origin-left transition-transform duration-200">{{ number_format($stats['sudah_diambil']) }}</p>
            </div>
        </a>

        {{-- Ke Satpam --}}
        @php
            $isSatpam = $currStatus === 'diserahkan_ke_satpam';
            $satpamUrl = route('barang-tertinggal.index', array_merge(request()->except(['page', 'status']), $isSatpam ? [] : ['status' => 'diserahkan_ke_satpam']));
        @endphp
        <a href="{{ $satpamUrl }}"
            class="p-4 sm:p-5 rounded-3xl flex items-center gap-3.5 col-span-2 sm:col-span-1 transition-all duration-200 cursor-pointer group hover:-translate-y-1 hover:shadow-md active:scale-[0.98] bg-card {{ $isSatpam ? 'border-2 border-indigo-500 ring-2 ring-indigo-500/20 shadow-xs' : 'border border-border hover:border-indigo-500 hover:bg-indigo-500/5 hover:shadow-indigo-500/5 shadow-2xs' }}"
            title="{{ $isSatpam ? 'Filter Diserahkan ke Satpam aktif (Klik untuk reset)' : 'Klik untuk filter barang diserahkan ke satpam' }}">
            <div class="w-11 h-11 rounded-2xl {{ $isSatpam ? 'bg-indigo-500 text-white shadow-xs' : 'bg-muted text-muted-foreground group-hover:bg-indigo-500 group-hover:text-white group-hover:shadow-xs' }} flex items-center justify-center font-bold text-lg flex-shrink-0 transition-all duration-200 group-hover:scale-105">
                <span class="text-base font-bold transition-transform duration-200 group-hover:scale-110">→</span>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-1.5">
                    <p class="text-xs font-semibold text-muted-foreground uppercase tracking-wider group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Ke Satpam</p>
                    @if($isSatpam)
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500" title="Filter Aktif"></span>
                    @endif
                </div>
                <p class="text-xl sm:text-2xl font-black text-foreground group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ number_format($stats['diserahkan_ke_satpam']) }}</p>
            </div>
        </a>
    </div>

    {{-- Top Action Bar & Filter Container --}}
    <div class="bg-card border border-border p-6 sm:p-7 rounded-3xl shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div>
                <h3 class="text-base font-bold text-foreground">Daftar Barang Tertinggal di Laboratorium</h3>
                <p class="text-sm text-muted-foreground mt-0.5">Catat barang temuan mahasiswa/laboran, monitor status kepemilikan dan penyerahan.</p>
            </div>

            <div class="flex items-center flex-wrap gap-2 flex-shrink-0">
                {{-- Ekspor Excel --}}
                <a href="{{ route('ekspor.barang-tertinggal.excel', request()->query()) }}"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-sm font-medium transition-colors"
                    title="Unduh format Excel / CSV">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span>Excel</span>
                </a>

                {{-- Ekspor PDF / Cetak --}}
                <a href="{{ route('ekspor.barang-tertinggal.pdf', request()->query()) }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-sm font-medium transition-colors"
                    title="Cetak atau simpan PDF">
                    <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>PDF / Cetak</span>
                </a>

                {{-- Input Baru --}}
                <a href="{{ route('barang-tertinggal.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold shadow-md shadow-primary/20 hover:bg-primary/90 transition-transform active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Catat Barang Baru</span>
                </a>
            </div>
        </div>

        {{-- Filter Form --}}
        <form method="GET" action="{{ route('barang-tertinggal.index') }}"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 pt-4 border-t border-border">

            <div>
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Cari Barang / Pemilik / Penemu</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang, pemilik..."
                    class="w-full px-3 py-2 rounded-xl bg-input border border-border text-sm text-foreground placeholder-muted-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Pilih Lab / Lokasi</label>
                <select name="lab"
                    class="w-full px-3 py-2 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    <option value="">Semua Laboratorium / Lokasi</option>
                    @foreach($labs as $l)
                        <option value="{{ $l }}" {{ request('lab') === $l ? 'selected' : '' }}>Lab {{ $l }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Status Barang</label>
                <select name="status"
                    class="w-full px-3 py-2 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $val => $lbl)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
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
                @if(request()->anyFilled(['search', 'lab', 'status', 'start_date', 'end_date']))
                    <a href="{{ route('barang-tertinggal.index') }}"
                        class="p-2 rounded-xl border border-border text-muted-foreground hover:text-foreground hover:bg-muted transition-colors"
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
                        <th class="py-3 px-5 whitespace-nowrap">Kode & Tgl Temu</th>
                        <th class="py-3 px-4 whitespace-nowrap">Lab / Lokasi</th>
                        <th class="py-3 px-4">Barang & Keterangan</th>
                        <th class="py-3 px-4 whitespace-nowrap">Penemu</th>
                        <th class="py-3 px-4 whitespace-nowrap">Pemilik & Kontak</th>
                        <th class="py-3 px-4 whitespace-nowrap">Status</th>
                        <th class="py-3 px-4 whitespace-nowrap">Pengambilan</th>
                        <th class="py-3 px-5 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                    @forelse($barangTertinggals as $item)
                        <tr class="hover:bg-muted/30 transition-colors {{ $item->status === 'belum_diambil' ? 'bg-amber-500/[0.02]' : '' }}">
                            {{-- Kode & Tgl --}}
                            <td class="py-3.5 px-5 whitespace-nowrap">
                                <div class="font-mono text-xs font-bold text-foreground">
                                    #BT-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="text-xs text-muted-foreground mt-0.5">
                                    {{ $item->tanggal_ditemukan?->format('d/m/Y') }}
                                </div>
                            </td>

                            {{-- Lab / Lokasi --}}
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-primary/10 text-primary font-bold text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $item->lab_nama }}</span>
                                </div>
                            </td>

                            {{-- Barang & Keterangan --}}
                            <td class="py-3.5 px-4">
                                <a href="{{ route('barang-tertinggal.show', $item) }}" class="font-bold text-foreground hover:text-primary transition-colors text-sm">
                                    {{ $item->nama_barang }}
                                </a>
                                @if($item->keterangan)
                                    <div class="text-xs text-muted-foreground line-clamp-1 mt-0.5" title="{{ $item->keterangan }}">
                                        {{ $item->keterangan }}
                                    </div>
                                @endif
                                @if($item->catatan)
                                    <div class="text-[11px] text-muted-foreground/80 italic mt-0.5 line-clamp-1">
                                        Catatan: {{ $item->catatan }}
                                    </div>
                                @endif
                            </td>

                            {{-- Penemu --}}
                            <td class="py-3.5 px-4 whitespace-nowrap text-sm text-foreground">
                                <div class="font-medium">{{ $item->penemu }}</div>
                            </td>

                            {{-- Pemilik & Kontak --}}
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($item->pemilik)
                                    <div class="font-semibold text-foreground text-sm">{{ $item->pemilik }}</div>
                                    @if($item->no_hp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->no_hp) }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 mt-0.5"
                                            title="Chat via WhatsApp">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            <span>{{ $item->no_hp }}</span>
                                        </a>
                                    @endif
                                @else
                                    <span class="inline-block px-2 py-0.5 rounded-lg text-xs text-muted-foreground bg-muted">
                                        Belum Diketahui
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <x-status-badge :status="$item->status" />
                            </td>

                            {{-- Pengambilan --}}
                            <td class="py-3.5 px-4 whitespace-nowrap text-xs text-muted-foreground">
                                @if($item->tanggal_pengambilan)
                                    <div class="font-medium text-foreground">{{ $item->tanggal_pengambilan->format('d/m/Y') }}</div>
                                    @if($item->penyerah)
                                        <div class="text-[11px]">Oleh: {{ $item->penyerah }}</div>
                                    @endif
                                @else
                                    <span>—</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3.5 px-5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('barang-tertinggal.show', $item) }}"
                                        class="p-2 rounded-xl text-primary hover:bg-primary/10 transition-colors"
                                        title="Lihat Detail & Update Status">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>

                                    <a href="{{ route('barang-tertinggal.edit', $item) }}"
                                        class="p-2 rounded-xl text-muted-foreground hover:text-foreground hover:bg-muted transition-colors"
                                        title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('barang-tertinggal.destroy', $item) }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan barang tertinggal ini?');"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded-xl text-destructive hover:bg-destructive/10 transition-colors"
                                            title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-muted-foreground">
                                <div class="w-12 h-12 rounded-2xl bg-muted flex items-center justify-center mx-auto mb-3 text-muted-foreground">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <p class="font-semibold text-foreground">Tidak Ada Data Barang Tertinggal</p>
                                <p class="text-xs text-muted-foreground mt-1">Belum ada catatan barang tertinggal atau tidak sesuai dengan filter yang dipilih.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($barangTertinggals->hasPages())
            <div class="p-4 border-t border-border">
                {{ $barangTertinggals->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
