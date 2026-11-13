@extends('layouts.app')

@section('title', 'Input Laporan Baru')
@section('header_title', 'Input Laporan Tertulis dari Laboran')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-12">

    {{-- Back Button --}}
    <a href="{{ route('laporan.index') }}"
        class="inline-flex items-center gap-2 text-sm font-semibold text-muted-foreground hover:text-primary transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Rekap Laporan</span>
    </a>

    {{-- Form Card --}}
    <form method="POST" action="{{ route('laporan.store') }}" class="bg-card border border-border rounded-3xl shadow-xs overflow-hidden">
        @csrf

        {{-- Section 1: Identitas & Asal Laporan --}}
        <div class="p-6 sm:p-8 space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-border">
                <span class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-black flex-shrink-0">1</span>
                <div>
                    <h3 class="text-base font-bold text-foreground">Identitas & Pelapor</h3>
                    <p class="text-sm text-muted-foreground">Data laboratorium dan identitas pihak yang melaporkan.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Lab Asal --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Laboratorium Asal <span class="text-destructive">*</span>
                    </label>
                    <select name="lab_nama" required
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-semibold focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                        <option value="">— Pilih Laboratorium —</option>
                        @foreach($labs as $lab)
                            <option value="{{ $lab }}" {{ old('lab_nama') === $lab ? 'selected' : '' }}>Lab {{ $lab }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama Pelapor --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Nama Pelapor
                    </label>
                    <input type="text" name="nama_pelapor" value="{{ old('nama_pelapor') }}"
                        placeholder="Contoh: Octaviana / Bambang"
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                </div>

                {{-- Jabatan Pelapor (Dropdown) --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Jabatan Pelapor
                    </label>
                    <select name="jabatan_pelapor"
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                        <option value="">— Pilih Jabatan —</option>
                        @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan }}" {{ old('jabatan_pelapor') === $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                {{-- Tanggal Kejadian --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Tanggal Kejadian / Ditemukan Masalah
                    </label>
                    <input type="date" name="tanggal_kejadian"
                        value="{{ old('tanggal_kejadian') }}"
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-medium focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                </div>

                {{-- Tanggal Laporan Diterima --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Tanggal Laporan Diterima Admin <span class="text-destructive">*</span>
                    </label>
                    <input type="date" name="tanggal_laporan"
                        value="{{ old('tanggal_laporan', date('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm font-medium focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
                </div>
            </div>
        </div>

        {{-- Section 2: Form Permintaan Tindakan Perbaikan & Pencegahan --}}
        <div class="p-6 sm:p-8 border-t border-border space-y-6">
            <div class="flex items-center gap-3 pb-4 border-b border-border">
                <span class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-black flex-shrink-0">2</span>
                <div>
                    <h3 class="text-base font-bold text-foreground">Isian Form Permintaan Tindakan (Fisik)</h3>
                    <p class="text-sm text-muted-foreground">Detail ketidaksesuaian, pengamatan, dan tindakan langsung yang sudah dicoba.</p>
                </div>
            </div>

            {{-- Bentuk Ketidaksesuaian --}}
            <div>
                <label class="block text-sm font-semibold text-foreground mb-2">
                    Bentuk Ketidaksesuaian / Pokok Masalah
                </label>
                <input type="text" name="bentuk_ketidaksesuaian" value="{{ old('bentuk_ketidaksesuaian') }}"
                    placeholder="Contoh: PC tidak mau menyala, Keyboard tidak terdeteksi di beberapa unit"
                    class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors">
            </div>

            {{-- Uraian Pengamatan --}}
            <div>
                <label class="block text-sm font-semibold text-foreground mb-2">
                    Hasil / Uraian Pengamatan
                </label>
                <textarea name="uraian_pengamatan" rows="3"
                    placeholder="Deskripsi detail hasil pengamatan kondisi saat masalah terjadi..."
                    class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors resize-none">{{ old('uraian_pengamatan') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Tindakan Langsung yang Sudah Dicoba --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Tindakan Langsung yang Sudah Dicoba
                        <span class="text-muted-foreground font-normal text-xs">(oleh Laboran)</span>
                    </label>
                    <textarea name="tindakan_langsung" rows="3"
                        placeholder="Contoh: Sudah dicoba restart, ganti kabel power, cek port..."
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors resize-none">{{ old('tindakan_langsung') }}</textarea>
                </div>

                {{-- Permintaan Tindakan Perbaikan & Pencegahan --}}
                <div>
                    <label class="block text-sm font-semibold text-foreground mb-2">
                        Permintaan Tindakan Perbaikan & Pencegahan
                    </label>
                    <textarea name="permintaan_tindakan" rows="3"
                        placeholder="Contoh: Mohon perbaikan teknisi atau penggantian unit monitor baru..."
                        class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors resize-none">{{ old('permintaan_tindakan') }}</textarea>
                </div>
            </div>

            {{-- Catatan Umum / Internal Admin --}}
            <div>
                <label class="block text-sm font-semibold text-foreground mb-2">
                    Catatan Umum / Tambahan Admin
                    <span class="text-muted-foreground font-normal">(Opsional)</span>
                </label>
                <textarea name="catatan_umum" rows="2"
                    placeholder="Catatan internal admin atau catatan umum lab..."
                    class="w-full px-4 py-2.5 rounded-xl bg-input border border-border text-foreground text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary transition-colors resize-none">{{ old('catatan_umum') }}</textarea>
            </div>
        </div>

        {{-- Section 3: Item Barang Bermasalah --}}
        <div class="p-6 sm:p-8 border-t border-border space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center text-sm font-black flex-shrink-0">3</span>
                    <div>
                        <h3 class="text-base font-bold text-foreground">Daftar Item Barang Bermasalah</h3>
                        <p class="text-sm text-muted-foreground">Satu laporan dapat memiliki beberapa item kerusakan.</p>
                    </div>
                </div>

                <button type="button" onclick="addItemRow()"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary/10 text-primary text-sm font-bold hover:bg-primary hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>+ Tambah Item</span>
                </button>
            </div>

            {{-- Container for dynamic items --}}
            <div id="items-container" class="space-y-4">
                {{-- Item Row Default --}}
                <div class="item-row border border-border rounded-2xl overflow-hidden shadow-xs" data-index="0">
                    <div class="flex items-center justify-between px-5 py-3 bg-muted/40 border-b border-border">
                        <span class="text-sm font-bold text-primary item-number">Item #1</span>
                        <button type="button" onclick="removeItemRow(this)"
                            class="text-muted-foreground hover:text-destructive text-sm font-semibold transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Hapus
                        </button>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-muted-foreground uppercase mb-1.5">Nama Barang Bermasalah <span class="text-destructive">*</span></label>
                                <input type="text" name="items[0][nama_barang]" required
                                    placeholder="Monitor LED 21.5 Inch, RAM 8GB, Keyboard..."
                                    class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-muted-foreground uppercase mb-1.5">Jumlah <span class="text-destructive">*</span></label>
                                <input type="number" name="items[0][jumlah]" value="1" min="1" required
                                    class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground font-bold focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-muted-foreground uppercase mb-1.5">Satuan <span class="text-destructive">*</span></label>
                                <select name="items[0][satuan]" required
                                    class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
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

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-muted-foreground uppercase mb-1.5">Deskripsi Kerusakan</label>
                                <input type="text" name="items[0][deskripsi]"
                                    placeholder="Mati total, layar bergaris di PC 08, klip patah..."
                                    class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-muted-foreground uppercase mb-1.5">Tindak Lanjut Awal</label>
                                <select name="items[0][jenis_tindak_lanjut]"
                                    class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground font-semibold focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                                    <option value="pending">Pending (Menunggu Triase)</option>
                                    <option value="tindak_ulang">Tindak Ulang Laboran (Instruksi Di Tempat)</option>
                                    <option value="perbaikan">Perbaikan (Teknisi / Vendor)</option>
                                    <option value="penggantian">Penggantian (Stok / Pengadaan)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-muted-foreground uppercase mb-1.5">Keterangan / Catatan Tindak Lanjut <span class="text-muted-foreground font-normal normal-case">(Opsional)</span></label>
                            <input type="text" name="items[0][keterangan]"
                                placeholder="Catatan jika sudah ada rencana perbaikan (teknisi, estimasi, alasan)..."
                                class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="px-6 sm:px-8 py-5 border-t border-border bg-muted/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <p class="text-sm text-muted-foreground">Pastikan semua item dari laporan tertulis sudah diinput.</p>
            <div class="flex items-center gap-3">
                <a href="{{ route('laporan.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-border text-foreground text-sm font-semibold hover:bg-muted transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-bold shadow-lg shadow-primary/25 hover:bg-primary/90 transition-transform active:scale-95">
                    Simpan Laporan
                </button>
            </div>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    let itemIndex = 1;

    function addItemRow() {
        const container = document.getElementById('items-container');
        const newRow    = document.createElement('div');
        newRow.className = 'item-row border border-border rounded-2xl overflow-hidden animate-fade-in';
        newRow.dataset.index = itemIndex;

        newRow.innerHTML = `
            <div class="flex items-center justify-between px-4 py-2.5 bg-muted/50 border-b border-border">
                <span class="text-sm font-bold text-primary item-number">Item #${itemIndex + 1}</span>
                <button type="button" onclick="removeItemRow(this)"
                    class="text-muted-foreground hover:text-destructive text-sm font-semibold transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Hapus
                </button>
            </div>
            <div class="p-4 space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-muted-foreground uppercase mb-1.5">Nama Barang Bermasalah *</label>
                        <input type="text" name="items[${itemIndex}][nama_barang]" required
                            placeholder="Monitor LED 21.5 Inch, RAM 8GB, Keyboard..."
                            class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-muted-foreground uppercase mb-1.5">Jumlah *</label>
                        <input type="number" name="items[${itemIndex}][jumlah]" value="1" min="1" required
                            class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground font-bold focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-muted-foreground uppercase mb-1.5">Satuan *</label>
                        <select name="items[${itemIndex}][satuan]" required
                            class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
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
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-muted-foreground uppercase mb-1.5">Deskripsi Kerusakan</label>
                        <input type="text" name="items[${itemIndex}][deskripsi]"
                            placeholder="Mati total, layar bergaris di PC 08, klip patah..."
                            class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-muted-foreground uppercase mb-1.5">Tindak Lanjut Awal</label>
                        <select name="items[${itemIndex}][jenis_tindak_lanjut]"
                            class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground font-semibold focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                            <option value="pending">Pending (Menunggu Triase)</option>
                            <option value="tindak_ulang">Tindak Ulang Laboran (Instruksi Di Tempat)</option>
                            <option value="perbaikan">Perbaikan (Teknisi / Vendor)</option>
                            <option value="penggantian">Penggantian (Stok / Pengadaan)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-muted-foreground uppercase mb-1.5">Keterangan / Catatan (Opsional)</label>
                    <input type="text" name="items[${itemIndex}][keterangan]"
                        placeholder="Catatan jika sudah ada rencana perbaikan..."
                        class="w-full px-3 py-2.5 rounded-xl bg-input border border-border text-sm text-foreground focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </div>
            </div>
        `;

        container.appendChild(newRow);
        itemIndex++;
        updateRowNumbers();
        // Scroll to new item
        newRow.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function removeItemRow(button) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length <= 1) {
            alert('Minimal harus ada 1 item barang bermasalah.');
            return;
        }
        button.closest('.item-row').remove();
        updateRowNumbers();
    }

    function updateRowNumbers() {
        document.querySelectorAll('.item-row').forEach((row, i) => {
            row.querySelector('.item-number').textContent = `Item #${i + 1}`;
        });
    }
</script>
@endpush
