@extends('layouts.app')

@section('title', 'Detail Barang — ' . $stok->barangMaster->nama_barang)
@section('header_title', 'Detail Barang & Riwayat Kartu Stok')

@section('content')
<div class="flex flex-col gap-6 max-w-7xl mx-auto pb-10">

    {{-- Top Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('gudang.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-muted-foreground hover:text-primary transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar Inventaris</span>
        </a>

        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Edit Button --}}
            <button type="button" onclick="document.getElementById('modal-edit-barang').classList.remove('hidden')"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-border bg-card hover:bg-muted text-foreground text-xs font-bold transition-colors shadow-2xs">
                <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Edit Data Barang</span>
            </button>

            {{-- Penyesuaian Stok Button --}}
            <button type="button" onclick="document.getElementById('modal-penyesuaian').classList.remove('hidden')"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-secondary text-secondary-foreground text-xs font-bold hover:bg-secondary/90 transition-colors shadow-2xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Koreksi / Penyesuaian Stok</span>
            </button>

            {{-- Distribusi Shortcut --}}
            <a href="{{ route('distribusi.create', ['stok_gudang_id' => $stok->id]) }}"
                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-primary text-white text-xs font-bold shadow-md shadow-primary/20 hover:bg-primary/90 transition-transform active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <span>Distribusi Barang</span>
            </a>

            {{-- Delete Button --}}
            <button type="button" onclick="document.getElementById('modal-hapus-barang').classList.remove('hidden')"
                class="p-2.5 rounded-xl bg-destructive/10 text-destructive hover:bg-destructive hover:text-white transition-colors"
                title="Hapus Barang Ini dari Gudang">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Barang Header Summary Card --}}
    <div class="bg-card border border-border rounded-3xl p-6 sm:p-8 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-xl bg-primary/10 text-primary font-bold text-xs uppercase tracking-wider">
                        {{ $stok->barangMaster->kategori ?? 'Umum' }}
                    </span>
                    @if($stok->isKritis())
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-red-500/15 text-red-600 dark:text-red-400 font-bold text-xs">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                            Stok Kritis
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 font-bold text-xs">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Stok Aman
                        </span>
                    @endif
                </div>

                <div>
                    <h2 class="text-2xl sm:text-3xl font-black text-foreground">{{ $stok->barangMaster->nama_barang }}</h2>
                    @if(!empty($stok->barangMaster->spesifikasi))
                        <div class="mt-1 flex items-center gap-2">
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-lg bg-primary/10 text-primary border border-primary/20">
                                Spesifikasi: {{ $stok->barangMaster->spesifikasi }}
                            </span>
                        </div>
                    @endif
                    <p class="text-sm text-muted-foreground mt-1.5">
                        Batas Minimum Alert: <span class="font-bold text-foreground">{{ $stok->barangMaster->stok_minimum }} {{ $stok->barangMaster->satuan }}</span>
                        • Satuan: <span class="font-semibold text-foreground">{{ $stok->barangMaster->satuan }}</span>
                    </p>
                </div>

                @if($stok->catatan)
                    <div class="p-3.5 rounded-2xl bg-input/40 border border-border flex items-start gap-2.5 max-w-xl">
                        <svg class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-xs">
                            <span class="font-bold text-foreground block">Catatan / Keterangan Stok:</span>
                            <p class="text-muted-foreground mt-0.5 whitespace-pre-line">{{ $stok->catatan }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="p-5 rounded-2xl bg-input/50 border border-border text-center sm:text-right min-w-[170px]">
                    <span class="text-[11px] text-muted-foreground uppercase font-bold tracking-wider block">Sisa Stok Fisik</span>
                    <div class="mt-1">
                        <span class="text-3xl sm:text-4xl font-black {{ $stok->isKritis() ? 'text-red-600 dark:text-red-400' : 'text-primary' }}">
                            {{ $stok->stok_saat_ini }}
                        </span>
                        <span class="text-sm font-semibold text-muted-foreground ml-1">{{ $stok->barangMaster->satuan }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mutasi Stock Card Table Container --}}
    <div class="bg-card border border-border rounded-3xl overflow-hidden shadow-xs">
        <div class="px-6 py-5 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-muted/20">
            <div>
                <h3 class="text-base font-bold text-foreground">Kartu Stok & Riwayat Mutasi</h3>
                <p class="text-xs text-muted-foreground mt-0.5">Audit log mutasi masuk, keluar, distribusi, dan penyesuaian stok manual.</p>
            </div>
            <span class="px-3 py-1 rounded-xl bg-card border border-border text-xs font-semibold text-muted-foreground self-start sm:self-auto">
                {{ $stok->transaksis->count() }} transaksi tercatat
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-muted/50 border-b border-border text-muted-foreground uppercase text-xs font-semibold tracking-wider">
                        <th class="py-3.5 px-6 whitespace-nowrap">Tanggal</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Jenis Mutasi</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Jumlah</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Tipe Referensi</th>
                        <th class="py-3.5 px-6">Keterangan / Keperluan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                    @forelse($stok->transaksis as $trx)
                        <tr class="hover:bg-muted/20 transition-colors">
                            <td class="py-4 px-6 font-medium text-foreground whitespace-nowrap">
                                {{ $trx->tanggal_transaksi?->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($trx->jenis === 'masuk')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 font-bold text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                        </svg>
                                        Masuk (In)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-rose-500/15 text-rose-600 dark:text-rose-400 font-bold text-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                        </svg>
                                        Keluar (Out)
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 font-black whitespace-nowrap {{ $trx->jenis === 'masuk' ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $trx->jenis === 'masuk' ? '+' : '-' }}{{ $trx->jumlah }} {{ $stok->barangMaster->satuan }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-lg bg-muted text-xs font-mono font-medium text-muted-foreground">
                                    {{ $trx->referensi_type ?? 'manual' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-foreground text-sm">
                                {{ $trx->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-muted-foreground">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <p class="font-medium text-foreground">Belum ada riwayat mutasi untuk barang ini</p>
                                    <p class="text-xs">Catat mutasi masuk atau keluar untuk memperbarui kartu stok.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modal Edit Data Barang --}}
<div id="modal-edit-barang" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-card border border-border rounded-3xl max-w-md w-full shadow-2xl overflow-hidden animate-fade-in">
        <div class="flex items-center justify-between px-6 py-4 border-b border-border">
            <div>
                <h3 class="text-base font-bold text-foreground">Edit Data Barang</h3>
                <p class="text-xs text-muted-foreground mt-0.5">Ubah spesifikasi identitas barang master di inventaris.</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-edit-barang').classList.add('hidden')"
                class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('gudang.update', $stok) }}" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-foreground mb-1.5 flex items-center justify-between">
                    <span>Nama Barang <span class="text-destructive">*</span></span>
                    <span class="text-[10px] text-muted-foreground">Pilih saran atau ketik baru</span>
                </label>
                <input type="text" name="nama_barang" id="input-edit-nama" value="{{ old('nama_barang', $stok->barangMaster->nama_barang) }}" required list="datalist-edit-nama-barang" autocomplete="off"
                    oninput="updateEditPreview()"
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                <datalist id="datalist-edit-nama-barang">
                    @foreach($masterNamaList as $nama)
                        <option value="{{ $nama }}">{{ $nama }}</option>
                    @endforeach
                </datalist>
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground mb-1.5 flex items-center justify-between">
                    <span>Spesifikasi / Merk (Opsional)</span>
                    <span class="text-[10px] text-muted-foreground">Model, brand, kapasitas</span>
                </label>
                <input type="text" name="spesifikasi" id="input-edit-spek" value="{{ old('spesifikasi', $stok->barangMaster->spesifikasi) }}" list="datalist-edit-spesifikasi-barang" autocomplete="off"
                    placeholder="Contoh: Logitech M90, 512GB NVMe, DDR4 8GB..."
                    oninput="updateEditPreview()"
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                <datalist id="datalist-edit-spesifikasi-barang">
                    @foreach($masterSpesifikasiList as $spek)
                        <option value="{{ $spek }}">{{ $spek }}</option>
                    @endforeach
                </datalist>
            </div>

            <div class="p-2.5 rounded-xl bg-muted/40 border border-border/80 text-[11px] flex items-center justify-between gap-2">
                <span class="text-muted-foreground">📦 Format Lengkap Master:</span>
                <strong class="text-primary font-mono text-xs" id="preview-edit-lengkap">{{ $stok->barangMaster->nama_lengkap }}</strong>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-foreground mb-1.5">Kategori <span class="text-destructive">*</span></label>
                    <select name="kategori" required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        @php
                            $defaultKats = ['Komponen PC', 'Peripheral', 'Jaringan', 'Sarana & Prasarana', 'Lainnya'];
                            $currentKat = old('kategori', $stok->barangMaster->kategori);
                        @endphp
                        @foreach($defaultKats as $kat)
                            <option value="{{ $kat }}" {{ $currentKat === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground mb-1.5">Satuan <span class="text-destructive">*</span></label>
                    <select name="satuan" required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        @php
                            $satuans = ['unit', 'buah', 'keping', 'set', 'pasang', 'lembar', 'meter', 'rol'];
                            $currentSatuan = old('satuan', $stok->barangMaster->satuan);
                        @endphp
                        @foreach($satuans as $sat)
                            <option value="{{ $sat }}" {{ $currentSatuan === $sat ? 'selected' : '' }}>{{ $sat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground mb-1.5">Batas Stok Minimum Alert <span class="text-destructive">*</span></label>
                <input type="number" name="stok_minimum" value="{{ old('stok_minimum', $stok->barangMaster->stok_minimum) }}" min="0" required
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                <p class="text-[11px] text-muted-foreground mt-1">Sistem akan memberi tanda "Stok Kritis" jika stok saat ini &le; batas ini.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground mb-1.5">Keterangan / Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" rows="2" placeholder="Contoh: Kondisi segel baru, rak penyimpanan B2, dsb..."
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">{{ old('catatan', $stok->catatan) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-border">
                <button type="button" onclick="document.getElementById('modal-edit-barang').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl border border-border text-foreground text-sm font-semibold hover:bg-muted transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/25 hover:bg-primary/90 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Penyesuaian Stok Manual --}}
<div id="modal-penyesuaian" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-card border border-border rounded-3xl max-w-md w-full shadow-2xl overflow-hidden animate-fade-in">
        <div class="flex items-center justify-between px-6 py-4 border-b border-border">
            <div>
                <h3 class="text-base font-bold text-foreground">Koreksi / Penyesuaian Stok</h3>
                <p class="text-xs text-muted-foreground mt-0.5">Catat penyesuaian stok fisik secara manual.</p>
            </div>
            <button type="button" onclick="document.getElementById('modal-penyesuaian').classList.add('hidden')"
                class="p-2 text-muted-foreground hover:text-foreground hover:bg-muted rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form method="POST" action="{{ route('gudang.penyesuaian', $stok) }}" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-1.5">Jenis Penyesuaian <span class="text-destructive">*</span></label>
                <select name="jenis" required class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-semibold focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    <option value="masuk">Tambah Stok Fisik (Masuk / In)</option>
                    <option value="keluar">Kurangi Stok Fisik (Keluar / Rusak / Hilang)</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-1.5">Jumlah ({{ $stok->barangMaster->satuan }}) <span class="text-destructive">*</span></label>
                    <input type="number" name="jumlah" value="1" min="1" required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-bold focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-1.5">Tanggal <span class="text-destructive">*</span></label>
                    <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-1.5">Alasan / Keterangan Penyesuaian <span class="text-destructive">*</span></label>
                <textarea name="keterangan" rows="2" required placeholder="Contoh: Stok opname rutin, barang pecah saat pemindahan..."
                    class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-border">
                <button type="button" onclick="document.getElementById('modal-penyesuaian').classList.add('hidden')"
                    class="px-4 py-2.5 rounded-xl border border-border text-foreground text-sm font-semibold hover:bg-muted transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/25 hover:bg-primary/90 transition-colors">
                    Simpan Penyesuaian
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Konfirmasi Hapus Barang --}}
<div id="modal-hapus-barang" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
    <div class="bg-card border border-border rounded-3xl max-w-md w-full p-6 sm:p-7 space-y-5 shadow-2xl animate-fade-in">
        <div class="flex items-center gap-3 text-destructive">
            <div class="w-10 h-10 rounded-2xl bg-destructive/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-foreground">Hapus Barang dari Gudang?</h3>
                <p class="text-xs text-muted-foreground">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
        </div>

        <p class="text-xs text-foreground/80 leading-relaxed bg-destructive/5 p-3.5 rounded-2xl border border-destructive/15">
            Apakah Anda yakin ingin menghapus barang <strong class="text-foreground">"{{ $stok->barangMaster->nama_barang }}"</strong> beserta seluruh catatan riwayat mutasi kartunya?
        </p>

        <form method="POST" action="{{ route('gudang.destroy', $stok) }}" class="flex items-center justify-end gap-2.5 pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="document.getElementById('modal-hapus-barang').classList.add('hidden')"
                class="px-4 py-2.5 rounded-xl border border-border text-foreground text-sm font-semibold hover:bg-muted transition-colors">
                Batal
            </button>
            <button type="submit"
                class="px-5 py-2.5 rounded-xl bg-destructive text-white text-sm font-bold shadow-md shadow-destructive/25 hover:bg-destructive/90 transition-colors">
                Ya, Hapus Barang
            </button>
        </form>
    </div>
</div>

<script>
function updateEditPreview() {
    const nama = document.getElementById('input-edit-nama')?.value.trim() || '';
    const spek = document.getElementById('input-edit-spek')?.value.trim() || '';
    const preview = document.getElementById('preview-edit-lengkap');
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
