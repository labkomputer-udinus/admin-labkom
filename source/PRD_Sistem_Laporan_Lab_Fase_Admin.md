# PRD — Sistem Pengelolaan Laporan Permasalahan Laboratorium
## Fase Awal: Scope Admin

**Versi:** 0.1 (Draft — Fase Awal)
**Tanggal:** 19 Agustus 2026
**Pemilik Produk:** Admin Laboratorium
**Status:** Draft untuk direview

---

## 1. Ringkasan

Fase awal ini membatasi scope sistem hanya untuk digunakan oleh **admin**. Laboran di 13 lab masih menyampaikan laporan secara **tertulis/manual** seperti proses saat ini — sistem belum dibuka aksesnya untuk laboran. Admin bertugas **merekap laporan tertulis tersebut ke dalam sistem**, lalu mengelola seluruh proses tindak lanjut (perbaikan/pengadaan), verifikasi penerimaan barang, gudang, hingga distribusi — semuanya dari sisi admin.

Tujuan fase ini: mendigitalkan pencatatan dan proses kerja admin terlebih dahulu, tanpa mengubah kebiasaan pelaporan laboran. Portal untuk laboran (input mandiri + tracking status) direncanakan sebagai fase berikutnya.

---

## 2. Latar Belakang & Masalah

- Laboran tetap melaporkan masalah secara tertulis (kertas/pesan), belum siap/belum perlu digitalisasi di sisi mereka untuk saat ini.
- Admin yang menerima laporan tertulis dari 13 lab masih merekapnya secara manual, sehingga rawan tercecer, sulit ditelusuri riwayatnya, dan sulit dibuat rekap barang.
- Proses pengajuan ke divisi lain dan pengecekan kesesuaian barang yang cair belum tercatat rapi.
- Belum ada pencatatan stok gudang yang terpusat.

**Fokus fase ini:** memberi admin satu sistem untuk mencatat ulang (input) laporan tertulis yang diterima, lalu mengelola seluruh siklus tindak lanjutnya secara digital — tanpa perlu melibatkan laboran di sistem dulu.

---

## 3. Tujuan Fase Ini

1. Admin dapat memasukkan laporan yang diterima secara tertulis dari laboran ke dalam sistem, per lab.
2. Admin dapat merekap dan mentriase seluruh laporan dari 13 lab dalam satu dashboard.
3. Admin dapat melacak proses pengajuan pengadaan ke divisi lain hingga barang diterima dan diverifikasi.
4. Admin dapat mengelola stok gudang secara terpusat (masuk, keluar, sisa stok).
5. Admin dapat memantau status tiap laporan dari awal sampai selesai — sebagai pengganti catatan manual yang selama ini dipegang admin sendiri.

### Metrik keberhasilan (indikatif)
- Semua laporan tertulis yang diterima admin berhasil direkap ke sistem (tidak ada yang tercecer).
- Admin dapat menelusuri riwayat & status tiap laporan tanpa membuka catatan/arsip fisik.
- Admin dapat menghasilkan rekap barang bermasalah dan rekap stok gudang langsung dari sistem.
- Selisih antara barang yang diajukan vs yang cair selalu tercatat dan dapat ditelusuri.

---

## 4. Target Pengguna & Peran

Fase ini **hanya memiliki satu peran pengguna sistem**:

| Peran | Deskripsi | Akses |
|---|---|---|
| **Admin** | Pengelola pusat seluruh 13 lab | Akses penuh: input laporan (atas nama laboran), triase, pengadaan, verifikasi, gudang, distribusi, dan seluruh status/progres |

**Laboran** belum menjadi pengguna sistem — mereka tetap melapor secara tertulis ke admin di luar sistem. **Divisi lain** juga belum menjadi pengguna sistem — keputusan persetujuan pengadaan dicatat manual oleh admin berdasarkan komunikasi di luar sistem (surat/WA/email).

---

## 5. Ruang Lingkup

### 5.1 Termasuk (in scope — fase admin)
- Input laporan oleh admin (mewakili laporan tertulis dari laboran), termasuk data lab asal & item bermasalah
- Rekap & triase laporan (tentukan perbaikan atau penggantian) — dilakukan admin
- Pencatatan proses perbaikan
- Pengajuan pengadaan ke divisi lain (dicatat oleh admin, status persetujuan diinput manual oleh admin)
- Verifikasi penerimaan barang (jumlah & spesifikasi cair vs diajukan)
- Pengelolaan gudang/inventaris (stok masuk-keluar)
- Distribusi barang ke lab (dicatat oleh admin)
- Dashboard rekap & status — **hanya dapat diakses admin**
- Ekspor rekap (laporan, stok) untuk kebutuhan dokumentasi

### 5.2 Tidak termasuk (out of scope — fase ini)
- Portal/akun untuk laboran (input laporan mandiri, tracking status mandiri) — direncanakan fase berikutnya
- Akun/approval digital untuk divisi lain
- Notifikasi otomatis ke laboran (karena laboran belum jadi pengguna sistem)
- Aplikasi mobile native
- Manajemen multi-admin/hak akses bertingkat (fase ini asumsi 1 admin)

---

## 6. Alur Proses (Fase Admin)

1. **Laboran** melaporkan masalah secara tertulis ke admin (di luar sistem, seperti proses saat ini).
2. **Admin** membuka sistem dan **menginput laporan** tersebut: pilih lab asal, isi item barang bermasalah, deskripsi, jumlah.
3. Admin melakukan **triase** per item: perbaikan atau penggantian unit.
   - **Perbaikan** → admin mencatat pelaksana (teknisi/vendor) dan progresnya → selesai.
   - **Penggantian** → lanjut ke pengecekan stok.
4. Admin **cek stok gudang**:
   - Stok cukup → alokasi langsung dari gudang.
   - Stok tidak cukup → admin membuat **pengajuan pengadaan** ke divisi lain di sistem (dicatat manual berdasarkan komunikasi nyata dengan divisi tersebut).
5. Setelah ada keputusan dari divisi lain (di luar sistem), admin **update status pengajuan** (disetujui/ditolak) dan mencatat kapan barang/dana cair.
6. Admin **memverifikasi** barang yang diterima: bandingkan jumlah & spesifikasi cair vs yang diajukan, tandai sesuai/tidak sesuai.
   - Sesuai → stok gudang bertambah otomatis.
   - Tidak sesuai → dicatat selisihnya untuk tindak lanjut (revisi/retur, dicatat manual oleh admin).
7. Admin **mendistribusikan** barang dari gudang ke lab yang mengajukan, stok berkurang.
8. Admin menandai laporan **selesai**. Seluruh histori dan status dapat dilihat kembali oleh admin kapan saja.

Status laporan (seluruhnya hanya terlihat oleh admin):
`Dicatat` → `Ditriase` → `Proses Perbaikan` *atau* `Menunggu Stok/Pengajuan` → `Menunggu Keputusan Divisi` → `Diverifikasi Penerimaan` → `Siap Distribusi` → `Selesai` (dengan kemungkinan `Ditolak` atau `Perlu Revisi`).

---

## 7. Kebutuhan Fungsional

### 7.1 Modul Input & Rekap Laporan

| ID | User Story | Kriteria Penerimaan |
|---|---|---|
| F1.1 | Sebagai admin, saya ingin menginput laporan yang diterima secara tertulis dari laboran | Form input: pilih lab, nama pelapor (opsional/teks bebas), tanggal laporan diterima, daftar item bermasalah |
| F1.2 | Sebagai admin, saya ingin memasukkan beberapa item barang dalam satu laporan | Satu laporan dapat memiliki banyak item, masing-masing dengan status sendiri |
| F1.3 | Sebagai admin, saya ingin melihat rekap semua laporan dari 13 lab dalam satu tampilan | Dashboard dapat difilter per lab, status, tanggal, jenis tindakan |
| F1.4 | Sebagai admin, saya ingin menentukan tindak lanjut (perbaikan/penggantian) per item | Admin dapat mengubah status dan jenis tindakan per item |
| F1.5 | Sebagai admin, saya ingin melihat riwayat lengkap satu laporan dari awal hingga selesai | Halaman detail laporan menampilkan seluruh histori perubahan status dengan tanggal |

### 7.2 Modul Perbaikan

| ID | User Story | Kriteria Penerimaan |
|---|---|---|
| F2.1 | Sebagai admin, saya ingin mencatat siapa yang menangani perbaikan dan progresnya | Field pelaksana, tanggal mulai/selesai, catatan, opsional biaya |
| F2.2 | Sebagai admin, saya ingin menandai item selesai diperbaiki | Status item berubah jadi "Selesai" |

### 7.3 Modul Pengajuan Pengadaan

| ID | User Story | Kriteria Penerimaan |
|---|---|---|
| F3.1 | Sebagai admin, saya ingin membuat pengajuan ke divisi lain untuk item yang perlu diganti | Pengajuan memiliki nomor, divisi tujuan, daftar item (nama, spesifikasi, jumlah), tanggal ajuan |
| F3.2 | Sebagai admin, saya ingin menggabungkan kebutuhan dari beberapa lab dalam satu pengajuan | Satu pengajuan dapat memuat item dari beberapa laporan/lab berbeda |
| F3.3 | Sebagai admin, saya ingin mencatat status persetujuan secara manual setelah menerima keputusan dari divisi lain | Status: Menunggu / Disetujui / Ditolak, dengan tanggal keputusan yang diinput admin |

### 7.4 Modul Verifikasi Penerimaan

| ID | User Story | Kriteria Penerimaan |
|---|---|---|
| F4.1 | Sebagai admin, saya ingin mencatat jumlah & spesifikasi barang yang benar-benar diterima | Form input per item: jumlah diterima, spesifikasi diterima |
| F4.2 | Sebagai admin, saya ingin sistem menunjukkan selisih antara yang diajukan dan yang diterima | Sistem membandingkan otomatis dan menandai "Sesuai"/"Tidak Sesuai" per item |
| F4.3 | Sebagai admin, saya ingin barang yang sesuai langsung masuk ke stok gudang | Saat status "Sesuai", stok gudang bertambah otomatis dan tercatat di kartu stok |

### 7.5 Modul Gudang/Inventaris

| ID | User Story | Kriteria Penerimaan |
|---|---|---|
| F5.1 | Sebagai admin, saya ingin melihat daftar stok barang yang tersedia di gudang | Daftar barang dengan jumlah stok saat ini, satuan, kategori |
| F5.2 | Sebagai admin, saya ingin melihat riwayat mutasi stok (masuk/keluar) | Kartu stok per barang: tanggal, jenis mutasi, jumlah, referensi |
| F5.3 | Sebagai admin, saya ingin diperingatkan jika stok barang tertentu di bawah batas minimum | Indikator/alert saat stok < stok minimum |

### 7.6 Modul Distribusi

> Kebutuhan tambahan: setiap barang yang didistribusikan harus dapat ditelusuri **asal-usulnya** (dari pengajuan pengadaan tanggal berapa, atau dari stok lama gudang) dan **tujuan detailnya** (lab mana, PC/unit mana) — kebutuhan ini untuk mendukung dokumentasi **akreditasi**, bukan hanya sekadar mencatat "barang keluar".

| ID | User Story | Kriteria Penerimaan |
|---|---|---|
| F6.1 | Sebagai admin, saya ingin mendistribusikan barang dari gudang ke lab yang mengajukan | Input: item laporan tujuan, barang dari stok, jumlah, tanggal kirim |
| F6.2 | Sebagai admin, saya ingin mencatat **asal barang** yang didistribusikan | Saat distribusi, admin memilih sumber barang: (a) **dari pengajuan pengadaan** tertentu — sistem menampilkan nomor & tanggal pengajuan terkait, atau (b) **dari stok lama gudang** — barang yang sudah ada sebelum/tidak terkait pengajuan tertentu. Data ini otomatis tersimpan di catatan distribusi |
| F6.3 | Sebagai admin, saya ingin mencatat **tujuan spesifik** barang, bukan hanya nama lab | Input: lab tujuan (dari 13 lab), dan **nomor/kode PC atau unit tujuan** di lab tersebut (misal "Lab 3 - PC 12"). Field kode PC bersifat teks bebas atau dari daftar aset per lab (tergantung apakah sudah ada daftar aset PC per lab) |
| F6.4 | Sebagai admin, saya ingin mencatat siapa yang menerima barang di lab | Field "diterima oleh" dan tanggal terima (diisi admin berdasarkan konfirmasi laboran di luar sistem) |
| F6.5 | Sistem otomatis menutup laporan setelah distribusi selesai | Status laporan/item berubah menjadi "Selesai" setelah distribusi tercatat |
| F6.6 | Sebagai admin, saya ingin menelusuri riwayat distribusi suatu barang untuk keperluan akreditasi | Admin dapat mencari/filter distribusi berdasarkan: sumber (pengajuan tertentu / stok lama), lab tujuan, atau rentang tanggal, lalu melihat rincian lengkap (barang, jumlah, asal, tujuan lab & PC, tanggal, penerima) — dapat diekspor sebagai bukti dokumentasi akreditasi |

### 7.7 Dashboard & Rekap

| ID | User Story | Kriteria Penerimaan |
|---|---|---|
| F7.1 | Sebagai admin, saya ingin melihat rekap barang yang paling sering dilaporkan bermasalah | Rekap dapat difilter per lab/periode, menampilkan jumlah laporan per jenis barang |
| F7.2 | Sebagai admin, saya ingin mengekspor rekap laporan untuk keperluan dokumentasi | Rekap dapat diunduh (misal Excel/PDF) |
| F7.3 | Sebagai admin, saya ingin melihat ringkasan status seluruh laporan yang masih berjalan | Dashboard menampilkan jumlah laporan per status (misal: 5 menunggu stok, 3 menunggu divisi, dst) |

---

## 8. Kebutuhan Non-Fungsional

- **Single-user access**: fase ini cukup satu akun admin, belum perlu manajemen banyak peran/hak akses.
- **Audit trail**: setiap perubahan status laporan/pengajuan/stok tercatat (kapan, perubahan apa) — penting karena admin bekerja sendiri dan perlu jejak riwayat yang jelas.
- **Kemudahan input cepat**: karena admin menginput ulang dari catatan tertulis, form input sebaiknya ringkas dan cepat diisi (minim klik).
- **Responsif**: dapat diakses dari desktop maupun HP.
- **Ketersediaan data**: data laporan & stok tidak boleh hilang; perlu backup berkala.

---

## 9. Struktur Data (ringkasan — disederhanakan untuk fase admin)

Karena laboran belum menjadi pengguna sistem, tabel `users` untuk fase ini cukup menyimpan **1 akun admin** (tidak perlu role laboran/divisi dulu). Field "lab asal" dan "nama pelapor" pada laporan cukup berupa data referensi (pilihan lab dari daftar 13 lab), bukan akun pengguna aktif.

Entitas utama: `labs` (daftar 13 lab, data referensi), `admin` (1 akun), `laporan`, `laporan_item`, `perbaikan`, `pengajuan_pengadaan`, `pengajuan_item`, `penerimaan_item`, `barang_master`, `stok_gudang`, `stok_transaksi`, `distribusi`.

**Tabel `distribusi` — field tambahan untuk kebutuhan tracking akreditasi:**

| Field | Tipe | Keterangan |
|---|---|---|
| `asal_barang` | enum | `pengajuan` atau `stok_lama` — menandai sumber barang |
| `pengajuan_item_id` | FK (nullable) | Diisi jika `asal_barang = pengajuan`; menghubungkan ke item pengajuan pengadaan tertentu (sehingga tanggal & nomor pengajuan dapat ditelusuri) |
| `lab_tujuan_id` | FK | Lab tujuan distribusi (dari 13 lab) |
| `kode_pc_tujuan` | string | Nomor/kode PC atau unit tujuan di lab tersebut |

Dengan field ini, satu baris distribusi dapat menjawab: *barang apa, berasal dari pengajuan tanggal berapa (atau stok lama), didistribusikan ke lab mana, dan ke PC mana* — inilah dasar data untuk dokumentasi akreditasi.

*(Struktur ini adalah versi sederhana dari ERD lengkap yang sudah dirancang sebelumnya — tabel `users` dengan role laboran/divisi dan akses berbasis peran baru diaktifkan penuh di fase berikutnya.)*

---

## 10. Asumsi & Pertanyaan Terbuka

- [ ] Apakah perlu field untuk mencatat "tanggal laporan tertulis diterima" terpisah dari "tanggal diinput ke sistem" (untuk keperluan audit ketepatan waktu admin menindaklanjuti)?
- [ ] Apakah admin ingin menyimpan/scan foto laporan tertulis asli sebagai lampiran di sistem?
- [ ] Apakah cukup satu akun admin, atau ada kemungkinan lebih dari satu orang mengelola sistem ini ke depannya?
- [ ] Apakah rekap yang diekspor perlu format tertentu (misal mengikuti format laporan instansi yang sudah ada)?

---

## 11. Kaitan dengan Fase Berikutnya

Fase ini dirancang agar strukturnya **kompatibel** dengan fase lanjutan (portal laboran + akses divisi lain), sehingga saat laboran mulai diberi akses:
- Data laporan yang sudah diinput admin tidak perlu dimigrasi ulang.
- Tabel `users` tinggal diperluas dengan role `laboran` dan `divisi`, dihubungkan ke lab masing-masing.
- Modul status yang sudah ada tinggal dibuka aksesnya (read-only) untuk laboran.

---

*Dokumen ini adalah draft PRD fase awal (scope admin) untuk didiskusikan lebih lanjut sebelum masuk tahap desain teknis dan pengembangan.*
