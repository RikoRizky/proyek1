# BAB 3 PERANCANGAN SISTEM

## 3.1 Arsitektur Sistem (High-Level Architecture)

Aplikasi SiLADATA dikembangkan menggunakan framework PHP **Laravel** yang menerapkan pola arsitektur **Model-View-Controller (MVC)**. Pola MVC memisahkan logika aplikasi menjadi tiga komponen utama guna mempermudah pemeliharaan, pengembangan, dan skalabilitas kode:

1. **Model:** Mewakili struktur data dan aturan bisnis. Di SiLADATA, Model seperti `User`, `Module`, `Requirement`, `Submission`, dan `Discussion` berinteraksi langsung dengan basis data menggunakan Eloquent ORM (Object-Relational Mapping).
2. **View:** Mengatur bagaimana informasi disajikan kepada pengguna. Antarmuka SiLADATA dikembangkan menggunakan *Blade Templating Engine* Laravel yang dinamis dan terstruktur.
3. **Controller:** Bertindak sebagai jembatan yang menerima HTTP Request dari pengguna, memproses data melalui Model, dan mengembalikan respon berupa View atau file unduhan.

Keamanan akses di dalam aplikasi dijamin melalui lapisan **Middleware**. Setiap request yang masuk ke server akan diperiksa terlebih dahulu oleh middleware autentikasi bawaan Laravel (`auth`) untuk memastikan pengguna sudah login, kemudian diteruskan ke middleware otorisasi berbasis peran (`role:admin`, `role:perti`, `role:unit_kerja`) untuk memastikan pengguna hanya dapat mengakses rute yang menjadi haknya.

Berikut adalah visualisasi diagram blok arsitektur sistem SiLADATA yang digambarkan dengan kode **Graphviz DOT**:

```dot
digraph G {
  fontname="Helvetica,Arial,sans-serif"
  node [fontname="Helvetica,Arial,sans-serif" shape=box style=filled fillcolor="#EBF4FF" color="#3182CE" penwidth=1.5]
  edge [fontname="Helvetica,Arial,sans-serif" color="#4A5568" penwidth=1.2]
  
  rankdir=TB;
  
  // Client/Browser
  Browser [label="Browser Client\n(Pengguna)" shape=ellipse fillcolor="#E2E8F0" color="#718096"]

  // Server Area
  subgraph cluster_server {
    label = "Server Aplikasi (Laravel MVC Framework)";
    style = dashed;
    color = "#E53E3E";
    
    Routes [label="Routes Web\n(routes/web.php)"]
    Middleware [label="Auth & Role Middleware\n(Session Guard)" fillcolor="#FEEBC8" color="#DD6B20"]
    
    subgraph cluster_controllers {
      label = "Controllers";
      color = "#319795";
      
      HomeController [label="HomeController\n(Guest Web)"]
      AdminController [label="Admin Controllers\n(User, Modul, Submissions)"]
      PertiController [label="Perti Controllers\n(Prodi, Monitoring)"]
      UnitController [label="Unit Kerja Controllers\n(Upload, Progress)"]
    }
    
    Models [label="Eloquent Models\n(User, Module, Requirement, Submission)" fillcolor="#E6FFFA" color="#319795"]
    PDFReport [label="Report PDF Engine\n(ReportController)" fillcolor="#FEE2E2" color="#EF4444"]
  }
  
  // Data Storage
  Database [label="Basis Data\n(MySQL / SQLite)" shape=cylinder fillcolor="#FEFCBF" color="#B7791F"]
  LocalStorage [label="Storage Lokal\n(public/uploads)" shape=folder fillcolor="#E9D8FD" color="#805AD5"]
  GoogleDrive [label="Google Drive\n(External Links)" shape=cloud fillcolor="#E2F0D9" color="#385723"]

  // Aliran Data
  Browser -> Routes [label="HTTP Request"]
  Routes -> Middleware [label="Filter Rute"]
  
  Middleware -> AdminController [label="Role: Admin"]
  Middleware -> PertiController [label="Role: Perti"]
  Middleware -> UnitController [label="Role: Unit Kerja"]
  Routes -> HomeController [label="Akses Publik"]
  
  AdminController -> Models
  PertiController -> Models
  UnitController -> Models
  
  Models -> Database [dir=both label="Eloquent ORM"]
  
  UnitController -> LocalStorage [label="Tulis File"]
  UnitController -> GoogleDrive [label="Simpan Link GD"]
  
  AdminController -> PDFReport
  PertiController -> PDFReport
  UnitController -> PDFReport
  PDFReport -> Browser [label="HTTP Response (PDF)"]
}
```

---

## 3.2 Workflow Sistem (Alur Proses Bisnis Pengumpulan Dokumen)

Proses pengumpulan dan versioning dokumen di dalam SiLADATA dirancang secara otomatis untuk meminimalisasi kesalahan koordinasi berkas. Alur proses bisnis pengumpulan dokumen dijabarkan langkah demi langkah sebagai berikut:

1. **Memilih Butir Persyaratan:** Pengguna Unit Kerja (Prodi) masuk ke menu pengumpulan berkas dan memilih butir kriteria/persyaratan akreditasi yang ingin dilengkapi.
2. **Pengisian Form & Unggah Berkas:** Pengguna memilih berkas dokumen lokal (misalnya format PDF) dari komputernya dan/atau memasukkan tautan Google Drive pada form unggahan. Setelah data lengkap, pengguna menekan tombol **Simpan**.
3. **Pengecekan Keberadaan Dokumen Sebelumnya:**
   - Sistem melakukan query ke tabel `submissions` untuk memeriksa apakah program studi tersebut sudah pernah mengunggah berkas untuk butir persyaratan yang sama sebelumnya.
   - **Jika Ya (Revisi Dokumen):** Sistem akan mengubah status dokumen lama dengan mengatur kolom status `is_latest` menjadi `false`. Hal ini mengarsipkan dokumen lama namun tidak menghapusnya dari riwayat basis data.
   - **Jika Tidak (Pengumpulan Baru):** Sistem melewati langkah pengarsipan versi lama.
4. **Penyimpanan Dokumen Baru:** Dokumen baru disimpan dengan kolom status `is_latest` bernilai `true`, status diset sebagai `Uploaded`, dan nomor kolom `version` otomatis dinaikkan satu angka dari versi sebelumnya (versi baru = versi lama + 1). File fisik disimpan dengan nama unik di storage lokal server.
5. **Pembaruan Dasbor & Rekapitulasi Progres:** Setelah berkas berhasil disimpan, query pada model `Submission` dengan relasi `latestForUnit` akan mendeteksi penambahan file. Persentase penyelesaian kriteria di halaman Dashboard Admin, Perti, dan Unit Kerja otomatis ter-update secara real-time.

Berikut adalah diagram alur proses pengunggahan dokumen hingga pembaruan dasbor yang digambarkan dengan kode **Graphviz DOT** (`rankdir=LR`):

```dot
digraph G {
  fontname="Helvetica,Arial,sans-serif"
  node [fontname="Helvetica,Arial,sans-serif" shape=box style=filled fillcolor="#F7FAFC" color="#CBD5E0" penwidth=1.5]
  edge [fontname="Helvetica,Arial,sans-serif" color="#4A5568" penwidth=1.2]
  
  rankdir=LR;
  
  // Nodes
  Start [label="Mulai" shape=ellipse fillcolor="#C6F6D5" color="#38A169"]
  SelectReq [label="Pilih Butir\nPersyaratan"]
  UploadForm [label="Unggah File /\nTautan Google Drive"]
  CheckDb [label="Pernah unggah\nsebelumnya?" shape=diamond fillcolor="#FEFCBF" color="#B7791F"]
  ArchiveOld [label="Set dokumen lama:\nis_latest = false"]
  SaveNew [label="Simpan dokumen baru:\nis_latest = true\nstatus = Uploaded\nversion = version + 1"]
  UpdateDashboard [label="Pembaruan Progres\nDasbor (Real-time)"]
  End [label="Selesai" shape=ellipse fillcolor="#FED7D7" color="#E53E3E"]
  
  // Connections
  Start -> SelectReq
  SelectReq -> UploadForm
  UploadForm -> CheckDb
  CheckDb -> ArchiveOld [label="Ya"]
  CheckDb -> SaveNew [label="Tidak"]
  ArchiveOld -> SaveNew
  SaveNew -> UpdateDashboard
  UpdateDashboard -> End
}
```
