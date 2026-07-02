# BAB 4 DESAIN ANTARMUKA

## 4.1 Konsep Desain

Aplikasi **SiLADATA** dirancang dengan mengutamakan aspek keindahan (*rich aesthetics*), kejelasan informasi, dan kemudahan navigasi (*user-friendly*). Beberapa konsep desain utama yang diterapkan meliputi:

1. **Tata Letak Bersih (*Clean Layout*):** Menghindari elemen visual yang terlalu padat. Pemanfaatan ruang putih (*whitespace*) yang optimal memberikan kenyamanan mata bagi pengguna saat membaca data borang dokumen akreditasi.
2. **Skema Warna Kontras yang Harmonis:** 
   - Warna biru dongker (*slate*) dan putih mendominasi sebagai warna dasar dasbor untuk menampilkan kesan profesional.
   - Status kelengkapan dokumen dipertegas dengan penanda warna kontras tinggi:
     - **Hijau (Emerald/Sky Blue):** Menandakan status **Terunggah** (*Uploaded*), memberikan kepastian visual bahwa persyaratan telah dipenuhi.
     - **Merah/Abu-abu (Slate/Red):** Menandakan status **Menunggu unggah** (*Pending*), memicu urgensi agar unit kerja segera melengkapinya.
3. **Tipografi Modern:** Menggunakan font sans-serif (Inter atau Roboto) yang bersih dan mudah dibaca pada berbagai ukuran layar perangkat.
4. **Antarmuka Responsif (*Responsive Design*):** Tata letak halaman menyesuaikan dengan berbagai resolusi layar, mulai dari monitor desktop kantor hingga layar ponsel pintar.

---

## 4.2 Mockup / Wireframe Halaman Utama

Berikut adalah representasi mockup tata letak (*wireframe*) antarmuka aplikasi SiLADATA berbasis teks Markdown:

### 4.2.1 Landing Page (Akses Tamu)
```text
+-----------------------------------------------------------------------------+
|  [SiLADATA Logo]      Fitur      Harga/Paket      Hubungi Kami    [ Login ] |
+-----------------------------------------------------------------------------+
|                                                                             |
|      Solusi Cepat Manajemen Dokumen Akreditasi Perguruan Tinggi             |
|      Kelola borang kriteria akreditasi Anda secara terstruktur dan aman.    |
|                                                                             |
|                     [ Mulai Konsultasi Sekarang ]                           |
|                                                                             |
+-----------------------------------------------------------------------------+
|  [ PILIHAN PAKET LAYANAN ]                                                  |
|  +---------------------+  +---------------------+  +---------------------+  |
|  |     Paket Basic     |  |    Paket Standard   |  |   Paket Enterprise  |  |
|  |   Rp 1.500.000/bln  |  |   Rp 3.500.000/bln  |  |      Hubungi Kami   |  |
|  +---------------------+  +---------------------+  +---------------------+  |
+-----------------------------------------------------------------------------+
|  [ FORMULIR KONSULTASI / DISKUSI ]                                          |
|  Nama Lengkap : [________________________]                                  |
|  Email        : [________________________]                                  |
|  No. WhatsApp : [________________________]                                  |
|  Institusi    : [________________________]                                  |
|  Rencana Investasi: ( ) Dekat   ( ) Sedang Perencanaan  ( ) Nanti           |
|                                                                             |
|                           [ Kirim Pengajuan ]                               |
+-----------------------------------------------------------------------------+
```

### 4.2.2 Dashboard Admin
```text
+-----------------------------------------------------------------------------+
|  SiLADATA [Admin]                             [Foto Profil] Halo, Admin! v  |
+-----------------------------------------------------------------------------+
|  [Menu]          |  [ STATISTIK GLOBAL ]                                    |
|  - Dashboard     |  +-------------------+  +-------------------+            |
|  - Kelola User   |  | 5 Perguruan Tinggi|  |  24 Program Studi |            |
|  - Kelola Modul  |  +-------------------+  +-------------------+            |
|  - Submissions   |  | 9 Modul Kriteria  |  |  65% Dokumen Ok   |            |
|  - Diskusi Lead  |  +-------------------+  +-------------------+            |
|  - Cetak PDF     |                                                          |
|                  |  [ GRAFIK AKUMULASI PROGRES PROGRAM STUDI ]              |
|                  |  - Prodi Teknik Informatika  : [==================> ] 75% |
|                  |  - Prodi Akuntansi           : [==========>         ] 45% |
|                  |  - Prodi Manajemen Bisnis    : [====================] 100%|
+-----------------------------------------------------------------------------+
```

### 4.2.3 Dashboard Perguruan Tinggi (Perti)
```text
+-----------------------------------------------------------------------------+
|  SiLADATA [Perti]                             [Foto Profil] Universitas A v |
+-----------------------------------------------------------------------------+
|  [Menu]          |  [ DAFTAR PRODI DI BAWAH UNIVERSITAS A ]                 |
|  - Dashboard     |  [+ Tambah Prodi]   [Cetak Rekap PDF]                    |
|  - Program Studi |  +----------------------------------------------------+  |
|  - Progress      |  | No | Nama Program Studi     | Progres  | Aksi      |  |
|  - Submissions   |  +----+------------------------+----------+-----------+  |
|  - Cetak PDF     |  | 1  | D4 Teknik Informatika  |   75%    | [Detail]  |  |
|                  |  | 2  | D3 Logistik Bisnis     |   90%    | [Detail]  |  |
|                  |  | 3  | S1 Akuntansi           |   20%    | [Detail]  |  |
|                  |  +----+------------------------+----------+-----------+  |
+-----------------------------------------------------------------------------+
```

### 4.2.4 Halaman Submissions (Akses Unit Kerja)
```text
+-----------------------------------------------------------------------------+
|  SiLADATA [Unit Kerja]                        [Foto Profil] TI - ULBI v     |
+-----------------------------------------------------------------------------+
|  [Menu]          |  [ KRITERIA 3: MAHASISWA ]                               |
|  - Dashboard     |  [<- Kembali]   [Batch Upload Dokumen]   [Download PDF]  |
|  - Progress      |  +----------------------------------------------------+  |
|  - Submissions   |  | No | Deskripsi Syarat        | Status      | Aksi      |  |
|  - Cetak PDF     |  +----+-------------------------+-------------+-----------+  |
|                  |  | 1  | Data IPK Lulusan 3 Thn  | [Terunggah] | [Detail]  |  |
|                  |  | 2  | Kuesioner Tracer Study  | [Menunggu]  | [Unggah]  |  |
|                  |  | 3  | Berkas Prestasi Mhs     | [Terunggah] | [Detail]  |  |
|                  |  +----+-------------------------+-------------+-----------+  |
+-----------------------------------------------------------------------------+
```

---

## 4.3 Deskripsi Tampilan dan Komponen Interaktif

1. **Tombol Unggah Dokumen Individu:** Ditemukan pada halaman daftar submission Unit Kerja. Saat diklik, tombol ini membuka jendela **Modal Interaktif** yang berisi formulir unggah berkas (tipe PDF, Word, dsb.) serta kolom isian teks untuk tautan Google Drive.
2. **Modal Batch Upload:** Memungkinkan program studi mengunggah beberapa dokumen sekaligus untuk butir-butir kriteria yang berbeda di dalam satu modul, meningkatkan efisiensi kerja tim akreditasi.
3. **Pratinjau Dokumen (*Inline Preview*):** Pada tabel daftar dokumen bagi Admin dan Perti, terdapat ikon mata (viewer). Ketika ikon diklik, sistem membuka modal pratinjau berkas secara langsung di dalam browser menggunakan *iframe viewer* tanpa memaksa pengguna mengunduh berkas secara lokal.
4. **Tombol Cetak PDF / Rekap PDF:** Tersedia di panel atas dashboard dan halaman progres. Tombol ini memicu pembuatan dokumen PDF secara *on-the-fly* di sisi server dan menampilkan jendela dialog unduhan berkas PDF formal bagi pengguna.
5. **Formulir Diskusi Landing Page:** Berisi elemen validasi instan (seperti input email valid, format nomor WhatsApp menggunakan prefix internasional, dan pemilihan kategori kebutuhan aplikasi menggunakan checkbox).
6. **Hubungi via WhatsApp:** Tombol dinamis pada panel admin diskusi yang secara otomatis merangkai tautan `wa.me` lengkap dengan teks template pra-isi yang mengambil data nama dan perusahaan calon klien untuk mempermudah tindak lanjut.
