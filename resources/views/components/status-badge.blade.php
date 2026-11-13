@props(['status'])

@php
    $labels = [
        'dicatat'                    => 'Dicatat',
        'ditriase'                   => 'Ditriase',
        'dalam_proses'               => 'Dalam Proses',
        'menunggu_keputusan_divisi'  => 'Menunggu Divisi',
        'barang_diterima'            => 'Barang Diterima',
        'siap_distribusi'            => 'Siap Distribusi',
        'selesai'                    => 'Selesai',
        'ditolak'                    => 'Ditolak',
        'perlu_revisi'               => 'Perlu Revisi',

        // Items status
        'menunggu_triase'   => 'Menunggu Triase',
        'proses_perbaikan'  => 'Proses Perbaikan',
        'selesai_perbaikan' => 'Selesai Perbaikan',
        'menunggu_pengadaan'=> 'Menunggu Pengadaan',
        'terdistribusi'     => 'Terdistribusi',

        // Pengajuan status
        'menunggu'  => 'Menunggu',
        'disetujui' => 'Disetujui',

        // Barang Tertinggal status
        'belum_diambil'        => 'Belum Diambil',
        'diumumkan'            => 'Diumumkan',
        'sudah_diambil'        => 'Sudah Diambil',
        'diserahkan_ke_satpam' => 'Diserahkan ke Satpam',
    ];

    $icons = [
        'dicatat'                   => '○',
        'ditriase'                  => '◐',
        'dalam_proses'              => '◑',
        'menunggu_keputusan_divisi' => '◷',
        'barang_diterima'           => '✓',
        'siap_distribusi'           => '→',
        'selesai'                   => '✓',
        'ditolak'                   => '✕',
        'menunggu_triase'           => '○',
        'proses_perbaikan'          => '◑',
        'selesai_perbaikan'         => '✓',
        'menunggu_pengadaan'        => '◷',
        'terdistribusi'             => '✓',
        'menunggu'                  => '◷',
        'disetujui'                 => '✓',
        'perlu_revisi'              => '!',
        'belum_diambil'             => '○',
        'diumumkan'                 => '◐',
        'sudah_diambil'             => '✓',
        'diserahkan_ke_satpam'      => '→',
    ];

    $classes = [
        'dicatat'                    => 'bg-slate-100 text-slate-700 dark:bg-slate-700/40 dark:text-slate-300',
        'ditriase'                   => 'bg-sky-100 text-sky-700 dark:bg-sky-700/40 dark:text-sky-300',
        'dalam_proses'               => 'bg-amber-100 text-amber-700 dark:bg-amber-700/40 dark:text-amber-300',
        'menunggu_keputusan_divisi'  => 'bg-orange-100 text-orange-700 dark:bg-orange-700/40 dark:text-orange-300',
        'barang_diterima'            => 'bg-teal-100 text-teal-700 dark:bg-teal-700/40 dark:text-teal-300',
        'siap_distribusi'            => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-700/40 dark:text-indigo-300',
        'selesai'                    => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/40 dark:text-emerald-300',
        'ditolak'                    => 'bg-rose-100 text-rose-700 dark:bg-rose-700/40 dark:text-rose-300',
        'perlu_revisi'               => 'bg-pink-100 text-pink-700 dark:bg-pink-700/40 dark:text-pink-300',

        // Item status
        'menunggu_triase'    => 'bg-slate-100 text-slate-700 dark:bg-slate-700/40 dark:text-slate-300',
        'proses_perbaikan'   => 'bg-amber-100 text-amber-700 dark:bg-amber-700/40 dark:text-amber-300',
        'selesai_perbaikan'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/40 dark:text-emerald-300',
        'menunggu_pengadaan' => 'bg-orange-100 text-orange-700 dark:bg-orange-700/40 dark:text-orange-300',
        'terdistribusi'      => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-700/40 dark:text-indigo-300',

        // Pengajuan status
        'menunggu'  => 'bg-orange-100 text-orange-700 dark:bg-orange-700/40 dark:text-orange-300',
        'disetujui' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-700/40 dark:text-emerald-300',

        // Barang Tertinggal status
        'belum_diambil'        => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-300/40',
        'diumumkan'            => 'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-300 border border-sky-300/40',
        'sudah_diambil'        => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-300/40',
        'diserahkan_ke_satpam' => 'bg-slate-100 text-slate-800 dark:bg-slate-800/60 dark:text-slate-300 border border-slate-300/40',
    ];

    $label      = $labels[$status] ?? ucwords(str_replace('_', ' ', $status));
    $badgeClass = $classes[$status] ?? 'bg-slate-100 text-slate-700 dark:bg-slate-700/40 dark:text-slate-300';
    $icon       = $icons[$status] ?? '○';
@endphp

<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold whitespace-nowrap {{ $badgeClass }}">
    <span class="text-[10px] leading-none">{{ $icon }}</span>
    <span>{{ $label }}</span>
</span>
