@extends('layouts.app')

@section('title', 'Buat Pengajuan Pengadaan')
@section('header_title', 'Form Pengajuan Pengadaan Barang')

@section('content')
<div class="max-w-4xl mx-auto flex flex-col gap-6 pb-10">

    <a href="{{ route('pengajuan.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-muted-foreground hover:text-primary transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span>Kembali ke Daftar Pengajuan</span>
    </a>

    <!-- Helpful Quick Import from Pending Laporan Items -->
    @if($availableItems->isNotEmpty())
        <div class="bg-card border border-primary/30 p-5 rounded-3xl space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <h4 class="text-xs font-bold text-foreground uppercase tracking-wider">Item Menunggu Pengadaan dari Laporan Lab</h4>
                </div>
                <span class="text-[11px] text-muted-foreground">{{ $availableItems->count() }} item tersedia</span>
            </div>
            <p class="text-xs text-muted-foreground">Klik tombol <span class="font-semibold text-primary">"Gunakan Item Ini"</span> untuk langsung memasukkannya ke daftar pengajuan pengadaan di bawah.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 pt-1">
                @foreach($availableItems as $avail)
                    <div class="p-3 rounded-2xl bg-input/50 border border-border/80 flex flex-col justify-between gap-2">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-xs text-foreground">{{ $avail->nama_barang }}</span>
                                <span class="px-2 py-0.5 rounded-md bg-primary/10 text-primary font-bold text-[10px]">Lab {{ $avail->laporan->lab_nama ?? '-' }}</span>
                            </div>
                            <p class="text-[11px] text-muted-foreground mt-0.5">{{ $avail->jumlah }} {{ $avail->satuan }} • {{ $avail->deskripsi ?? 'Perlu unit pengganti' }}</p>
                        </div>
                        <button type="button" onclick="importItem('{{ $avail->id }}', '{{ addslashes($avail->nama_barang) }}', {{ $avail->jumlah }}, '{{ addslashes($avail->satuan) }}', '{{ addslashes($avail->deskripsi ?? '') }}')"
                            class="w-full py-1 rounded-xl bg-primary/15 hover:bg-primary text-primary hover:text-white font-bold text-[11px] transition-colors">
                            + Gunakan Item Ini
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Main Create Form -->
    <form method="POST" action="{{ route('pengajuan.store') }}" class="bg-card border border-border rounded-3xl p-6 sm:p-8 space-y-8 shadow-sm">
        @csrf

        <!-- Section 1: Header Pengajuan -->
        <div class="space-y-4">
            <div class="border-b border-border pb-3">
                <h3 class="text-base font-bold text-foreground flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-black">1</span>
                    Informasi Pengajuan
                </h3>
                <p class="text-xs text-muted-foreground mt-0.5">Nomor pengajuan otomatis dihasilkan dengan format tahun dan bulan.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Nomor Pengajuan <span class="text-destructive">*</span>
                    </label>
                    <input type="text" name="nomor_pengajuan" value="{{ old('nomor_pengajuan', $autoNomor) }}" required
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-primary font-mono font-bold text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Divisi Tujuan <span class="text-destructive">*</span>
                    </label>
                    <input type="text" name="divisi_tujuan" value="{{ old('divisi_tujuan', 'BIUM (Biro Umum)') }}" required placeholder="Nama divisi tujuan..."
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm font-medium focus:ring-2 focus:ring-primary/40 focus:border-primary">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-foreground uppercase tracking-wider mb-2">
                        Tanggal Pengajuan <span class="text-destructive">*</span>
                    </label>
                    <input type="date" name="tanggal_ajuan" value="{{ old('tanggal_ajuan', date('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 rounded-2xl bg-input border border-border text-foreground text-sm font-medium focus:ring-2 focus:ring-primary/40 focus:border-primary">
                </div>
            </div>
        </div>

        <!-- Section 2: Items to procure -->
        <div class="space-y-4">
            <div class="border-b border-border pb-3">
                <h3 class="text-base font-bold text-foreground flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-black">2</span>
                    Rincian Barang yang Diajukan
                </h3>
                <p class="text-xs text-muted-foreground mt-0.5">Pilih dari data master gudang atau masukkan barang kebutuhan baru secara manual.</p>
            </div>

            <div id="procure-container" class="space-y-4">
                <!-- First Row -->
                <div class="procure-row p-4 rounded-2xl border border-border bg-input/40 space-y-3 relative group" data-index="0">
                    <input type="hidden" name="items[0][laporan_item_id]" id="item-laporan-0" value="">

                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-primary procure-number">Barang #1</span>
                        <button type="button" onclick="removeProcureRow(this)" class="text-muted-foreground hover:text-destructive text-xs font-semibold p-1 transition-colors">
                            Hapus Baris
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        {{-- Nama Barang dengan Searchable Dropdown --}}
                        <div class="sm:col-span-2 relative master-dropdown-wrapper" data-index="0">
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-[11px] font-semibold text-muted-foreground uppercase">
                                    Nama Barang <span class="text-destructive">*</span>
                                </label>
                                <span id="master-status-0" class="text-[10px] font-semibold px-2 py-0.5 rounded-md hidden"></span>
                            </div>

                            <div class="relative">
                                <input type="text"
                                    name="items[0][nama_barang]"
                                    id="item-nama-0"
                                    required
                                    autocomplete="off"
                                    placeholder="Pilih dari master atau ketik nama barang..."
                                    class="w-full pl-3 pr-8 py-2 rounded-xl bg-card border border-border text-xs text-foreground font-medium focus:ring-1 focus:ring-primary input-nama-barang"
                                    onfocus="openDropdown(0)"
                                    oninput="handleSearchInput(0, this.value)">

                                <button type="button"
                                    onclick="toggleDropdown(0)"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground p-1 toggle-dropdown-btn"
                                    tabindex="-1"
                                    title="Tampilkan daftar master barang">
                                    <svg id="chevron-0" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Dropdown Menu -->
                            <div id="dropdown-menu-0"
                                class="master-dropdown-menu absolute left-0 right-0 top-full mt-1.5 z-40 bg-card border border-border rounded-2xl shadow-xl overflow-hidden hidden">
                                <div class="px-3 py-2 border-b border-border bg-muted/40 flex items-center justify-between text-[11px]">
                                    <span class="font-bold text-foreground flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        Master Barang (Stok Gudang)
                                    </span>
                                    <span class="text-[10px] text-muted-foreground">7 teratas / ketik untuk memfilter</span>
                                </div>

                                <div id="dropdown-list-0" class="max-h-56 overflow-y-auto divide-y divide-border/60">
                                    <!-- Rendered dynamically -->
                                </div>

                                <div class="p-2 border-t border-border bg-muted/20">
                                    <button type="button"
                                        onclick="selectCustomItem(0)"
                                        class="w-full text-left px-3 py-1.5 rounded-xl text-xs font-semibold text-primary hover:bg-primary/10 transition-colors flex items-center justify-between">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            <span>Gunakan sebagai barang baru (tidak ada di master)</span>
                                        </span>
                                        <span class="text-[10px] text-muted-foreground font-normal">Input Bebas</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-muted-foreground uppercase mb-1">Jumlah Diajukan *</label>
                            <input type="number" name="items[0][jumlah_diajukan]" id="item-jumlah-0" value="1" min="1" required
                                class="w-full px-3 py-2 rounded-xl bg-card border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-muted-foreground uppercase mb-1">Satuan *</label>
                            <select name="items[0][satuan]" id="item-satuan-0" required
                                class="w-full px-3 py-2 rounded-xl bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
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
                        <input type="text" name="items[0][spesifikasi]" id="item-spesifikasi-0" placeholder="Contoh: Speed PC-2666 / 3200MHz, Garansi resmi 1 tahun..."
                            class="w-full px-3 py-2 rounded-xl bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                    </div>
                </div>
            </div>

            <!-- Tombol Tambah Baris di Bawah List Barang -->
            <div class="pt-1">
                <button type="button" onclick="addProcureRow()"
                    class="w-full py-3.5 px-4 rounded-2xl border-2 border-dashed border-border hover:border-primary/60 bg-muted/20 hover:bg-primary/5 text-foreground hover:text-primary font-bold text-xs flex items-center justify-center gap-2 transition-all group shadow-2xs active:scale-[0.99]">
                    <span class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </span>
                    <span>+ Tambah Baris Barang</span>
                </button>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
            <a href="{{ route('pengajuan.index') }}" class="px-5 py-2.5 rounded-2xl border border-border text-foreground text-xs font-semibold hover:bg-muted transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-2xl bg-primary text-white text-xs font-bold shadow-lg shadow-primary/25 hover:bg-primary/90 transition-transform active:scale-95">
                Simpan & Buat Pengajuan
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    let procureIndex = 1;
    const initialTopBarangs = @json($topBarangs ?? []);
    const searchEndpoint = "{{ route('gudang.search-barang') }}";
    const searchCache = {};
    let searchDebounceTimeout = null;

    // Inisialisasi baris pertama saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        renderDropdownList(0, initialTopBarangs);
    });

    // Close dropdown saat klik di luar area dropdown
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.master-dropdown-wrapper')) {
            closeAllDropdowns();
        }
    });

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
                    <p class="text-[11px] mt-0.5">Barang "${escapeHtml(currentQuery)}" akan diajukan sebagai barang baru (non-master).</p>
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
                    onclick="selectMasterItem(${idx}, decodeURIComponent('${itemJson}'))"
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

        // Auto-fill satuan jika tersedia
        if (item.satuan) {
            selectSatuan(idx, item.satuan);
        }

        // Sinkronkan spesifikasi dari master barang
        if (spekInput && item.spesifikasi) {
            spekInput.value = item.spesifikasi;
        } else if (item.catatan && spekInput && spekInput.value.trim() === '') {
            spekInput.value = item.catatan;
        }

        // Status badge: terhubung dengan master
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

        // Jika satuan di master belum ada dalam option default, tambahkan otomatis
        if (!matched) {
            const opt = document.createElement('option');
            opt.value = satuan;
            opt.textContent = satuan;
            opt.selected = true;
            select.appendChild(opt);
        }
    }

    function addProcureRow(laporanItemId = '', nama = '', jumlah = 1, satuan = 'unit', spesifikasi = '') {
        const container = document.getElementById('procure-container');
        const newRow = document.createElement('div');
        newRow.className = 'procure-row p-4 rounded-2xl border border-border bg-input/40 space-y-3 relative group animate-fade-in';
        newRow.dataset.index = procureIndex;

        const currentIdx = procureIndex;

        newRow.innerHTML = `
            <input type="hidden" name="items[${currentIdx}][laporan_item_id]" value="${laporanItemId}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-primary procure-number">Barang #${currentIdx + 1}</span>
                <button type="button" onclick="removeProcureRow(this)" class="text-muted-foreground hover:text-destructive text-xs font-semibold p-1 transition-colors">
                    Hapus Baris
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="sm:col-span-2 relative master-dropdown-wrapper" data-index="${currentIdx}">
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-[11px] font-semibold text-muted-foreground uppercase">
                            Nama Barang <span class="text-destructive">*</span>
                        </label>
                        <span id="master-status-${currentIdx}" class="text-[10px] font-semibold px-2 py-0.5 rounded-md hidden"></span>
                    </div>

                    <div class="relative">
                        <input type="text"
                            name="items[${currentIdx}][nama_barang]"
                            id="item-nama-${currentIdx}"
                            value="${nama}"
                            required
                            autocomplete="off"
                            placeholder="Pilih dari master atau ketik nama barang..."
                            class="w-full pl-3 pr-8 py-2 rounded-xl bg-card border border-border text-xs text-foreground font-medium focus:ring-1 focus:ring-primary input-nama-barang"
                            onfocus="openDropdown(${currentIdx})"
                            oninput="handleSearchInput(${currentIdx}, this.value)">

                        <button type="button"
                            onclick="toggleDropdown(${currentIdx})"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground p-1 toggle-dropdown-btn"
                            tabindex="-1"
                            title="Tampilkan daftar master barang">
                            <svg id="chevron-${currentIdx}" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Dropdown Menu -->
                    <div id="dropdown-menu-${currentIdx}"
                        class="master-dropdown-menu absolute left-0 right-0 top-full mt-1.5 z-40 bg-card border border-border rounded-2xl shadow-xl overflow-hidden hidden">
                        <div class="px-3 py-2 border-b border-border bg-muted/40 flex items-center justify-between text-[11px]">
                            <span class="font-bold text-foreground flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Master Barang (Stok Gudang)
                            </span>
                            <span class="text-[10px] text-muted-foreground">7 teratas / ketik untuk memfilter</span>
                        </div>

                        <div id="dropdown-list-${currentIdx}" class="max-h-56 overflow-y-auto divide-y divide-border/60">
                            <!-- Rendered dynamically -->
                        </div>

                        <div class="p-2 border-t border-border bg-muted/20">
                            <button type="button"
                                onclick="selectCustomItem(${currentIdx})"
                                class="w-full text-left px-3 py-1.5 rounded-xl text-xs font-semibold text-primary hover:bg-primary/10 transition-colors flex items-center justify-between">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    <span>Gunakan sebagai barang baru (tidak ada di master)</span>
                                </span>
                                <span class="text-[10px] text-muted-foreground font-normal">Input Bebas</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-muted-foreground uppercase mb-1">Jumlah Diajukan *</label>
                    <input type="number" name="items[${currentIdx}][jumlah_diajukan]" id="item-jumlah-${currentIdx}" value="${jumlah}" min="1" required
                        class="w-full px-3 py-2 rounded-xl bg-card border border-border text-xs text-foreground font-semibold focus:ring-1 focus:ring-primary">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-muted-foreground uppercase mb-1">Satuan *</label>
                    <select name="items[${currentIdx}][satuan]" id="item-satuan-${currentIdx}" required
                        class="w-full px-3 py-2 rounded-xl bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
                        <option value="unit" ${satuan === 'unit' ? 'selected' : ''}>unit</option>
                        <option value="buah" ${satuan === 'buah' ? 'selected' : ''}>buah</option>
                        <option value="keping" ${satuan === 'keping' ? 'selected' : ''}>keping</option>
                        <option value="set" ${satuan === 'set' ? 'selected' : ''}>set</option>
                        <option value="pasang" ${satuan === 'pasang' ? 'selected' : ''}>pasang</option>
                        <option value="lembar" ${satuan === 'lembar' ? 'selected' : ''}>lembar</option>
                        <option value="meter" ${satuan === 'meter' ? 'selected' : ''}>meter</option>
                        <option value="rol" ${satuan === 'rol' ? 'selected' : ''}>rol</option>
                        <option value="pack" ${satuan === 'pack' ? 'selected' : ''}>pack</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-muted-foreground uppercase mb-1">Spesifikasi Detail Barang</label>
                <input type="text" name="items[${currentIdx}][spesifikasi]" id="item-spesifikasi-${currentIdx}" value="${spesifikasi}" placeholder="Contoh: Speed PC-2666 / 3200MHz, Garansi..."
                    class="w-full px-3 py-2 rounded-xl bg-card border border-border text-xs text-foreground focus:ring-1 focus:ring-primary">
            </div>
        `;

        container.appendChild(newRow);
        renderDropdownList(currentIdx, initialTopBarangs);
        procureIndex++;
        updateProcureNumbers();
    }

    function removeProcureRow(button) {
        const rows = document.querySelectorAll('.procure-row');
        if (rows.length <= 1) {
            alert('Minimal harus ada 1 barang dalam pengajuan.');
            return;
        }
        button.closest('.procure-row').remove();
        updateProcureNumbers();
    }

    function updateProcureNumbers() {
        document.querySelectorAll('.procure-row').forEach((row, i) => {
            row.querySelector('.procure-number').textContent = `Barang #${i + 1}`;
        });
    }

    function importItem(id, nama, jumlah, satuan, deskripsi) {
        const firstNama = document.getElementById('item-nama-0');
        if (firstNama && firstNama.value.trim() === '') {
            document.getElementById('item-laporan-0').value = id;
            firstNama.value = nama;
            document.getElementById('item-jumlah-0').value = jumlah;
            selectSatuan(0, satuan);
            document.getElementById('item-spesifikasi-0').value = deskripsi;

            const badge = document.getElementById('master-status-0');
            if (badge) {
                badge.className = 'text-[10px] font-semibold px-2 py-0.5 rounded-md bg-blue-500/15 text-blue-600 dark:text-blue-400 inline-block';
                badge.textContent = '📋 Dari Laporan Lab';
                badge.classList.remove('hidden');
            }
            alert(`Item "${nama}" berhasil dimasukkan ke baris #1.`);
        } else {
            addProcureRow(id, nama, jumlah, satuan, deskripsi);
            alert(`Item "${nama}" berhasil ditambahkan ke daftar pengajuan.`);
        }
    }
</script>
@endpush
