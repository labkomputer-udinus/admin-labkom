@extends('layouts.app')

@section('title', 'Verifikasi Penerimaan — ' . $pengajuan->nomor_pengajuan)
@section('header_title', 'Verifikasi Penerimaan Barang')

@section('content')
<div class="max-w-4xl mx-auto flex flex-col gap-6 pb-10">

    <a href="{{ route('pengajuan.show', $pengajuan) }}" class="inline-flex items-center gap-2 text-xs font-semibold text-muted-foreground hover:text-primary transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Detail Pengajuan</span>
    </a>

    <div class="bg-card border border-border rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
        <div class="border-b border-border pb-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-xs font-mono font-bold text-primary">Verifikasi Fisik Barang</span>
                    <h2 class="text-xl font-bold text-foreground mt-0.5">Pengajuan {{ $pengajuan->nomor_pengajuan }}</h2>
                    <p class="text-xs text-muted-foreground">Divisi Tujuan: {{ $pengajuan->divisi_tujuan }} • Tanggal Ajuan: {{ $pengajuan->tanggal_ajuan?->format('d M Y') }}</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-teal-500/10 text-teal-600 dark:text-teal-400 font-bold text-xs">
                    Mode Verifikasi
                </span>
            </div>
            <div class="text-xs text-foreground/80 mt-3 p-3.5 rounded-2xl bg-teal-500/10 border border-teal-500/20 space-y-1">
                <p>💡 <strong>Aturan Otomatisasi Stok Gudang:</strong></p>
                <ul class="list-disc list-inside space-y-0.5 text-muted-foreground text-[11px]">
                    <li><strong>Sesuai Penuh:</strong> Barang langsung masuk ke stok master yang diajukan.</li>
                    <li><strong>Sesuai Beda Spesifikasi/Merk:</strong> Jumlah & jenis barang sesuai, namun terdapat perubahan merk/spek dari kebijakan pusat. Barang otomatis dicatat ke Master Data Gudang sesuai merk fisik yang diterima.</li>
                    <li><strong>Tidak Sesuai:</strong> Barang tidak masuk ke stok gudang dan diteruskan untuk revisi penanganan.</li>
                </ul>
            </div>
        </div>

        <form method="POST" action="{{ route('penerimaan.simpan', $pengajuan) }}" class="space-y-6">
            @csrf

            <div class="space-y-6">
                @foreach($pengajuan->items as $index => $item)
                    @php
                        $currentStatus = old('items.'.$index.'.status_kesesuaian', $item->status_kesesuaian ?? 'sesuai');
                    @endphp
                    <div class="p-5 rounded-2xl border border-border bg-input/40 space-y-4" id="item-card-{{ $index }}">
                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-border/80 pb-2">
                            <div>
                                <h4 class="font-bold text-sm text-foreground" id="original-nama-{{ $index }}">{{ $item->nama_barang }}</h4>
                                <p class="text-xs text-muted-foreground">Spesifikasi Diajukan: <span class="font-medium text-foreground">{{ $item->spesifikasi ?? '-' }}</span></p>
                            </div>
                            <span class="px-2.5 py-1 rounded-xl bg-primary/10 text-primary font-bold text-xs self-start sm:self-auto">
                                Diajukan: {{ $item->jumlah_diajukan }} {{ $item->satuan }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-1.5">
                                    Jumlah Fisik Diterima <span class="text-destructive">*</span>
                                </label>
                                <input type="number" name="items[{{ $index }}][jumlah_diterima]" value="{{ old('items.'.$index.'.jumlah_diterima', $item->jumlah_diterima ?? $item->jumlah_diajukan) }}" min="0" required
                                    class="w-full px-3.5 py-2 rounded-xl bg-card border border-border text-foreground font-bold text-xs focus:ring-1 focus:ring-primary">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-1.5">
                                    Status Kesesuaian <span class="text-destructive">*</span>
                                </label>
                                <select name="items[{{ $index }}][status_kesesuaian]" required
                                    onchange="toggleBedaSpesifikasiPanel({{ $index }}, this.value)"
                                    class="w-full px-3.5 py-2 rounded-xl bg-card border border-border text-foreground font-semibold text-xs focus:ring-1 focus:ring-primary">
                                    <option value="sesuai" {{ $currentStatus === 'sesuai' ? 'selected' : '' }}>
                                        ✅ Sesuai Penuh (Masuk Stok Gudang)
                                    </option>
                                    <option value="sesuai_beda_spesifikasi" {{ $currentStatus === 'sesuai_beda_spesifikasi' ? 'selected' : '' }}>
                                        🔄 Sesuai Beda Spesifikasi/Merk (Kebijakan Pusat)
                                    </option>
                                    <option value="tidak_sesuai" {{ $currentStatus === 'tidak_sesuai' ? 'selected' : '' }}>
                                        ❌ Tidak Sesuai (Ada Selisih/Ditolak)
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-1.5">
                                    Tanggal Terima Fisik <span class="text-destructive">*</span>
                                </label>
                                <input type="date" name="items[{{ $index }}][tanggal_terima]" value="{{ old('items.'.$index.'.tanggal_terima', $item->tanggal_terima?->format('Y-m-d') ?? date('Y-m-d')) }}" required
                                    class="w-full px-3.5 py-2 rounded-xl bg-card border border-border text-foreground text-xs font-medium focus:ring-1 focus:ring-primary">
                            </div>
                        </div>

                        {{-- Panel Khusus: Sesuai Beda Spesifikasi / Merk Karena Kebijakan Pusat --}}
                        <div id="panel-beda-spek-{{ $index }}" class="{{ $currentStatus === 'sesuai_beda_spesifikasi' ? '' : 'hidden' }} p-4 rounded-xl border border-amber-500/30 bg-amber-500/5 space-y-3">
                            <div class="flex items-center gap-2 text-amber-600 dark:text-amber-400 font-bold text-xs">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span>Rincian Barang Aktual yang Diterima (Pengganti dari Pusat)</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-semibold text-foreground uppercase tracking-wider mb-1">
                                        Merk / Tipe Fisik yang Diterima <span class="text-destructive">*</span>
                                    </label>
                                    <input type="text"
                                        id="input-merk-{{ $index }}"
                                        name="items[{{ $index }}][merk_diterima]"
                                        value="{{ old('items.'.$index.'.merk_diterima') }}"
                                        placeholder="Contoh: Logitech B100 / Rexus Daxa Air..."
                                        oninput="updateNamaPreview({{ $index }})"
                                        class="w-full px-3 py-2 rounded-xl bg-card border border-border text-foreground text-xs font-medium focus:ring-1 focus:ring-amber-500">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-semibold text-foreground uppercase tracking-wider mb-1">
                                        Alasan Penyesuaian dari Pusat / Vendor
                                    </label>
                                    <input type="text"
                                        name="items[{{ $index }}][alasan_beda_spesifikasi]"
                                        value="{{ old('items.'.$index.'.alasan_beda_spesifikasi') }}"
                                        placeholder="Contoh: Kebijakan pengadaan pusat / tipe lama diskontinu..."
                                        class="w-full px-3 py-2 rounded-xl bg-card border border-border text-foreground text-xs focus:ring-1 focus:ring-amber-500">
                                </div>
                            </div>

                            <div class="p-2.5 rounded-lg bg-amber-500/10 border border-amber-500/20 text-[11px] text-foreground flex items-center justify-between flex-wrap gap-2">
                                <span>📦 Master Barang di Gudang akan dicatat sebagai:</span>
                                <strong class="text-primary font-mono text-xs" id="preview-nama-{{ $index }}">{{ $item->nama_barang }}</strong>
                            </div>
                        </div>

                        {{-- Panel Umum: Spesifikasi & Catatan --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-muted-foreground uppercase tracking-wider mb-1.5" id="label-spek-{{ $index }}">
                                    Spesifikasi Fisik yang Diterima (Opsional)
                                </label>
                                <input type="text" name="items[{{ $index }}][spesifikasi_diterima]" value="{{ old('items.'.$index.'.spesifikasi_diterima', $item->spesifikasi_diterima ?? $item->spesifikasi) }}" placeholder="Ketik rincian teknis fisik barang yang datang..."
                                    class="w-full px-3.5 py-2 rounded-xl bg-card border border-border text-foreground text-xs focus:ring-1 focus:ring-primary">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-muted-foreground uppercase tracking-wider mb-1.5">
                                    Catatan Tambahan / Selisih
                                </label>
                                <input type="text" name="items[{{ $index }}][catatan_selisih]" value="{{ old('items.'.$index.'.catatan_selisih', $item->catatan_selisih) }}" placeholder="Contoh: Nomor serial, kemasan, atau catatan fisik..."
                                    class="w-full px-3.5 py-2 rounded-xl bg-card border border-border text-foreground text-xs focus:ring-1 focus:ring-primary">
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                <a href="{{ route('pengajuan.show', $pengajuan) }}" class="px-5 py-2.5 rounded-2xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-2xl bg-teal-500 hover:bg-teal-600 text-white text-xs font-bold shadow-lg shadow-teal-500/25 transition-transform active:scale-95">
                    Simpan Verifikasi & Sinkronkan Stok Gudang
                </button>
            </div>
        </form>
    </div>

</div>

<script>
function toggleBedaSpesifikasiPanel(index, status) {
    const panel = document.getElementById('panel-beda-spek-' + index);
    const labelSpek = document.getElementById('label-spek-' + index);
    if (!panel) return;

    if (status === 'sesuai_beda_spesifikasi') {
        panel.classList.remove('hidden');
        if (labelSpek) labelSpek.textContent = 'Spesifikasi Teknis Fisik Tambahan (Opsional)';
        updateNamaPreview(index);
    } else {
        panel.classList.add('hidden');
        if (labelSpek) labelSpek.textContent = 'Spesifikasi Fisik yang Diterima (Opsional)';
    }
}

function updateNamaPreview(index) {
    const originalNamaEl = document.getElementById('original-nama-' + index);
    const inputMerk = document.getElementById('input-merk-' + index);
    const previewEl = document.getElementById('preview-nama-' + index);

    if (!originalNamaEl || !previewEl) return;

    const baseName = originalNamaEl.textContent.trim();
    const merk = inputMerk ? inputMerk.value.trim() : '';

    if (!merk) {
        previewEl.textContent = baseName;
    } else {
        previewEl.textContent = `${baseName} - ${merk}`;
    }
}

// Inisialisasi awal preview pada saat load jika sudah ada data lama
document.addEventListener('DOMContentLoaded', function () {
    @foreach($pengajuan->items as $idx => $itm)
        updateNamaPreview({{ $idx }});
    @endforeach
});
</script>
@endsection
