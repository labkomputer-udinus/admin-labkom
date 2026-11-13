# 📦 Implementation Plan: Distribusi Mandiri (Tanpa Laporan)

> **Versi:** 1.0 | **Tanggal:** November 2026
> **Prioritas:** Tinggi — Fitur saat ini hanya melayani distribusi yang terikat laporan masalah, tidak mendukung kebutuhan distribusi proaktif seperti upgrade lab, pemeliharaan rutin, atau pengisian awal.

---

## 🧩 Masalah yang Ingin Dipecahkan

### Kondisi Sekarang

Distribusi barang saat ini **wajib** terikat ke sebuah `laporan_item` (item dari laporan masalah). Ini terlihat pada:

```php
// DistribusiController.php — baris 89
'laporan_item_id' => ['required', 'exists:laporan_items,id'],
```

```php
// distribusis migration — laporan_item_id NOT NULL dengan constraint FK
$table->foreignId('laporan_item_id')->constrained('laporan_items')->cascadeOnDelete();
```

### Dampak Keterbatasan Ini

| Skenario | Bisa Dilakukan Sekarang? |
|---|:---:|
| Distribusi barang karena ada laporan kerusakan | ✅ Bisa |
| Distribusi SSD ke Lab untuk **upgrade HDD → SSD** | ❌ Tidak bisa |
| Distribusi kabel HDMI penambahan titik proyektor baru | ❌ Tidak bisa |
| Distribusi stok awal saat lab baru dibuka | ❌ Tidak bisa |
| Distribusi barang habis pakai rutin (tinta board, dll.) | ❌ Tidak bisa |
| Distribusi demi **dokumentasi akreditasi** tanpa masalah | ❌ Tidak bisa |

---

## 🎯 Tujuan Implementasi

Memperkenalkan mode **"Distribusi Mandiri"** yaitu distribusi yang:
1. **Tidak memerlukan laporan masalah** sebagai syarat
2. Tetap mencatat pengurangan stok gudang secara akurat
3. Memiliki **alasan distribusi** yang jelas untuk keperluan audit & akreditasi
4. Terintegrasi sempurna dengan halaman Log Distribusi yang sudah ada

---

## 🗂️ Daftar File yang Akan Diubah/Dibuat

| No | File | Jenis Perubahan | Tujuan |
|---|---|---|---|
| 1 | `database/migrations/xxxx_add_distribusi_mandiri.php` | **Baru** | Tambah kolom `laporan_item_id` jadi nullable + kolom baru `jenis_distribusi`, `alasan_distribusi` |
| 2 | `app/Models/Distribusi.php` | **Edit** | Update `$fillable`, tambah helper method `isMandiri()` dan `isLaporan()` |
| 3 | `app/Http/Controllers/DistribusiController.php` | **Edit** | Update `create()`, `store()`, `destroy()` untuk support mode mandiri |
| 4 | `resources/views/distribusi/create.blade.php` | **Edit** | Tambah mode selector, tampilkan form berbeda untuk mandiri |
| 5 | `resources/views/distribusi/index.blade.php` | **Edit** | Tampilkan badge jenis distribusi, filter baru |
| 6 | `app/Constants/JenisTindakan.php` | **Edit** | Tambah constant baru `DISTRIBUSI_MANDIRI` |
| 7 | `routes/web.php` | **Periksa** | Kemungkinan tidak perlu diubah (endpoint tetap sama) |

---

## 📐 Rancangan Perubahan Database

### Migration Baru

**File:** `database/migrations/2026_11_xx_000001_add_distribusi_mandiri_support.php`

```php
// PERUBAHAN pada tabel `distribusis`:

// 1. Jadikan laporan_item_id nullable (saat ini NOT NULL)
$table->foreignId('laporan_item_id')
      ->nullable()           // ← TAMBAH nullable
      ->change();

// 2. Tambah kolom jenis_distribusi
// 'laporan'   = distribusi biasa terikat laporan (perilaku saat ini)
// 'mandiri'   = distribusi proaktif tanpa laporan
$table->enum('jenis_distribusi', ['laporan', 'mandiri'])
      ->default('laporan')
      ->after('asal_barang');

// 3. Tambah kolom alasan distribusi mandiri
$table->string('alasan_distribusi', 500)
      ->nullable()
      ->after('jenis_distribusi');

// 4. Jadikan kode_pc_tujuan nullable (agar bisa distribusi barang non-PC)
$table->string('kode_pc_tujuan')
      ->nullable()
      ->change();
```

> **Catatan:** Pastikan `doctrine/dbal` sudah terinstall sebelum menjalankan `->change()`:
> ```bash
> composer require doctrine/dbal
> ```

---

## 🔧 Rancangan Perubahan Kode

### 1. Model `Distribusi.php`

**Perubahan:**
- Tambah `jenis_distribusi` dan `alasan_distribusi` ke `$fillable`
- Tambah method helper `isMandiri()` dan `isLaporan()`

```php
// Tambah ke $fillable:
'jenis_distribusi',
'alasan_distribusi',

// Tambah method:
public function isMandiri(): bool
{
    return $this->jenis_distribusi === 'mandiri';
}

public function isLaporan(): bool
{
    return $this->jenis_distribusi === 'laporan';
}
```

---

### 2. `DistribusiController.php` — `store()`

**Logika percabangan utama:**

```
IF jenis_distribusi === 'laporan':
    → Jalankan logika yang sudah ada (wajib ada laporan_item_id)
    → Cek sisa kebutuhan item laporan
    → Update status laporan_item setelah distribusi
    → Catat ke PenangananLogger (audit trail laporan)

ELSE IF jenis_distribusi === 'mandiri':
    → laporan_item_id = null (tidak wajib)
    → alasan_distribusi WAJIB diisi
    → Kurangi stok gudang (sama seperti sebelumnya)
    → Catat StokTransaksi dengan keterangan dari alasan
    → TIDAK ada update status laporan_item
    → TIDAK ada PenangananLogger (karena tidak ada laporan terkait)
```

**Perubahan validasi:**

```php
// SEBELUM:
'laporan_item_id' => ['required', 'exists:laporan_items,id'],
'kode_pc_tujuan'  => ['required', 'string', 'max:255'],

// SESUDAH:
'jenis_distribusi'  => ['required', 'in:laporan,mandiri'],
'laporan_item_id'   => ['required_if:jenis_distribusi,laporan', 'nullable', 'exists:laporan_items,id'],
'alasan_distribusi' => ['required_if:jenis_distribusi,mandiri', 'nullable', 'string', 'max:500'],
'kode_pc_tujuan'    => ['nullable', 'string', 'max:255'],  // ← tidak lagi required
```

---

### 3. `DistribusiController.php` — `destroy()`

Tambah pengecekan kondisional — saat distribusi mandiri dihapus, tidak perlu rollback status `laporan_item` (karena tidak ada):

```php
// Kembalikan status laporan item — HANYA jika bukan distribusi mandiri
if ($distribusi->isLaporan() && $distribusi->laporanItem) {
    // ... logika rollback status yang sudah ada
}
```

---

### 4. `JenisTindakan.php`

Tambah constant baru untuk log aksi distribusi mandiri:

```php
public const DISTRIBUSI_MANDIRI = 'distribusi_mandiri';

// Di labels():
self::DISTRIBUSI_MANDIRI => 'Distribusi Mandiri (Tanpa Laporan)',
```

---

## 🖥️ Rancangan Tampilan (UI/UX)

### Halaman Form Distribusi (`distribusi/create`)

#### Mode Selector di Bagian Paling Atas Form

Tambahkan **toggle/tab pemilih mode** sebelum form utama:

```
┌─────────────────────────────────────────────────────────┐
│  Pilih Mode Distribusi:                                  │
│                                                          │
│  ◉ Distribusi Berdasarkan Laporan                       │
│    (Barang dikirim untuk menyelesaikan laporan masalah) │
│                                                          │
│  ○ Distribusi Mandiri / Proaktif                        │
│    (Upgrade lab, pengisian awal, pemeliharaan rutin)    │
└─────────────────────────────────────────────────────────┘
```

Gunakan `radio button` dengan styling Tailwind — bukan tab.
Saat radio berubah, JS menyembunyikan/menampilkan seksi yang relevan.

#### Form Mode "Laporan" (Tampilan Saat Ini — Tidak Berubah)

Semua field saat ini tetap ada. Perubahan hanya:
- `kode_pc_tujuan` menjadi opsional (tambahkan teks `(Opsional)` di label)

#### Form Mode "Mandiri" (Tampilan Baru)

```
┌─── Seksi 1: Barang yang Didistribusikan ────────────────┐
│  Pilih Barang dari Gudang: [Custom Searchable Select]   │
│  Jumlah yang Dikirim:      [input number]               │
│  Asal Sumber Barang:       [select: stok_lama/pengajuan]│
└─────────────────────────────────────────────────────────┘

┌─── Seksi 2: Alasan Distribusi ──────────────────────────┐
│  Kategori Alasan: [select dropdown]                      │
│    - Upgrade Perangkat Lab (contoh: HDD → SSD)          │
│    - Pengisian Awal / Lab Baru                           │
│    - Pemeliharaan & Perawatan Rutin                      │
│    - Penggantian Preventif (sebelum rusak)               │
│    - Lainnya (isi manual)                                │
│                                                          │
│  Keterangan Detail: [textarea]                           │
└─────────────────────────────────────────────────────────┘

┌─── Seksi 3: Tujuan Distribusi ──────────────────────────┐
│  Lab Tujuan:          [select - 14 labs]                │
│  Kode PC / Perangkat: [text, OPSIONAL]                  │
│  Nama Penerima:       [text, wajib]                     │
│  Tanggal Distribusi:  [datetime-local]                  │
└─────────────────────────────────────────────────────────┘
```

> **Tip:** Simpan nilai alasan ke kolom `alasan_distribusi` dengan format:
> `"[Upgrade Perangkat Lab] HDD → SSD untuk 20 unit PC Lab 7"` — prefix kategori dari dropdown, detail dari textarea.

### Halaman Log Distribusi (`distribusi/index`)

#### Tambahan Filter

```
Tambah filter baru di baris filter yang sudah ada:
[ Jenis Distribusi: Semua ▼ ]
  → Semua
  → Berdasarkan Laporan
  → Distribusi Mandiri
```

#### Tambahan Badge di Kolom Tabel

Pada kolom **Asal Barang (Audit)**, tampilkan badge jenis distribusi:

```
Mode Laporan:
  [🟢 Pengadaan] PG-2026-001  [📋 Lap. #LAP-0023]

Mode Mandiri:
  [🔵 Stok Lama]  [⚡ Mandiri: Upgrade Lab]
```

Untuk mode mandiri, tampilkan alasan singkat sebagai tooltip atau baris kecil di bawah.

---

## 📊 Diagram Alur Baru

```
Admin klik "Distribusi Barang"
            │
            ▼
  ┌─── Pilih Mode ──────────────┐
  │                             │
  ▼                             ▼
Mode: LAPORAN              Mode: MANDIRI
  │                             │
  ▼                             ▼
Pilih item laporan         Pilih barang
yang masih pending         dari gudang langsung
(sisa kebutuhan > 0)       (semua stok > 0)
  │                             │
  ▼                             ▼
Validasi sisa              Pilih kategori alasan
kebutuhan item             + isi keterangan detail
  │                             │
  └──────────┬──────────────────┘
             │
             ▼
     Isi detail tujuan
     (Lab, Penerima, Kode PC opsional, Tanggal)
             │
             ▼
     [Simpan & Distribusikan]
             │
             ▼
     ┌───────────────────────────────────┐
     │  - Kurangi stok gudang            │
     │  - Catat StokTransaksi (keluar)   │
     │  - Jika mode Laporan:             │
     │      Update status laporan_item   │
     │      Catat PenangananLogger       │
     │  - Jika mode Mandiri:             │
     │      Hanya kurangi stok + catat   │
     │      transaksi (tidak ada laporan)│
     └───────────────────────────────────┘
             │
             ▼
     Redirect ke Log Distribusi ✓
```

---

## 📋 Urutan Langkah Implementasi

> Lakukan secara **berurutan** untuk menghindari error dependency.

### Step 1 — Database (Migration)
- [ ] Buat file migration baru
- [ ] Jalankan `php artisan migrate`
- [ ] Verifikasi kolom di tabel `distribusis` sudah sesuai:
  - `laporan_item_id` → nullable ✓
  - `jenis_distribusi` → enum, default `'laporan'` ✓
  - `alasan_distribusi` → nullable string(500) ✓
  - `kode_pc_tujuan` → nullable ✓

### Step 2 — Model
- [ ] Update `Distribusi.php`:
  - Tambah kolom baru ke `$fillable`
  - Tambah method `isMandiri()` dan `isLaporan()`

### Step 3 — Constant
- [ ] Tambah `DISTRIBUSI_MANDIRI` ke `JenisTindakan.php`

### Step 4 — Controller
- [ ] Update validasi `store()` dengan percabangan `required_if`
- [ ] Pisahkan logika store ke dua cabang: laporan vs mandiri
- [ ] Update `destroy()` agar tidak rollback laporan_item saat mode mandiri

### Step 5 — View: Form Distribusi
- [ ] Tambah radio selector mode di atas form
- [ ] Tambah hidden field `jenis_distribusi` yang diupdate JS saat radio dipilih
- [ ] Tambah seksi form baru untuk mode mandiri (barang + alasan)
- [ ] Sembunyikan seksi "Item Laporan" saat mode mandiri aktif
- [ ] Ubah `kode_pc_tujuan` dari required menjadi opsional dengan label yang sesuai
- [ ] Tambah JS untuk show/hide seksi berdasarkan mode

### Step 6 — View: Log Distribusi Index
- [ ] Tambah filter "Jenis Distribusi"
- [ ] Update tampilan kolom tabel dengan badge mode dan alasan distribusi mandiri

### Step 7 — Testing Manual
- [ ] Test distribusi mode **laporan** → pastikan perilaku lama tidak berubah sama sekali
- [ ] Test distribusi mode **mandiri** → stok berkurang, tidak ada FK error laporan_item
- [ ] Test hapus distribusi mandiri → stok kembali, tidak ada error rollback laporan_item
- [ ] Cek filter log distribusi berjalan untuk kedua mode
- [ ] Data distribusi lama (existing records) tetap tampil normal sebagai mode "laporan"

---

## ❓ Poin Konfirmasi Sebelum Eksekusi

Sebelum mengimplementasikan, ada beberapa poin yang perlu dikonfirmasi:

1. **Kategori Alasan Mandiri** — Apakah daftar kategori berikut sudah sesuai, atau ada yang perlu ditambah/diubah?
   - Upgrade Perangkat Lab
   - Pengisian Awal / Lab Baru
   - Pemeliharaan & Perawatan Rutin
   - Penggantian Preventif
   - Lainnya

2. **Kode PC Tujuan** — Untuk mode mandiri, apakah field ini sepenuhnya opsional, atau ada kasus tertentu yang tetap wajib mengisi kode PC?

3. **Ekspor PDF/Excel** — Apakah laporan ekspor distribusi perlu menampilkan kolom alasan distribusi mandiri? Jika ya, apakah ekspor yang sudah ada perlu diupdate juga?

---

*Dokumen ini siap dieksekusi setelah konfirmasi poin-poin di atas.*

