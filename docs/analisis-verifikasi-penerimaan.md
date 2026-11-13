# Analisis Verifikasi Penerimaan Barang
## Risiko Double Entry & Penanganan Kekeliruan

> **Dokumen ini membahas:** Bagaimana sistem menangani klik ulang verifikasi penerimaan, risiko stok masuk ganda, skenario perubahan status, dan langkah koreksi jika terjadi kekeliruan.

---

## 1. Mekanisme Sistem: Delta Stok

Sistem **tidak langsung menambah stok** setiap kali tombol "Simpan Verifikasi" ditekan.  
Sebaliknya, sistem menggunakan **mekanisme delta** (selisih) yang dihitung dari riwayat sebelumnya.

**Logika inti** di `PenerimaanController::simpan()` (sekitar baris 40–126):

```php
// Ambil data verifikasi SEBELUMNYA (sebelum disimpan ulang)
$prevJumlahDiterima = $item->jumlah_diterima ?? 0;
$prevMasukStok = in_array($item->status_kesesuaian, ['sesuai', 'sesuai_beda_spesifikasi']);

// ...proses validasi & update...

// Hitung delta: berapa stok yang PERLU ditambahkan
$delta = $prevMasukStok
    ? ($itemData['jumlah_diterima'] - $prevJumlahDiterima)  // sudah pernah masuk stok → hitung selisihnya
    : $itemData['jumlah_diterima'];                          // belum pernah masuk stok → tambah penuh

// Hanya tambah stok jika delta > 0
if ($delta > 0) {
    $stok->increment('stok_saat_ini', $delta);
    StokTransaksi::create([...]); // catat riwayat transaksi
}
```

Dengan mekanisme ini, sistem **secara desain mencegah penambahan stok ganda** untuk kasus-kasus sederhana.

---

## 2. Skenario-Skenario Lengkap

### ✅ Skenario 1: Klik Verifikasi Ulang — Status & Jumlah Sama

**Situasi:** Item sudah diverifikasi `sesuai` dengan jumlah 5. Admin klik simpan ulang tanpa mengubah apapun.

| Variabel | Nilai |
|---|---|
| `prevMasukStok` | `true` (sebelumnya sesuai) |
| `prevJumlahDiterima` | 5 |
| `jumlah_diterima` baru | 5 |
| `delta` | `5 - 5 = 0` |

**Hasil:** `delta = 0` → tidak ada penambahan stok. **AMAN, tidak terjadi double entry.**

---

### ✅ Skenario 2: Klik Verifikasi Ulang — Jumlah Berubah

**Situasi:** Item sudah diverifikasi `sesuai` jumlah 3. Ternyata ada tambahan 2 unit, diubah menjadi 5.

| Variabel | Nilai |
|---|---|
| `prevMasukStok` | `true` |
| `prevJumlahDiterima` | 3 |
| `jumlah_diterima` baru | 5 |
| `delta` | `5 - 3 = 2` |

**Hasil:** Hanya **2 unit** ditambahkan ke stok (bukan 5). **AMAN, selisih diproses dengan benar.**

---

### ✅ Skenario 3: Status `tidak_sesuai` → `sesuai`

**Situasi:** Item awalnya ditandai `tidak_sesuai` (tidak masuk stok). Kemudian diubah menjadi `sesuai` dengan jumlah 4.

| Variabel | Nilai |
|---|---|
| `prevMasukStok` | `false` (sebelumnya tidak_sesuai) |
| `prevJumlahDiterima` | 4 (nilai tersimpan sebelumnya) |
| `delta` | `jumlah_diterima` penuh = **4** |

**Hasil:** 4 unit ditambahkan ke stok. **AMAN, stok ditambahkan sekali saja.**

---

### ⚠️ Skenario 4: Status `sesuai` → `tidak_sesuai` (MASALAH!)

**Situasi:** Item sudah diverifikasi `sesuai`, stok 5 unit sudah masuk gudang. Kemudian admin menyadari salah, mengubah status ke `tidak_sesuai`.

| Variabel | Nilai |
|---|---|
| `prevMasukStok` | `true` |
| `isMasukStok` (baru) | `false` |
| Blok penambahan stok | **DILEWATI** (kondisi `if ($isMasukStok)` tidak terpenuhi) |
| Stok sebelumnya yang sudah masuk | **TIDAK DI-ROLLBACK** |

**Hasil:** Status item berubah ke `tidak_sesuai`, tapi **5 unit stok yang sudah terlanjur masuk TETAP ADA di gudang.**

> [!WARNING]
> **Ini adalah celah/gap dalam sistem.** Tidak ada mekanisme otomatis untuk mengembalikan (rollback) stok ketika status diubah dari `sesuai`/`sesuai_beda_spesifikasi` → `tidak_sesuai`. Stok di gudang tidak berkurang secara otomatis.

---

### ⚠️ Skenario 5: Ganti Spesifikasi Berbeda (`sesuai` → `sesuai_beda_spesifikasi`)

**Situasi:** Item diverifikasi `sesuai` dengan spesifikasi A (stok masuk ke BarangMaster A). Kemudian diubah ke `sesuai_beda_spesifikasi` dengan spesifikasi B (berbeda).

**Yang terjadi:**
1. Sistem mencari/membuat **BarangMaster baru** untuk spesifikasi B.
2. `prevMasukStok = true`, `prevJumlahDiterima = jumlah lama`.
3. Delta dihitung dari jumlah baru - jumlah lama → ditambahkan ke **BarangMaster B**.
4. **BarangMaster A (spesifikasi lama)** — stok yang sebelumnya masuk **TIDAK dikurangi**.

**Hasil:** Stok di BarangMaster A "menggelantung" (orphan) — terdapat stok yang seharusnya tidak ada karena barang sudah dianggap berbeda spesifikasi.

> [!WARNING]
> Skenario perubahan spesifikasi antar-verifikasi berpotensi menyebabkan data stok tidak akurat pada BarangMaster yang pertama kali digunakan.

---

## 3. Ringkasan Status Keamanan Per Skenario

| Skenario | Kondisi | Status |
|---|---|---|
| Klik ulang tanpa perubahan | Delta = 0 | ✅ Aman |
| Klik ulang, jumlah bertambah | Delta = selisih | ✅ Aman |
| `tidak_sesuai` → `sesuai` | Tambah penuh sekali | ✅ Aman |
| `sesuai` → `tidak_sesuai` | Stok tidak di-rollback | ⚠️ Perlu koreksi manual |
| Ganti spesifikasi beda | Stok lama tidak dikurangi | ⚠️ Perlu koreksi manual |
| `tidak_sesuai` → `tidak_sesuai` (ulang) | Tidak ada stok yang diproses | ✅ Aman |

---

## 4. Penanganan Kekeliruan: Stok Sudah Terlanjur Masuk

Jika stok sudah terlanjur masuk (Skenario 4 atau 5 di atas), **sistem tidak memiliki fitur rollback otomatis**. Koreksi dilakukan secara manual melalui fitur **Penyesuaian Stok** di gudang.

### Langkah Koreksi Manual

1. **Buka halaman Gudang** → cari nama barang yang perlu dikoreksi
2. Klik tombol **"Detail"** pada barang tersebut
3. Pada halaman detail stok, klik tombol **"Koreksi / Penyesuaian Stok"**
4. Pilih jenis: **"Kurangi Stok Fisik (Keluar / Rusak / Hilang)"**
5. Isi **jumlah** yang ingin dikurangi (sesuai jumlah yang salah masuk)
6. Isi **keterangan/alasan** yang jelas, contoh:
   ```
   Koreksi verifikasi keliru — Pengadaan #PG-2026-XXX, barang tidak sesuai spesifikasi
   ```
7. Simpan → stok akan berkurang dan riwayat transaksi tercatat

> [!IMPORTANT]
> Selalu isi keterangan/alasan dengan referensi nomor pengajuan agar riwayat audit trail tetap dapat ditelusuri di kemudian hari.

---

## 5. Contoh Kasus Nyata

**Kasus:** Verifikasi penerimaan pengajuan #PG-2026-001. Item "Mouse" spesifikasi "Logitech M90" diverifikasi `sesuai` jumlah 10 unit → masuk stok gudang. Ternyata barang yang datang adalah "Logitech B100" (beda model).

**Kondisi saat ini:** Stok gudang "Mouse - Logitech M90" bertambah 10 unit (salah).

**Langkah koreksi:**
1. Ubah status di verifikasi ke `tidak_sesuai` atau `sesuai_beda_spesifikasi` dengan spesifikasi yang benar
2. Pergi ke Gudang → Detail "Mouse - Logitech M90" → Penyesuaian → **Kurangi 10 unit**
   - Keterangan: `"Koreksi verifikasi keliru PG-2026-001, barang sebenarnya Logitech B100"`
3. Jika barang "Mouse - Logitech B100" belum ada di gudang, tambahkan secara manual melalui halaman Gudang → "Tambah Barang Baru" dengan jumlah yang benar

---

## 6. Rekomendasi Perbaikan (Future Enhancement)

Berikut rekomendasi teknis untuk menutup celah yang ada, jika ingin dikembangkan ke depan:

### 6.1 Tambah Logika Rollback Otomatis

Ketika status berubah dari `sesuai`/`sesuai_beda_spesifikasi` → `tidak_sesuai`, tambahkan logika di `PenerimaanController::simpan()`:

```php
// Jika sebelumnya sudah masuk stok tapi sekarang tidak_sesuai → rollback
if ($prevMasukStok && !$isMasukStok && $prevJumlahDiterima > 0) {
    // Kurangi stok sebesar jumlah yang pernah masuk
    $stok->decrement('stok_saat_ini', $prevJumlahDiterima);
    StokTransaksi::create([
        'jenis' => 'keluar',
        'jumlah' => $prevJumlahDiterima,
        'keterangan' => "Rollback verifikasi — item diubah ke tidak_sesuai (Pengajuan {$pengajuan->nomor_pengajuan})",
        // ...
    ]);
}
```

### 6.2 Tambah Warning di UI

Sebelum admin menyimpan ulang verifikasi yang sudah `sesuai`, tampilkan konfirmasi:

```
⚠️ Item ini sebelumnya sudah diverifikasi SESUAI dan stok sudah masuk gudang.
Mengubah status akan memerlukan koreksi stok manual.
Yakin ingin melanjutkan?
```

### 6.3 Tambah Indikator Visual

Pada halaman verifikasi, tampilkan badge/label untuk item yang sudah pernah diverifikasi dan stoknya sudah masuk gudang, agar admin lebih berhati-hati.

---

## 7. Kesimpulan

| Pertanyaan | Jawaban |
|---|---|
| Apakah klik verifikasi ulang akan double entry stok? | **Tidak**, sistem pakai delta — aman selama status & jumlah tidak berubah |
| Apakah update `tidak_sesuai` → `sesuai` aman? | **Ya**, stok ditambahkan penuh sekali saja |
| Apakah ada risiko stok "nyangkut" di gudang? | **Ya**, jika status diubah dari `sesuai` → `tidak_sesuai` tanpa koreksi manual |
| Bagaimana jika sudah terlanjur keliru? | Gunakan fitur **Koreksi / Penyesuaian Stok** di halaman detail gudang |
| Apakah ada auto-rollback? | **Belum ada** — perlu dikembangkan sebagai future enhancement |

---

*Dibuat: Oktober 2026 | Sistem: Admin Lab Komputer*

