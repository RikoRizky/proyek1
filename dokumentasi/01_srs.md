# Software Requirements Specification (SRS)
## Sistem Manajemen & Pemantauan Dokumen Persyaratan (Akreditasi)

Dokumen ini mendeskripsikan spesifikasi kebutuhan perangkat lunak untuk sistem manajemen dan pemantauan dokumen persyaratan akreditasi program studi di bawah perguruan tinggi.

---

### 1. Deskripsi Umum Sistem
Sistem ini dirancang untuk memfasilitasi pengelolaan, pengumpulan, dan pemantauan dokumen bukti kinerja (persyaratan/akreditasi) dari Program Studi (Unit Kerja) di bawah naungan Perguruan Tinggi (Perti). Sistem ini membagi hak akses menjadi tiga peran utama, yaitu Administrator, Perguruan Tinggi (Perti), dan Program Studi (Unit Kerja), serta menyediakan landing page publik dengan fitur formulir konsultasi/diskusi.

---

### 2. Aktor dan Peran (Roles)
Sistem memiliki 3 aktor terautentikasi dan 1 aktor publik:

1. **Administrator (Admin)**
   - Memiliki kendali penuh terhadap sistem.
   - Mengelola data pengguna (User CRUD) untuk Perti dan Program Studi.
   - Mengelola struktur Kriteria (Module) dan Butir Persyaratan (Requirement).
   - Memantau progres unggahan semua unit kerja dan melihat detail dokumen yang dikumpulkan.
   - Mengunduh laporan rekapitulasi progres dalam format PDF.
   - Mengelola daftar pengajuan formulir diskusi/konsultasi dari pihak luar.

2. **Perguruan Tinggi (Perti)**
   - Mewakili institusi perguruan tinggi induk.
   - Mengelola daftar Program Studi (Prodi) di bawah naungannya.
   - Memantau progres unggahan dokumen dari setiap Prodi di bawah naungannya secara waktu nyata (*real-time*).
   - Mengunduh rekap laporan progres program studi dalam bentuk PDF.
   - Melihat detail submission/dokumen yang diunggah oleh Prodi.

3. **Program Studi / Unit Kerja (Unit Kerja)**
   - Mewakili unit kerja/program studi yang mengumpulkan dokumen.
   - Melihat daftar modul (kriteria) dan butir persyaratan yang harus dipenuhi.
   - Mengunggah dokumen pendukung untuk setiap butir persyaratan (baik secara individual maupun sekaligus/*batch*).
   - Memasukkan tautan dokumen pendukung melalui tautan eksternal (Google Drive).
   - Mengunduh rekapitulasi progres pengumpulan dokumen dalam format PDF.

4. **Tamu (Guest / Publik)**
   - Mengakses landing page utama.
   - Melihat informasi harga dan paket layanan.
   - Mengisi formulir pengajuan diskusi/konsultasi terkait kebutuhan sistem.

---

### 3. Kebutuhan Fungsional (Functional Requirements)

| ID Kebutuhan | Fitur Utama | Deskripsi Fitur | Peran Terkait |
| :--- | :--- | :--- | :--- |
| **FR-01** | Autentikasi | Login, logout, registrasi (jika diaktifkan), reset password, serta pengelolaan profil pengguna (termasuk foto profil). | Semua Aktor Terautentikasi |
| **FR-02** | Dashboard | Tampilan statistik ringkas mengenai progres unggahan dokumen, jumlah modul, dan jumlah unit kerja. | Admin, Perti, Unit Kerja |
| **FR-03** | Manajemen User | CRUD data pengguna serta penentuan peran (*role*) dan relasi induk (*perti_id*). | Admin |
| **FR-04** | Manajemen Prodi | CRUD data Program Studi yang berada di bawah Perguruan Tinggi terkait. | Perti |
| **FR-05** | Pengelolaan Kriteria | CRUD Modul (Kriteria) dan Requirement (Persyaratan) beserta pengurutan (*sort_order*). | Admin |
| **FR-06** | Pengumpulan Dokumen | Mengunggah file dokumen, mengunggah multi-dokumen (*batch*), menyimpan nama file asli, mencatat ukuran dan tipe MIME, serta menyimpan tautan Google Drive. | Unit Kerja |
| **FR-07** | Versioning Dokumen | Menyimpan riwayat perubahan dokumen dengan sistem versi, di mana hanya dokumen terbaru yang ditandai sebagai `is_latest = true`. | Unit Kerja, Admin, Perti |
| **FR-08** | Pemantauan Dokumen | Memantau status unggahan (Terunggah vs Menunggu unggah) per prodi atau kriteria, serta melakukan *inline preview* atau unduh langsung. | Admin, Perti, Unit Kerja |
| **FR-09** | Rekap Laporan (PDF) | Mengunduh rekap progres pengumpulan dokumen ke dalam format PDF. | Admin, Perti, Unit Kerja |
| **FR-10** | Formulir Diskusi | Pengunjung dapat mengirimkan konsultasi kebutuhan sistem melalui form yang kemudian dapat ditindaklanjut oleh Admin. | Guest, Admin |

---

### 4. Kebutuhan Non-Fungsional (Non-Functional Requirements)
1. **Keamanan (Security)**
   - Akses rute dilindungi oleh middleware berbasis peran (`role:admin`, `role:perti`, `role:unit_kerja`).
   - Dokumen yang diunggah disimpan dengan aman di penyimpanan server/cloud terproteksi.
2. **Kinerja (Performance)**
   - Penghitungan persentase progres unggahan dokumen dioptimalkan menggunakan query relasional Eloquent dengan relasi `latestForUnit`.
3. **Penyimpanan (Storage)**
   - Mendukung penyimpanan multi-dokumen per kriteria baik lokal maupun tautan pihak ketiga (Google Drive).
