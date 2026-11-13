@extends('layouts.app')

@section('title', 'Detail Barang Tertinggal - ' . $barang_tertinggal->nama_barang)
@section('header_title', 'Detail Barang Tertinggal')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 pb-12">

    {{-- Top Navigation & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <a href="{{ route('barang-tertinggal.index') }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-muted-foreground hover:text-primary transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Daftar Barang Tertinggal</span>
        </a>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('barang-tertinggal.edit', $barang_tertinggal) }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-border bg-card text-foreground hover:bg-muted font-semibold text-sm transition-colors shadow-xs">
                <svg class="w-4 h-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>Edit Data</span>
            </a>

            <form method="POST" action="{{ route('barang-tertinggal.destroy', $barang_tertinggal) }}"
                onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan barang ini?');"
                class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-destructive/30 bg-destructive/10 text-destructive hover:bg-destructive/20 font-semibold text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Hapus</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Main Grid: 2 Columns --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Main Detail Information (2 Cols) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Main Info Card --}}
            <div class="bg-card border border-border rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                {{-- Header Item --}}
                <div class="flex flex-wrap items-start justify-between gap-4 pb-6 border-b border-border">
                    <div>
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <span class="font-mono text-xs font-bold text-muted-foreground px-2 py-0.5 rounded-md bg-muted">
                                #BT-{{ str_pad($barang_tertinggal->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                            <x-status-badge :status="$barang_tertinggal->status" />
                        </div>
                        <h2 class="text-2xl font-black text-foreground">{{ $barang_tertinggal->nama_barang }}</h2>
                        <div class="flex items-center gap-2 text-xs text-muted-foreground mt-1">
                            <span>Dicatat pada {{ $barang_tertinggal->created_at?->format('d F Y, H:i') }}</span>
                            @if($barang_tertinggal->updated_at != $barang_tertinggal->created_at)
                                <span>• Diperbarui {{ $barang_tertinggal->updated_at?->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Lokasi Temuan</div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-primary/10 text-primary font-bold text-sm mt-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $barang_tertinggal->lab_nama }}</span>
                        </div>
                    </div>
                </div>

                {{-- Detail Meta Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground mb-1">Tanggal Ditemukan</p>
                        <p class="text-base font-bold text-foreground">{{ $barang_tertinggal->tanggal_ditemukan?->translatedFormat('l, d F Y') ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground mb-1">Ditemukan Oleh</p>
                        <p class="text-base font-bold text-foreground">{{ $barang_tertinggal->penemu }}</p>
                    </div>
                </div>

                {{-- Ciri-ciri / Keterangan --}}
                <div class="pt-4 border-t border-border">
                    <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground mb-2">Ciri-ciri / Merk / Keterangan Khusus</p>
                    <div class="p-4 rounded-2xl bg-muted/40 border border-border text-sm text-foreground leading-relaxed whitespace-pre-line">
                        {{ $barang_tertinggal->keterangan ?: 'Tidak ada keterangan ciri fisik khusus.' }}
                    </div>
                </div>

                {{-- Catatan Tambahan --}}
                @if($barang_tertinggal->catatan)
                    <div class="pt-4 border-t border-border">
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground mb-1">Catatan Tempat Penyimpanan / Lainnya</p>
                        <p class="text-sm text-foreground">{{ $barang_tertinggal->catatan }}</p>
                    </div>
                @endif
            </div>

            {{-- Pemilik & Pengambilan Card --}}
            <div class="bg-card border border-border rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                <h3 class="text-base font-bold text-foreground pb-4 border-b border-border flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Informasi Pemilik & Penyerahan</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Pemilik --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground mb-1">Nama Pemilik</p>
                        @if($barang_tertinggal->pemilik)
                            <p class="text-base font-bold text-foreground">{{ $barang_tertinggal->pemilik }}</p>
                        @else
                            <p class="text-sm text-muted-foreground italic">Belum diketahui / belum teridentifikasi</p>
                        @endif
                    </div>

                    {{-- No HP --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground mb-1">Kontak Pemilik (No HP / WhatsApp)</p>
                        @if($barang_tertinggal->no_hp)
                            <div class="flex items-center gap-3 mt-1">
                                <span class="font-bold text-base text-foreground">{{ $barang_tertinggal->no_hp }}</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $barang_tertinggal->no_hp) }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-500/10 text-emerald-600 hover:bg-emerald-500/20 dark:text-emerald-400 text-xs font-bold transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span>Hubungi WhatsApp</span>
                                </a>
                            </div>
                        @else
                            <p class="text-sm text-muted-foreground italic">Tidak ada data kontak</p>
                        @endif
                    </div>

                    {{-- Tanggal Pengambilan --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground mb-1">Tanggal Pengambilan / Penyerahan</p>
                        @if($barang_tertinggal->tanggal_pengambilan)
                            <p class="text-base font-bold text-foreground">{{ $barang_tertinggal->tanggal_pengambilan->translatedFormat('l, d F Y') }}</p>
                        @else
                            <p class="text-sm text-muted-foreground italic">Belum diambil</p>
                        @endif
                    </div>

                    {{-- Penyerah --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground mb-1">Petugas Penyerah</p>
                        @if($barang_tertinggal->penyerah)
                            <p class="text-base font-bold text-foreground">{{ $barang_tertinggal->penyerah }}</p>
                        @else
                            <p class="text-sm text-muted-foreground italic">—</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- Right: Quick Status Update Panel --}}
        <div class="space-y-6">
            <div class="bg-card border border-border rounded-3xl p-6 sm:p-7 shadow-xs space-y-5">
                <div>
                    <h3 class="text-base font-bold text-foreground">Perbarui Status Barang</h3>
                    <p class="text-xs text-muted-foreground mt-0.5">Ubah status alur barang secara instan tanpa masuk form edit lengkap.</p>
                </div>

                <form method="POST" action="{{ route('barang-tertinggal.status', $barang_tertinggal) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Pilih Status Baru</label>
                        <select name="status" id="quick_status" required
                            class="w-full px-3.5 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-semibold focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                            @foreach($statuses as $val => $lbl)
                                <option value="{{ $val }}" {{ $barang_tertinggal->status === $val ? 'selected' : '' }}>
                                    {{ $lbl }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Conditional fields when status changed to sudah_diambil or diserahkan_ke_satpam --}}
                    <div id="quick_fields" class="space-y-3 pt-2 border-t border-border {{ in_array($barang_tertinggal->status, ['sudah_diambil', 'diserahkan_ke_satpam']) ? '' : 'hidden' }}">
                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Tanggal Penyerahan</label>
                            <input type="date" name="tanggal_pengambilan"
                                value="{{ $barang_tertinggal->tanggal_pengambilan?->format('Y-m-d') ?? date('Y-m-d') }}"
                                class="w-full px-3.5 py-2 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Nama Petugas Penyerah</label>
                            <input type="text" name="penyerah" value="{{ $barang_tertinggal->penyerah ?? Auth::user()->name }}"
                                placeholder="Nama yang menyerahkan barang"
                                class="w-full px-3.5 py-2 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Nama Penerima / Pemilik</label>
                            <input type="text" name="pemilik" value="{{ $barang_tertinggal->pemilik }}"
                                placeholder="Nama yang menerima barang"
                                class="w-full px-3.5 py-2 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground mb-1.5">No. HP Penerima</label>
                            <input type="text" name="no_hp" value="{{ $barang_tertinggal->no_hp }}"
                                placeholder="08..."
                                class="w-full px-3.5 py-2 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-muted-foreground mb-1.5">Catatan Perubahan (Opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Catatan tambahan saat status diubah..."
                            class="w-full px-3.5 py-2 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">{{ $barang_tertinggal->catatan }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 px-4 rounded-xl bg-primary text-white font-bold text-sm shadow-md shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Perbarui Status</span>
                    </button>
                </form>
            </div>

            {{-- Flow Info Help Card --}}
            <div class="bg-card border border-border rounded-3xl p-5 shadow-xs text-xs space-y-3">
                <div class="font-bold text-foreground flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Panduan Alur Status:</span>
                </div>
                <ul class="space-y-2 text-muted-foreground">
                    <li class="flex items-start gap-2">
                        <span class="text-amber-500 font-bold">1.</span>
                        <span><strong>Belum Diambil:</strong> Barang baru ditemukan & disimpan sementara di ruang lab.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-sky-500 font-bold">2.</span>
                        <span><strong>Diumumkan:</strong> Info barang sudah dishare ke grup kelas/mading lab/broadcast WA.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-emerald-500 font-bold">3.</span>
                        <span><strong>Sudah Diambil:</strong> Pemilik telah datang memvalidasi ciri-ciri dan mengambil barang.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-slate-500 font-bold">4.</span>
                        <span><strong>Ke Satpam:</strong> Barang tidak diambil dalam batas waktu tertentu dan dilimpahkan ke pos Satpam Udinus.</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quickStatus = document.getElementById('quick_status');
        const quickFields = document.getElementById('quick_fields');

        function checkQuickStatus() {
            const val = quickStatus.value;
            if (val === 'sudah_diambil' || val === 'diserahkan_ke_satpam') {
                quickFields.classList.remove('hidden');
            } else {
                quickFields.classList.add('hidden');
            }
        }

        quickStatus.addEventListener('change', checkQuickStatus);
    });
</script>
@endsection
