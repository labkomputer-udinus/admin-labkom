# PRD — Sistem Pengelolaan Laporan Permasalahan Laboratorium
## Fase Awal: Scope Admin — Fokus Laporan & Tracking/Log

**Versi:** 0.2 (Draft — Fase Awal, revisi fokus tracking)
**Tanggal:** 19 Agustus 2026
**Pemilik Produk:** Admin Laboratorium
**Status:** Draft untuk direview

---

## 1. Ringkasan

Sistem ini fase awal digunakan oleh **admin** untuk mendigitalkan proses penanganan laporan permasalahan lab. Laboran tetap melapor secara **tertulis** (mengikuti form fisik "Permintaan Tindakan Perbaikan dan Pencegahan" yang sudah ada), lalu admin menginputnya ke sistem.

**Inti dari fase ini bukan sekadar mencatat laporan, tapi membangun jejak (log) setiap tindakan** yang terjadi terhadap satu laporan — dari laboran mencoba tindakan langsung, keputusan koordinator, proses teknisi/pengadaan, sampai barang didistribusikan. Setiap langkah harus tercatat siapa, kapan, dan apa yang terjadi, sehingga riwayat penanganan satu masalah dapat ditelusuri secara lengkap kapan saja — termasuk untuk keperluan verifikasi, evaluasi efektivitas tindakan, dan akreditasi.

---

## 2. Latar Belakang & Masalah

- Laboran melaporkan masalah menggunakan form fisik yang sudah baku (lihat referensi form), berisi: bentuk ketidaksesuaian, lokasi, tanggal/jam kejadian, tanggal/jam laporan, uraian pengamatan, tindakan langsung yang sudah dicoba, dan permintaan tindakan perbaikan/pencegahan.
- Form fisik ini diserahkan ke admin, lalu diteruskan ke **koordinator** untuk diputuskan arah tindak lanjutnya: ditindak ulang oleh laboran, diperbaiki teknisi, atau diganti unit baru.
- Proses ini melibatkan banyak pihak (laboran → admin → koordinator → teknisi/BIUM → gudang → laboran lagi), tetapi **tidak ada jejak digital** yang menyatukan seluruh langkah tersebut per laporan.
- Saat ini, jika ingin tahu "laporan ini sudah sampai mana, siapa yang menindaklanjuti, dan apa hasil tiap langkahnya", admin harus menelusuri form fisik dan ingatannya sendiri — tidak ada satu tempat yang merekam seluruh riwayat.

**Fokus fase ini:** membangun *log tindakan* yang lengkap per laporan, sehingga histori penanganan tidak pernah hilang meski prosesnya panjang dan melibatkan banyak keputusan.

---

## 3. Tujuan

1. Setiap laporan yang diterima dari laboran (form fisik) dapat diinput lengkap ke sistem, mengikuti struktur form yang sudah ada.
2. **Setiap tindakan/keputusan terhadap satu laporan tercatat sebagai satu entri log** — bukan hanya status akhir — sehingga riwayat lengkap dapat dilihat kembali kapan saja.
3. Admin dapat melihat "linimasa" (timeline) satu laporan dari awal (laboran menemukan masalah) sampai selesai, termasuk semua cabang keputusan yang diambil.
4. Admin dapat menelusuri dan memfilter laporan berdasarkan status, lab, tanggal, atau jenis tindak lanjut — untuk kebutuhan rekap maupun evaluasi.
5. *(Pendukung)* Proses tindak lanjut (perbaikan, pengadaan ke BIUM, verifikasi, distribusi) tetap dikelola dalam sistem yang sama, sebagai sumber entri log.

### Metrik keberhasilan
- 100% laporan yang masuk memiliki riwayat log lengkap dari awal sampai status akhir (tidak ada "lompatan" status tanpa jejak).
- Admin dapat menjawab "laporan X sekarang di tahap apa, dan siapa yang terakhir menindaklanjuti" dalam hitungan detik tanpa membuka arsip fisik.
- Admin dapat menyusun bukti riwayat penanganan (untuk evaluasi/akreditasi) langsung dari sistem, tanpa menyusun ulang secara manual.

---

## 4. Target Pengguna & Peran

| Pihak | Status di sistem | Keterangan |
|---|---|---|
| **Admin** | Pengguna sistem (login) | Input laporan, mencatat setiap tindakan/keputusan, mengelola tindak lanjut, gudang, distribusi |
| **Laboran** | Belum jadi pengguna sistem | Melapor tertulis via form fisik; hasil tindak ulang/konfirmasi disampaikan ke admin di luar sistem lalu dicatatkan admin |
| **Koordinator** | Belum jadi pengguna sistem (data referensi) | Memberi keputusan arah tindak lanjut secara langsung/manual; keputusannya dicatatkan admin ke log |
| **Teknisi** | Data referensi | Pelaksana perbaikan; hasil kerjanya dicatatkan admin |
| **BIUM** | Pihak eksternal (di luar sistem) | Tujuan pengajuan barang; keputusan & pencairannya dicatatkan admin secara manual |

---

## 5. Ruang Lingkup

### 5.1 Termasuk (in scope)
- **Modul Laporan** — input laporan mengikuti struktur form fisik yang sudah ada
- **Modul Riwayat/Log Tindakan** — pencatatan setiap langkah pada satu laporan (inti fase ini)
- Modul Tindak Lanjut: tindak ulang laboran, perbaikan teknisi, pengajuan ke BIUM
- Modul Verifikasi Penerimaan Barang
- Modul Gudang/Inventaris
- Modul Distribusi (dengan asal barang & tujuan lab/PC — lihat PRD sebelumnya)
- Dashboard rekap & pencarian riwayat — akses admin

### 5.2 Tidak termasuk (out of scope — fase ini)
- Akun sistem untuk laboran, koordinator, teknisi, atau BIUM
- Approval digital dari koordinator/BIUM (tetap manual, dicatatkan admin)
- Notifikasi otomatis
- Aplikasi mobile native

---

## 6. Acuan Form Fisik

Struktur data laporan mengikuti form **"Permintaan Tindakan Perbaikan dan Pencegahan"** yang sudah digunakan, agar admin tidak perlu mengubah kebiasaan mengisi data, hanya memindahkannya ke sistem:

| Field pada form fisik | Dipetakan ke |
|---|---|
| Nomor form | `laporan.nomor_form` |
| Bentuk Ketidaksesuaian | `laporan.bentuk_ketidaksesuaian` |
| Lokasi | `laporan.lab_id` |
| Tanggal & Jam Kejadian | `laporan.tanggal_kejadian`, `laporan.jam_kejadian` |
| Tanggal & Jam Laporan | `laporan.tanggal_laporan`, `laporan.jam_laporan` |
| Hasil/Uraian Pengamatan | `laporan.uraian_pengamatan` |
| Tindakan Langsung | `laporan.tindakan_langsung` *(juga jadi entri pertama di log)* |
| Permintaan Tindakan Perbaikan & Pencegahan | `laporan.permintaan_tindakan` |
| Pelapor (nama, bagian, jabatan) | `laporan.nama_pelapor`, `laporan.bagian`, `laporan.jabatan_pelapor` |
| Verifikasi Hasil Tindakan (Diterima/Ditolak) | entri log tersendiri saat laporan ditutup |
| Status (Closed/Follow Up) | `laporan.status_akhir` |

---

## 7. Alur Proses Lengkap

1. **Laboran** menemukan masalah → mencoba **tindakan langsung** di tempat.
2. Jika masih perlu tindak lanjut, laboran mengisi form **Permintaan Tindakan Perbaikan dan Pencegahan** (tertulis) dan menyerahkannya ke **admin**.
3. **Admin** menginput laporan tersebut ke sistem lengkap dengan tindakan langsung yang sudah dicoba laboran.
4. Admin meneruskan laporan ke **koordinator** untuk mendapat keputusan arah tindak lanjut. Ada 3 kemungkinan:
   - **(a) Tindak ulang oleh laboran** — admin menyampaikan instruksi koordinator ke laboran (di luar sistem), laboran melaksanakan dan melaporkan hasilnya ke admin, admin mencatat hasilnya.
   - **(b) Perbaikan oleh teknisi** — admin menugaskan, teknisi mengerjakan, hasilnya dicatat admin. Dari sini bisa berakhir **selesai**, atau ternyata **perlu pengajuan barang** (lanjut ke langkah 5).
   - **(c) Penggantian barang langsung** — lanjut ke langkah 5.
5. Untuk jalur penggantian barang: admin **cek stok gudang**.
   - Stok tersedia → alokasi langsung, lanjut ke distribusi.
   - Stok tidak tersedia → admin membuat **pengajuan ke BIUM**.
6. Setelah BIUM memutuskan dan barang/dana cair, admin **memverifikasi** kesesuaian jumlah & spesifikasi yang cair vs yang diajukan.
7. Barang yang sesuai masuk ke gudang, lalu **didistribusikan** ke lab (dan PC/unit) yang mengajukan.
8. Admin mengonfirmasi ke laboran bahwa tidak ada masalah lagi → laporan ditandai **selesai (Closed)**.

**Setiap panah pada alur di atas = satu entri baru di log/riwayat tindakan laporan tersebut.**

---

## 8. Modul Utama: Laporan & Riwayat/Log Tindakan

Ini adalah modul inti fase ini. Dua tabel utamanya:

### 8.1 Tabel `laporan`
Menyimpan data induk sesuai form fisik (lihat pemetaan di §6), plus `status_terkini` yang selalu menunjukkan tahap sistem saat ini (misal: "Menunggu Keputusan Koordinator", "Proses Perbaikan Teknisi", "Selesai").

### 8.2 Tabel `riwayat_tindakan` (log)
Setiap baris = satu peristiwa yang terjadi pada satu laporan. **Tidak pernah menimpa baris sebelumnya** — hanya menambah baris baru, sehingga riwayat penuh selalu tersimpan.

| Field | Keterangan |
|---|---|
| `laporan_id` | Laporan mana yang terkait |
| `waktu` | Kapan tindakan terjadi (tanggal & jam) |
| `aktor` | Siapa yang melakukan/memutuskan (misal "Admin", "Koordinator: Pak X", "Teknisi: Y", "Laboran: Octaviana") — diinput admin |
| `jenis_tindakan` | Kategori tindakan (lihat daftar di bawah) |
| `keterangan` | Detail bebas (misal "Jalur: Perbaikan Teknisi", "Baterai A2 tidak tersedia di stok") |
| `status_sebelum` → `status_sesudah` | Perubahan status laporan akibat tindakan ini |

**Daftar jenis tindakan (contoh, dapat diperluas):**
- Laporan Dicatat
- Tindakan Langsung (dicatat dari isian form)
- Diteruskan ke Koordinator
- Keputusan Koordinator (dengan sub-jalur: Tindak Ulang Laboran / Perbaikan Teknisi / Penggantian Barang)
- Instruksi ke Laboran
- Hasil Tindak Ulang Laboran
- Ditugaskan ke Teknisi
- Hasil Perbaikan Teknisi
- Cek Stok Gudang
- Pengajuan ke BIUM
- Keputusan BIUM
- Verifikasi Penerimaan Barang
- Distribusi Barang
- Konfirmasi ke Laboran
- Laporan Ditutup (Closed) / Follow Up

### 8.3 User Stories

| ID | User Story | Kriteria Penerimaan |
|---|---|---|
| L1 | Sebagai admin, saya ingin menginput laporan sesuai struktur form fisik | Semua field pada §6 tersedia di form input, termasuk tindakan langsung yang sudah dicoba laboran |
| L2 | Sebagai admin, saya ingin sistem otomatis membuat entri log pertama saat laporan diinput | Begitu laporan disimpan, otomatis tercipta entri "Laporan Dicatat" dan "Tindakan Langsung" (jika diisi) di `riwayat_tindakan` |
| L3 | Sebagai admin, saya ingin mencatat setiap keputusan/tindakan lanjutan sebagai entri baru | Admin dapat menambah entri log kapan pun tanpa menghapus/menimpa entri sebelumnya, dengan memilih jenis tindakan dari daftar dan mengisi keterangan |
| L4 | Sebagai admin, saya ingin melihat **linimasa lengkap** satu laporan dari awal sampai kondisi terkini | Halaman detail laporan menampilkan seluruh entri `riwayat_tindakan` terurut kronologis, jelas terlihat siapa-kapan-apa di tiap langkah |
| L5 | Sebagai admin, saya ingin mencari/memfilter laporan berdasarkan status, lab, tanggal, atau jenis tindak lanjut | Dashboard laporan mendukung filter kombinasi dan menampilkan status terkini tiap laporan |
| L6 | Sebagai admin, saya ingin mengekspor riwayat satu laporan (atau sekumpulan laporan) sebagai dokumentasi | Tersedia opsi ekspor (misal PDF/Excel) berisi seluruh linimasa log untuk keperluan evaluasi/akreditasi |
| L7 | Sebagai admin, saya ingin tahu laporan mana saja yang belum bergerak/tertahan lama di satu status | Dashboard menandai laporan yang sudah lebih dari X hari tidak ada entri log baru |

---

## 9. Modul Pendukung (ringkas)

Modul berikut menghasilkan entri-entri log di atas — detail user story mengikuti PRD sebelumnya, hanya disesuaikan dengan alur baru (koordinator & BIUM):

- **Perbaikan** — pencatatan penugasan & hasil kerja teknisi.
- **Pengajuan Pengadaan (ke BIUM)** — nomor pengajuan, item, status persetujuan, tanggal cair.
- **Verifikasi Penerimaan** — kesesuaian jumlah & spesifikasi barang cair vs diajukan.
- **Gudang/Inventaris** — stok masuk-keluar, kartu stok, alert stok minimum.
- **Distribusi** — asal barang (dari pengajuan tanggal berapa / stok lama), tujuan lab & PC, penerima.

*(Setiap aksi di modul-modul ini wajib menambah satu entri di `riwayat_tindakan` agar linimasa laporan tetap utuh.)*

---

## 10. Kebutuhan Non-Fungsional

- **Log bersifat append-only**: entri riwayat tidak boleh diedit/dihapus, hanya ditambah — untuk menjaga integritas jejak audit.
- **Ketertelusuran penuh**: dari satu laporan, admin harus bisa melihat seluruh pihak yang terlibat dan keputusan yang diambil tanpa membuka dokumen lain.
- **Kemudahan input cepat**: mengingat admin menginput ulang dari form fisik, form input dan input log dibuat seringkas mungkin.
- **Responsif**: dapat diakses dari desktop maupun HP.
- **Backup berkala**: data laporan & log adalah data historis penting, wajib dicadangkan rutin.

---

## 11. Struktur Data (ringkasan)

| Tabel | Fungsi |
|---|---|
| `labs` | Referensi 13 lab |
| `barang_master` | Katalog jenis barang |
| `laporan` | Data induk laporan, mengikuti form fisik |
| `riwayat_tindakan` | **Log setiap tindakan/keputusan pada laporan (inti sistem)** |
| `perbaikan` | Detail penanganan oleh teknisi |
| `pengajuan_pengadaan` | Pengajuan ke BIUM |
| `pengajuan_item` | Rincian barang yang diajukan |
| `penerimaan_item` | Verifikasi kesesuaian barang cair |
| `stok_gudang` | Stok barang saat ini |
| `stok_transaksi` | Riwayat mutasi stok |
| `distribusi` | Barang keluar ke lab/PC, dengan asal barang |

---

## 12. Asumsi & Pertanyaan Terbuka

- [ ] Apakah keputusan koordinator selalu satu tingkat, atau kadang perlu eskalasi lebih lanjut?
- [ ] Apakah "jenis tindakan" pada log perlu dibatasi dari daftar baku (dropdown), atau admin boleh menambah jenis baru sesuai kebutuhan di lapangan?
- [ ] Apakah nomor form fisik yang sudah ada (misal "F-LAB.KOM-UDINUS-SKT-12-01") tetap dipakai sebagai nomor acuan di sistem, atau sistem membuat nomor baru?
- [ ] Untuk laporan lama yang sudah selesai di form fisik sebelum sistem ini ada, apakah perlu diinput mundur (backlog) ke sistem, atau sistem mulai dari laporan baru saja?

---

## 13. Fase Pengembangan (usulan)

**Fase 1 — MVP (fokus laporan & log)**
- Modul Laporan (input sesuai form fisik)
- Modul Riwayat/Log Tindakan lengkap dengan linimasa
- Dashboard pencarian & filter laporan

**Fase 2**
- Modul Perbaikan, Pengajuan ke BIUM, Verifikasi Penerimaan
- Modul Gudang & Distribusi terhubung otomatis ke log

**Fase 3 (opsional)**
- Akses laboran (input mandiri + lihat status laporannya)
- Akses koordinator untuk memberi keputusan langsung di sistem
- Notifikasi otomatis & laporan analitik lanjutan

---

*Dokumen ini adalah draft PRD revisi yang menempatkan modul Laporan dan Riwayat/Log Tindakan sebagai inti sistem, sebelum masuk ke desain teknis dan pengembangan.*
