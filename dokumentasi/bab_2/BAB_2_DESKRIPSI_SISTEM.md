# BAB 2 DESKRIPSI SISTEM

## 2.1 Gambaran Umum Aplikasi

**SiLADATA (Sistem Layanan Dokumen Akreditasi)** dirancang sebagai solusi komprehensif berbasis web untuk menyederhanakan proses manajemen, pengumpulan, dan pemantauan dokumen bukti kinerja akreditasi di tingkat perguruan tinggi. Alur kerja global dari aplikasi ini mengintegrasikan seluruh elemen penjaminan mutu dalam satu alur yang runut dan transparan:

1. **Inisialisasi Standar Akreditasi:** Administrator menetapkan struktur kriteria utama (Modul) beserta butir-butir bukti fisik (Requirements) yang wajib diisi. Hal ini bersifat dinamis sehingga dapat disesuaikan dengan berbagai standar akreditasi (seperti BAN-PT 9 Kriteria, LAM-INFOKOM, LAM-TEKNIK, dsb.).
2. **Pemetaan Pengguna:** Administrator mendaftarkan perguruan tinggi (Perti). Pihak Perti kemudian mengelola dan mendaftarkan program studi/unit kerja (Prodi) yang berada langsung di bawah naungannya.
3. **Proses Pengumpulan Dokumen:** Unit Kerja login dan langsung disajikan dengan daftar kriteria. Mereka dapat mengunggah berkas secara individual maupun sekaligus (*batch upload*), serta menyematkan tautan dokumen Google Drive untuk file berukuran sangat besar.
4. **Pelacakan Versi dan Validasi:** Setiap kali berkas baru dikirimkan, sistem secara otomatis melakukan pengarsipan berkas versi sebelumnya dan mengaktifkan versi terbaru sebagai berkas resmi (*latest version*). Status dokumen berubah dari **Menunggu unggah** menjadi **Terunggah**.
5. **Pemantauan dan Evaluasi Real-Time:** Pimpinan Perguruan Tinggi (Perti) dan Administrator dapat melihat progres kelengkapan dokumen seluruh prodi dalam bentuk grafik persentase interaktif di dasbor. Mereka dapat memeriksa kelayakan dokumen secara langsung (*inline preview*) tanpa harus mengunduh file terlebih dahulu, serta mengekspor rekapitulasi progres dalam bentuk PDF untuk keperluan evaluasi offline.

---

## 2.2 Stakeholder dan User (Aktor dan Peran)

Sistem membagi hak akses pengguna ke dalam empat aktor dengan batasan operasional sebagai berikut:

### 1. Administrator (Admin)
- **Peran:** Pengelola tingkat tertinggi sistem.
- **Hak Akses & Batasan Operasional:**
  - Melakukan operasi CRUD pengguna (Admin, Perti, Unit Kerja) beserta penetapan relasi `perti_id`.
  - Melakukan konfigurasi data master modul kriteria (`modules`) dan persyaratan (`requirements`), termasuk pengurutan tampilan (`sort_order`).
  - Memantau progres penyelesaian unggahan dokumen dari seluruh unit kerja secara global.
  - Memeriksa file unggahan dan tautan Google Drive dari semua unit kerja.
  - Mengunduh rekap laporan progres global dalam format PDF.
  - Mengakses panel kelola pesan konsultasi dari formulir diskusi landing page.

### 2. Perguruan Tinggi (Perti)
- **Peran:** Pimpinan perguruan tinggi induk/tim penjaminan mutu tingkat universitas.
- **Hak Akses & Batasan Operasional:**
  - Melakukan CRUD akun Program Studi (Unit Kerja) di bawah naungannya.
  - Memantau secara real-time persentase progres kelengkapan dokumen masing-masing prodi di bawah naungannya.
  - Melihat detail dokumen, melakukan pratinjau langsung (*inline viewer*), atau mengunduh dokumen yang telah dikumpulkan prodi.
  - Mengunduh laporan rekapitulasi progres seluruh prodi di bawah naungannya dalam format PDF.
  - Tidak memiliki akses untuk mengubah struktur modul kriteria maupun butir persyaratan.

### 3. Program Studi / Unit Kerja (Unit Kerja)
- **Peran:** Tim penyusun borang akreditasi program studi.
- **Hak Akses & Batasan Operasional:**
  - Melihat daftar kriteria (modul) dan butir persyaratan dokumen yang harus dipenuhi.
  - Mengunggah satu atau beberapa berkas dokumen pendukung secara mandiri atau secara massal (*batch*).
  - Menyematkan tautan berkas dari Google Drive.
  - Memantau progres pemenuhan dokumen miliknya sendiri melalui visualisasi dasbor.
  - Mengunduh laporan rekapitulasi progres pengumpulan dokumen miliknya sendiri dalam format PDF.
  - Tidak dapat melihat progres maupun berkas unggahan dari program studi lain.

### 4. Tamu (Guest / Publik)
- **Peran:** Pihak eksternal, calon mitra, atau pengguna umum yang belum terautentikasi.
- **Hak Akses & Batasan Operasional:**
  - Mengakses landing page informasi aplikasi SiLADATA.
  - Melihat halaman daftar harga dan paket layanan yang ditawarkan.
  - Mengisi formulir interaktif pengajuan diskusi/konsultasi (nama, email, WhatsApp, kebutuhan sistem, anggaran investasi) untuk dikirimkan kepada Administrator.

---

## 2.3 Kebutuhan Fungsional (Functional Requirements)

Kebutuhan fungsional sistem SiLADATA dijabarkan dalam tabel kebutuhan berikut:

| ID Kebutuhan | Nama Kebutuhan | Deskripsi Fungsional | Aktor Terkait |
| :--- | :--- | :--- | :--- |
| **FR-01** | Autentikasi Sistem | Sistem harus menyediakan fitur Login, Logout, dan pengamanan rute. Pengguna juga dapat memperbarui informasi profil mereka beserta foto profil. | Admin, Perti, Unit Kerja |
| **FR-02** | Visualisasi Dasbor | Sistem harus menampilkan statistik ringkas progres pengunggahan dokumen dalam bentuk angka rekapitulasi dan persentase grafis yang disesuaikan dengan peran pengguna. | Admin, Perti, Unit Kerja |
| **FR-03** | Manajemen Pengguna | Admin dapat mengelola (CRUD) seluruh data pengguna sistem (Admin, Perti, Unit Kerja) dan memetakan unit kerja ke perguruan tinggi induknya. | Admin |
| **FR-04** | Manajemen Program Studi | Pengguna Perti dapat mengelola (CRUD) akun Program Studi khusus yang berada di bawah perguruan tingginya sendiri. | Perti |
| **FR-05** | Pengelolaan Kriteria | Admin dapat mengelola (CRUD) Modul Kriteria dan butir Persyaratan Dokumen, lengkap dengan pengaturan urutan tampilan (*sort_order*). | Admin |
| **FR-06** | Pengumpulan Dokumen | Unit Kerja dapat mengunggah berkas dokumen (tunggal atau banyak berkas sekaligus/*batch*) serta memasukkan tautan Google Drive untuk memenuhi butir persyaratan. | Unit Kerja |
| **FR-07** | Versioning Dokumen | Sistem harus mengelola versi dokumen secara otomatis. Jika dokumen baru diunggah untuk persyaratan yang sama, berkas lama diarsipkan (`is_latest = false`) dan berkas baru diset sebagai versi terkini (`is_latest = true`). | Unit Kerja, Admin, Perti |
| **FR-08** | Pemantauan & Pratinjau | Pengguna dapat melihat daftar status pengumpulan dokumen. Admin dan Perti dapat melakukan pratinjau langsung berkas dokumen di dalam browser (*inline preview*) atau mengunduhnya. | Admin, Perti, Unit Kerja |
| **FR-09** | Rekap PDF | Sistem dapat men-generate dokumen PDF formal yang berisi laporan rekapitulasi progres kelengkapan dokumen akreditasi. | Admin, Perti, Unit Kerja |
| **FR-10** | Formulir Diskusi Tamu | Tamu dapat mengirimkan formulir konsultasi kebutuhan sistem melalui landing page publik, dan Admin dapat melihat rekap pengajuan diskusi tersebut pada panel administrator. | Tamu, Admin |

---

## 2.4 Kebutuhan Non-Fungsional (Non-Functional Requirements)

### 1. Keamanan (*Security*)
- **Middleware Proteksi Peran:** Seluruh rute dilindungi oleh sistem autentikasi sesi Laravel dan didistribusikan melalui middleware khusus untuk mengecek peran pengguna (`role:admin`, `role:perti`, `role:unit_kerja`).
- **Pencegahan Akses Ilegal:** Pengguna Unit Kerja dibatasi secara ketat hanya dapat mengakses data dan mengunggah dokumen miliknya sendiri, serta tidak dapat mengakses folder penyimpanan server prodi lain melalui manipulasi URL rute.
- **Enkripsi Sandi:** Semua kata sandi disimpan menggunakan algoritma enkripsi satu arah yang aman (`bcrypt`).

### 2. Kinerja (*Performance*)
- **Optimasi Query Progres:** Perhitungan progres kelengkapan kriteria yang kompleks dioptimalkan menggunakan database query Eloquent dengan *eager loading* relasi dan penerapan scope `latestForUnit` pada model `Submission`. Hal ini menghindari isu *N+1 query* saat memuat banyak program studi atau modul kriteria sekaligus.
- **Pemuatan Berkas Ringan:** Pratinjau file (*inline viewer*) menggunakan integrasi viewer native browser untuk menjaga waktu respon server tetap cepat dan hemat bandwidth.

### 3. Penyimpanan (*Storage*)
- **Struktur Berkas Lokal:** Dokumen fisik yang diunggah disimpan di folder server `/storage/app/public/uploads/` yang dipetakan secara publik melalui symbolic link (`php artisan storage:link`) demi keamanan akses.
- **Integrasi Cloud Link:** Mendukung penyimpanan tautan eksternal (Google Drive) dalam format JSON untuk menghemat penyimpanan server lokal institusi ketika menangani dokumen bukti berukuran sangat besar (seperti video profil, dokumen scan tebal, dsb.).
