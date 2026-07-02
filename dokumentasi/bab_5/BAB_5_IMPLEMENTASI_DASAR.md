# BAB 5 IMPLEMENTASI DASAR

## 5.1 Tools dan Teknologi

Implementasi sistem SiLADATA memanfaatkan ekosistem pengembangan web modern untuk menjamin performa, keamanan, dan keandalan sistem:

1. **Bahasa Pemrograman & Framework:** PHP (versi 8.2+) dengan **Laravel Framework** (versi 11.x) sebagai kerangka kerja MVC utama.
2. **Basis Data (*Database Engine*):** **MySQL** (untuk lingkungan produksi) dan **SQLite** (untuk kemudahan pengujian lokal). Relasi data dikelola penuh melalui **Eloquent ORM** bawaan Laravel.
3. **Penyusunan Desain UI:** HTML5, CSS3, dan **Tailwind CSS** untuk penyusunan komponen dasbor yang modern, cepat, dan responsif.
4. **Library Pembuatan Dokumen:** **DomPDF** (melalui package *barryvdh/laravel-dompdf*) sebagai *Report PDF Engine* untuk mengonversi data HTML/Blade secara langsung menjadi dokumen PDF formal siap cetak.
5. **Diagram Engine:** **Graphviz (DOT Language)** untuk mendokumentasikan arsitektur sistem dan alur proses bisnis secara visual.

---

## 5.2 Struktur Folder Proyek

Aplikasi SiLADATA diorganisasi mengikuti arsitektur folder Laravel standar dengan struktur modul kontrol yang terpisah berdasarkan peran pengguna:

```text
proyek1/
├── app/
│   ├── Enums/
│   │   ├── SubmissionStatus.php   # Status berkas (Pending/Uploaded)
│   │   └── UserRole.php           # Definisikan Peran (Admin/Perti/UnitKerja)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/             # Pengendali halaman Administrator
│   │   │   │   ├── AnalyticsController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── DiscussionController.php
│   │   │   │   ├── ModuleController.php
│   │   │   │   ├── ReportController.php
│   │   │   │   ├── RequirementController.php
│   │   │   │   ├── SubmissionOverviewController.php
│   │   │   │   └── UserController.php
│   │   │   ├── Perti/             # Pengendali halaman Perguruan Tinggi
│   │   │   │   ├── ProdiController.php
│   │   │   │   ├── ProdiProgressController.php
│   │   │   │   └── SubmissionController.php
│   │   │   ├── UnitKerja/         # Pengendali halaman Program Studi
│   │   │   │   ├── ProgressController.php
│   │   │   │   └── SubmissionController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── DiscussionController.php
│   │   │   └── HomeController.php
│   ├── Models/
│   │   ├── User.php               # Model pengguna & relasi perti_id
│   │   ├── Module.php             # Model kriteria utama
│   │   ├── Requirement.php        # Model butir persyaratan kriteria
│   │   ├── Submission.php         # Model berkas unggahan & versioning
│   │   └── Discussion.php         # Model pengajuan konsultasi tamu
│   └── Support/
│       └── UploadProgress.php     # Helper kalkulasi progres persentase unggahan
├── config/                        # Berkas konfigurasi sistem aplikasi
├── database/
│   ├── migrations/                # Berkas rancangan skema tabel basis data
│   └── seeders/                   # Data awal bawaan sistem (Default Users & Modul)
├── resources/
│   ├── views/                     # Template antarmuka (.blade.php)
│   │   ├── admin/                 # Halaman panel Administrator
│   │   ├── perti/                 # Halaman panel Perguruan Tinggi
│   │   ├── unit/                  # Halaman panel Program Studi
│   │   ├── home/                  # Halaman tamu (Landing page & Harga)
│   │   └── layouts/               # Template dasar navigasi & sidebar
├── routes/
│   ├── auth.php                   # Rute autentikasi bawaan (Laravel Breeze)
│   └── web.php                    # Rute utama aplikasi dengan filter middleware
├── storage/                       # Direktori penyimpanan lokal berkas fisik unggahan
└── vite.config.js                 # Konfigurasi kompilasi aset Frontend
```

---

## 5.3 Petunjuk Menjalankan Aplikasi

Ikuti instruksi baris perintah (*terminal bash*) berikut untuk memasang dan menjalankan aplikasi SiLADATA pada lingkungan server lokal komputer Anda:

```bash
# 1. Masuk ke direktori utama proyek SiLADATA
cd /Users/rikorizky/proyek1

# 2. Pasang semua pustaka dependensi PHP yang dibutuhkan proyek melalui Composer
composer install

# 3. Salin berkas konfigurasi environment contoh ke berkas environment aktif
cp .env.example .env

# 4. Buat kunci enkripsi unik untuk keamanan aplikasi Laravel Anda
php artisan key:generate

# 5. Buat tautan simbolis folder penyimpanan berkas agar dapat diakses publik
php artisan storage:link

# 6. Jalankan migrasi tabel basis data beserta pengisian data awal sistem (users & modul default)
php artisan migrate --seed

# 7. Jalankan server lokal untuk mulai menjalankan aplikasi di browser
php artisan serve
```

Setelah server lokal berjalan, Anda dapat mengakses sistem melalui browser dengan membuka alamat **`http://127.0.0.1:8000`**.
