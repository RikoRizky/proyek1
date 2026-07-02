# Panduan Pengguna (User Guide)
## Sistem Manajemen & Pemantauan Dokumen Persyaratan (Akreditasi)

Panduan ini ditujukan bagi seluruh aktor yang berinteraksi dengan sistem, yang mencakup Administrator, Perguruan Tinggi (Perti), dan Program Studi (Unit Kerja).

---

### 1. Peran Program Studi (Unit Kerja)

Program Studi bertanggung jawab mengumpulkan dokumen pendukung untuk setiap butir kriteria yang disyaratkan.

#### 1.1 Melihat Progres Pengumpulan
1. Masuk ke aplikasi menggunakan akun Program Studi Anda.
2. Di halaman **Dashboard**, Anda akan disuguhkan informasi ringkasan:
   - Persentase total dokumen yang telah berhasil diunggah.
   - Grafik batang progres per kriteria/modul.
   - Jumlah dokumen terunggah vs dokumen yang belum lengkap.
3. Buka menu **Progress** di sidebar untuk melihat status detail per butir kriteria.

#### 1.2 Mengunggah Dokumen & Tautan Google Drive
Ada dua metode yang dapat digunakan untuk memperbarui dokumen:

**Metode A: Unggah Secara Detail (Per Butir Persyaratan)**
1. Klik butir persyaratan yang ingin dilengkapi pada halaman progres atau menu **Submissions**.
2. Klik tombol **Unggah Dokumen** / **Edit**.
3. Di dalam modal yang muncul:
   - Pilih berkas lokal dari komputer Anda (PDF, Docx, xlsx, dll.).
   - Dan/atau masukkan tautan **Google Drive** yang valid pada kolom yang disediakan.
4. Klik **Simpan**. Status butir tersebut akan otomatis berubah dari **Menunggu unggah** menjadi **Terunggah**.

**Metode B: Batch Upload (Sekaligus per Kriteria)**
1. Masuk ke menu **Submissions** lalu pilih Kriteria/Modul terkait.
2. Anda akan melihat tombol **Batch Upload** di bagian atas tabel.
3. Anda dapat memilih beberapa file sekaligus dan mengunggahnya dalam satu kali proses untuk butir-butir yang berbeda.

#### 1.3 Mengunduh Rekap Progres
- Klik menu **Laporan / Download Rekap PDF** untuk mengunduh rekapitulasi progres pengerjaan Anda dalam bentuk dokumen cetak PDF yang rapi.

---

### 2. Peran Perguruan Tinggi (Perti)

Perguruan Tinggi bertindak sebagai pemantau kualitas dan progres seluruh Program Studi di bawah naungannya.

#### 2.1 Mengelola Data Program Studi (Prodi)
1. Setelah login, pilih menu **Program Studi** di sidebar.
2. Untuk menambahkan Prodi baru:
   - Klik **Tambah Program Studi**.
   - Isi nama prodi, email prodi, dan kata sandi untuk akun prodi tersebut.
   - Klik **Simpan**. Akun tersebut otomatis memiliki peran `unit_kerja` dengan relasi induk ke Perti Anda.

#### 2.2 Memantau Progres Program Studi
1. Pada **Dashboard**, Anda akan melihat statistik ringkas jumlah prodi di bawah naungan Anda beserta rata-rata persentase penyelesaian pengumpulan dokumen.
2. Pilih menu **Program Studi** lalu klik tautan **Progress** pada salah satu prodi untuk melihat secara spesifik kriteria mana saja yang sudah atau belum diselesaikan oleh prodi tersebut.
3. Anda dapat memeriksa berkas yang diunggah oleh prodi dengan mengklik berkas terkait secara langsung untuk membukanya (*inline preview*) atau mengunduhnya.

#### 2.3 Mengunduh Rekap PDF
- Anda dapat mengunduh berkas laporan rekapitulasi progres seluruh prodi di bawah naungan Anda dengan mengklik tombol **Cetak PDF / Download Report** pada halaman rekapitulasi.

---

### 3. Peran Administrator (Admin)

Administrator mengendalikan data master sistem, termasuk Kriteria (Modul) dan Akun Perguruan Tinggi.

#### 3.1 Mengelola Modul & Persyaratan (Kriteria)
1. Pilih menu **Modul** di sidebar.
2. Anda dapat menambahkan modul kriteria baru (misal: "Kriteria 9: Luaran dan Capaian Tridharma").
3. Di setiap modul, klik tombol **Kelola Persyaratan** untuk menambahkan butir-butir dokumen pendukung yang wajib dikumpulkan oleh unit kerja.
4. Tentukan `sort_order` agar urutan kriteria tampil berurutan secara logis.

#### 3.2 Mengelola Akun Pengguna (Users)
1. Masuk ke menu **Manajemen User**.
2. Anda dapat menambah, mengubah, atau menonaktifkan akun bertipe **Administrator**, **Perguruan Tinggi (Perti)**, dan **Program Studi (Unit Kerja)**.
3. Pastikan kolom **Perti Induk** terisi dengan benar untuk pengguna dengan peran Program Studi agar relasi pemantauan berfungsi.

#### 3.3 Memantau Seluruh Pengumpulan & Laporan
1. Di halaman **Dashboard**, Admin disajikan ringkasan analitik dari seluruh Perguruan Tinggi dan Prodi di sistem.
2. Admin dapat melihat riwayat unggahan berkas terbaru di menu **Semua Submissions** dan mengunduh berkas tersebut.
3. Admin juga dapat mengunduh laporan global progres seluruh institusi dalam bentuk PDF.

#### 3.4 Mengelola Lead Diskusi
1. Akses menu **Discussions / Permintaan Diskusi**.
2. Halaman ini menampilkan semua data prospek/tamu yang mengisi formulir kontak di landing page publik.
3. Anda dapat melihat kebutuhan spesifik mereka, sistem saat ini yang mereka gunakan, anggaran mereka, dan menindaklanjutinya via email atau tautan WhatsApp yang disediakan.
