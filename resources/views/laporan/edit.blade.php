@extends('layouts.app')

@section('title', 'Edit Laporan #' . $laporan->id)
@section('header_title', 'Edit Informasi Laporan Lab ' . $laporan->lab_nama)

@section('content')
<div class="max-w-3xl mx-auto space-y-8 pb-12">

    <a href="{{ route('laporan.show', $laporan) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-muted-foreground hover:text-primary transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Detail Laporan</span>
    </a>

    <form method="POST" action="{{ route('laporan.update', $laporan) }}" class="bg-card border border-border rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
        @csrf
        @method('PUT')

        <div class="border-b border-border pb-4">
            <h3 class="text-base font-bold text-foreground">Edit Informasi Utama Laporan</h3>
            <p class="text-xs text-muted-foreground mt-0.5">Perbarui informasi pelapor, isian form fisik, tanggal, dan status laporan.</p>
        </div>

        {{-- Section 1: Identitas & Pelapor --}}
        <div class="space-y-4">
            <h4 class="text-xs font-bold text-primary uppercase tracking-wider">1. Identitas & Pelapor</h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Laboratorium</label>
                    <select name="lab_nama" required class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm font-semibold focus:ring-2 focus:ring-primary/40">
                        @foreach($labs as $lab)
                            <option value="{{ $lab }}" {{ old('lab_nama', $laporan->lab_nama) === $lab ? 'selected' : '' }}>Lab {{ $lab }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Nama Pelapor</label>
                    <input type="text" name="nama_pelapor" value="{{ old('nama_pelapor', $laporan->nama_pelapor) }}"
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Jabatan Pelapor</label>
                    <select name="jabatan_pelapor" class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40">
                        <option value="">— Pilih Jabatan —</option>
                        @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan }}" {{ old('jabatan_pelapor', $laporan->jabatan_pelapor) === $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Section 2: Isian Form Fisik --}}
        <div class="space-y-4 pt-4 border-t border-border">
            <h4 class="text-xs font-bold text-primary uppercase tracking-wider">2. Isian Form Permintaan Tindakan</h4>
            
            <div>
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Bentuk Ketidaksesuaian / Pokok Masalah</label>
                <input type="text" name="bentuk_ketidaksesuaian" value="{{ old('bentuk_ketidaksesuaian', $laporan->bentuk_ketidaksesuaian) }}"
                    class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40">
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Hasil / Uraian Pengamatan</label>
                <textarea name="uraian_pengamatan" rows="3" class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40">{{ old('uraian_pengamatan', $laporan->uraian_pengamatan) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Tindakan Langsung yang Sudah Dicoba</label>
                    <textarea name="tindakan_langsung" rows="3" class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40">{{ old('tindakan_langsung', $laporan->tindakan_langsung) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Permintaan Tindakan Perbaikan & Pencegahan</label>
                    <textarea name="permintaan_tindakan" rows="3" class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40">{{ old('permintaan_tindakan', $laporan->permintaan_tindakan) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Section 3: Tanggal & Status --}}
        <div class="space-y-4 pt-4 border-t border-border">
            <h4 class="text-xs font-bold text-primary uppercase tracking-wider">3. Tanggal & Status Laporan</h4>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Tanggal Kejadian</label>
                    <input type="date" name="tanggal_kejadian" value="{{ old('tanggal_kejadian', $laporan->tanggal_kejadian?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm font-medium focus:ring-2 focus:ring-primary/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Tanggal Laporan Masuk</label>
                    <input type="date" name="tanggal_laporan" value="{{ old('tanggal_laporan', $laporan->tanggal_laporan?->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm font-medium focus:ring-2 focus:ring-primary/40">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Tanggal Selesai Ditangani</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $laporan->tanggal_selesai?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm font-medium focus:ring-2 focus:ring-primary/40">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Status Keseluruhan Laporan</label>
                    <select name="status" required class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm font-semibold focus:ring-2 focus:ring-primary/40">
                        <option value="dicatat" {{ old('status', $laporan->status) === 'dicatat' ? 'selected' : '' }}>Dicatat</option>
                        <option value="ditriase" {{ old('status', $laporan->status) === 'ditriase' ? 'selected' : '' }}>Ditriase</option>
                        <option value="dalam_proses" {{ old('status', $laporan->status) === 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                        <option value="menunggu_keputusan_divisi" {{ old('status', $laporan->status) === 'menunggu_keputusan_divisi' ? 'selected' : '' }}>Menunggu Keputusan Divisi</option>
                        <option value="barang_diterima" {{ old('status', $laporan->status) === 'barang_diterima' ? 'selected' : '' }}>Barang Diterima</option>
                        <option value="siap_distribusi" {{ old('status', $laporan->status) === 'siap_distribusi' ? 'selected' : '' }}>Siap Distribusi</option>
                        <option value="selesai" {{ old('status', $laporan->status) === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ old('status', $laporan->status) === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="perlu_revisi" {{ old('status', $laporan->status) === 'perlu_revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Status Akhir Form (Closed / Follow Up)</label>
                    <select name="status_akhir" class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm font-semibold focus:ring-2 focus:ring-primary/40">
                        <option value="">— Belum Ditentukan —</option>
                        <option value="closed" {{ old('status_akhir', $laporan->status_akhir) === 'closed' ? 'selected' : '' }}>Closed (Selesai Tuntas)</option>
                        <option value="follow_up" {{ old('status_akhir', $laporan->status_akhir) === 'follow_up' ? 'selected' : '' }}>Follow Up (Perlu Tindak Lanjut Tambahan)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">Catatan Umum / Tambahan Admin</label>
                <textarea name="catatan_umum" rows="2" class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40">{{ old('catatan_umum', $laporan->catatan_umum) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
            <a href="{{ route('laporan.show', $laporan) }}" class="px-5 py-2.5 rounded-2xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-2xl bg-primary text-white text-xs font-bold shadow-lg shadow-primary/25 hover:bg-primary/90 transition-colors">
                Perbarui Informasi
            </button>
        </div>
    </form>
</div>
@endsection
