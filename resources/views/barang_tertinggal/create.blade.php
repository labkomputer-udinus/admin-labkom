@extends('layouts.app')

@section('title', 'Catat Barang Tertinggal Baru')
@section('header_title', 'Catat Barang Tertinggal')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    {{-- Back Link --}}
    <a href="{{ route('barang-tertinggal.index') }}"
        class="inline-flex items-center gap-2 text-sm font-semibold text-muted-foreground hover:text-primary transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Daftar Barang Tertinggal</span>
    </a>

    {{-- Form Card --}}
    <form method="POST" action="{{ route('barang-tertinggal.store') }}" class="bg-card border border-border rounded-3xl shadow-xs overflow-hidden">
        @csrf

        {{-- Section 1: Lokasi & Temuan --}}
        <div class="p-6 sm:p-8 space-y-6 border-b border-border">
            <div class="flex items-center gap-3 pb-4 border-b border-border">
                <span class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-black flex-shrink-0">1</span>
                <div>
                    <h3 class="text-base font-bold text-foreground">Lokasi Temuan & Waktu</h3>
                    <p class="text-sm text-muted-foreground">Informasi kapan dan di mana barang tertinggal ditemukan.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Tanggal Ditemukan --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Tanggal Ditemukan <span class="text-destructive">*</span>
                    </label>
                    <input type="date" name="tanggal_ditemukan"
                        value="{{ old('tanggal_ditemukan', date('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-medium focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors @error('tanggal_ditemukan') border-destructive @enderror">
                    @error('tanggal_ditemukan')
                        <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Penemu --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Nama Penemu <span class="text-destructive">*</span>
                    </label>
                    <input type="text" name="penemu" value="{{ old('penemu') }}" required
                        placeholder="Contoh: Budi Santoso (Mahasiswa / Laboran / CS)"
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors @error('penemu') border-destructive @enderror">
                    @error('penemu')
                        <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Lab / Lokasi Temuan --}}
            <div class="space-y-3">
                <label class="block text-sm font-semibold text-foreground">
                    Laboratorium / Lokasi Ditemukan <span class="text-destructive">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <select name="lab_pilihan" id="lab_pilihan" required
                            class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-semibold focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                            <option value="">— Pilih Laboratorium / Lokasi —</option>
                            @foreach($labs as $lab)
                                <option value="{{ $lab }}" {{ old('lab_pilihan') === $lab ? 'selected' : '' }}>Lab {{ $lab }}</option>
                            @endforeach
                            <option value="Lainnya" {{ old('lab_pilihan') === 'Lainnya' ? 'selected' : '' }}>-- Lainnya (Input Manual) --</option>
                        </select>
                    </div>

                    {{-- Text input for "Lainnya" --}}
                    <div id="wrapper_lab_lainnya" class="{{ old('lab_pilihan') === 'Lainnya' ? '' : 'hidden' }}">
                        <input type="text" name="lab_lainnya" id="lab_lainnya"
                            value="{{ old('lab_lainnya') }}"
                            placeholder="Ketik lokasi spesifik, misal: Koridor Lantai 2 Gedung D"
                            class="w-full px-4 py-2.5 rounded-xl bg-input border border-primary/50 text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                        <p class="text-xs text-muted-foreground mt-1">Sebutkan area penemuan jika di luar lab komputer.</p>
                    </div>
                </div>
                @error('lab_pilihan')
                    <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Section 2: Informasi Barang --}}
        <div class="p-6 sm:p-8 space-y-6 border-b border-border">
            <div class="flex items-center gap-3 pb-4 border-b border-border">
                <span class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-black flex-shrink-0">2</span>
                <div>
                    <h3 class="text-base font-bold text-foreground">Identifikasi Barang</h3>
                    <p class="text-sm text-muted-foreground">Deskripsi detail barang yang tertinggal untuk memudahkan verifikasi pemilik.</p>
                </div>
            </div>

            <div class="space-y-4">
                {{-- Nama Barang --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Nama Barang Tertinggal <span class="text-destructive">*</span>
                    </label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required
                        placeholder="Contoh: Flashdisk SanDisk 32GB / Jaket Parka Hitam / Tumbler Stainless"
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-medium focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors @error('nama_barang') border-destructive @enderror">
                    @error('nama_barang')
                        <p class="text-xs text-destructive mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Merk / Type / Keterangan Ciri-ciri --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Merk / Type / Keterangan Ciri Khusus
                    </label>
                    <textarea name="keterangan" rows="3"
                        placeholder="Contoh: Warna merah hitam, ada gantungan kunci karakter anime, terdapat stiker nama 'Dian' di bagian belakang, dsb."
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">{{ old('keterangan') }}</textarea>
                    <p class="text-xs text-muted-foreground mt-1">Ciri-ciri fisik yang bisa dijadikan pertanyaan validasi saat pemilik mengklaim.</p>
                </div>
            </div>
        </div>

        {{-- Section 3: Status & Pemilik --}}
        <div class="p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-border">
                <span class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-black flex-shrink-0">3</span>
                <div>
                    <h3 class="text-base font-bold text-foreground">Status & Data Pemilik (Jika Ada)</h3>
                    <p class="text-sm text-muted-foreground">Status awal barang serta kontak pemilik jika sudah diketahui / teridentifikasi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Status Barang <span class="text-destructive">*</span>
                    </label>
                    <select name="status" id="status_select" required
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-semibold focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                        @foreach($statuses as $val => $lbl)
                            <option value="{{ $val }}" {{ old('status', 'belum_diambil') === $val ? 'selected' : '' }}>
                                {{ $lbl }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Pemilik --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Nama Pemilik <span class="text-xs text-muted-foreground font-normal">(Opsional)</span>
                    </label>
                    <input type="text" name="pemilik" value="{{ old('pemilik') }}"
                        placeholder="Nama pemilik jika diketahui"
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                </div>

                {{-- No HP / WA --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        No. HP / WA Pemilik <span class="text-xs text-muted-foreground font-normal">(Opsional)</span>
                    </label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                        placeholder="Contoh: 08123456789"
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                </div>
            </div>

            {{-- Fields Pengambilan (Conditional) --}}
            <div id="pengambilan_fields" class="p-4 rounded-2xl bg-muted/40 border border-border space-y-4 {{ in_array(old('status'), ['sudah_diambil', 'diserahkan_ke_satpam']) ? '' : 'hidden' }}">
                <div class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Informasi Penyerahan / Pengambilan</div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-foreground mb-1.5">
                            Tanggal Pengambilan / Penyerahan
                        </label>
                        <input type="date" name="tanggal_pengambilan" value="{{ old('tanggal_pengambilan') }}"
                            class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-foreground mb-1.5">
                            Nama Petugas Penyerah
                        </label>
                        <input type="text" name="penyerah" value="{{ old('penyerah') }}"
                            placeholder="Petugas yang menyerahkan barang"
                            class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                    </div>
                </div>
            </div>

            {{-- Catatan Tambahan --}}
            <div>
                <label class="block text-sm font-semibold text-foreground mb-2">
                    Catatan Tambahan <span class="text-xs text-muted-foreground font-normal">(Opsional)</span>
                </label>
                <input type="text" name="catatan" value="{{ old('catatan') }}"
                    placeholder="Contoh: Disimpan di laci meja laboran ruang D2A"
                    class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="p-6 bg-muted/30 border-t border-border flex items-center justify-end gap-3">
            <a href="{{ route('barang-tertinggal.index') }}"
                class="px-5 py-2.5 rounded-xl border border-border bg-card text-foreground hover:bg-muted font-medium text-sm transition-colors">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary text-white font-bold text-sm shadow-md shadow-primary/20 hover:bg-primary/90 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>Simpan Catatan Barang</span>
            </button>
        </div>
    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const labSelect = document.getElementById('lab_pilihan');
        const labLainnyaWrapper = document.getElementById('wrapper_lab_lainnya');
        const labLainnyaInput = document.getElementById('lab_lainnya');
        const statusSelect = document.getElementById('status_select');
        const pengambilanFields = document.getElementById('pengambilan_fields');

        function toggleLabLainnya() {
            if (labSelect.value === 'Lainnya') {
                labLainnyaWrapper.classList.remove('hidden');
                labLainnyaInput.focus();
            } else {
                labLainnyaWrapper.classList.add('hidden');
            }
        }

        function togglePengambilan() {
            const val = statusSelect.value;
            if (val === 'sudah_diambil' || val === 'diserahkan_ke_satpam') {
                pengambilanFields.classList.remove('hidden');
            } else {
                pengambilanFields.classList.add('hidden');
            }
        }

        labSelect.addEventListener('change', toggleLabLainnya);
        statusSelect.addEventListener('change', togglePengambilan);
    });
</script>
@endsection
