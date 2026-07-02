# BAB 1 PENDAHULUAN

## 1.1 Latar Belakang

Akreditasi merupakan salah satu instrumen penjaminan mutu eksternal yang bersifat krusial bagi keberlangsungan dan kredibilitas program studi maupun perguruan tinggi. Melalui proses akreditasi, institusi diukur kepatuhan dan pencapaiannya terhadap standar nasional pendidikan tinggi yang telah ditetapkan oleh pemerintah. Evaluasi mutu ini berdampak langsung terhadap reputasi institusi, nilai kelulusan mahasiswa, daya saing alumni di dunia kerja, serta pemenuhan regulasi operasional pendidikan tinggi.

Namun, dalam pelaksanaannya, mempersiapkan dokumen borang akreditasi sering kali menjadi tantangan yang sangat kompleks dan melelahkan bagi para pengelola program studi (Unit Kerja) dan pimpinan perguruan tinggi (Perti). Beberapa permasalahan utama yang sering terjadi dalam manajemen dokumen akreditasi konvensional meliputi:

1. **Risiko Kehilangan dan Kerusakan Berkas:** Dokumen fisik atau penyimpanan yang tersebar di komputer pribadi staf administrasi rentan hilang, rusak, atau sulit ditemukan kembali saat dibutuhkan untuk visitasi asesor.
2. **Masalah Sinkronisasi dan Versi Dokumen (*Versioning*):** Dokumen akreditasi sering kali mengalami revisi berulang kali oleh tim penyusun. Tanpa adanya sistem versi yang baik, dokumen versi lama sering kali tertukar dengan versi terbaru, yang berakibat pada ketidakakuratan data kinerja yang dilaporkan.
3. **Sulitnya Pemantauan Progres secara Waktu Nyata (*Real-Time Monitoring*):** Pihak rektorat atau pimpinan perguruan tinggi kesulitan untuk memantau sejauh mana kesiapan dokumen dari masing-masing program studi di bawah naungan mereka. Akibatnya, keterlambatan pengumpulan dokumen sering kali baru diketahui menjelang batas waktu berakhir (*deadline*), sehingga proses penjaminan mutu menjadi kurang optimal dan terburu-buru.
4. **Penyimpanan Berkas Ganda yang Tidak Terstruktur:** Kebutuhan unggah berkas yang mencakup file fisik maupun tautan eksternal (seperti Google Drive) sering kali tidak terkelola dengan rapi di satu wadah.

Untuk mengatasi permasalahan tersebut, dikembangkan sebuah solusi digital berupa platform manajemen dokumen berbasis web yang tersentralisasi. Framework **Laravel** dengan arsitektur **Model-View-Controller (MVC)** dipilih sebagai fondasi pengembangan aplikasi ini. Laravel menyediakan kerangka kerja yang tangguh, aman, dan terstruktur, yang mempermudah integrasi logika bisnis (*Controller*), pengelolaan data relasional (*Model*), serta penyajian antarmuka pengguna yang dinamis (*View*). Sistem ini dirancang untuk menjembatani komunikasi data dan pemantauan dokumen dari tingkat Program Studi (Unit Kerja) langsung menuju manajemen Perguruan Tinggi (Perti) secara terstruktur, aman, dan efisien.

---

## 1.2 Nama Aplikasi dan Dasar Ide

Aplikasi ini diberi nama **SiLADATA (Sistem Layanan Dokumen Akreditasi)**. 

Dasar ide dari pengembangan SiLADATA lahir dari kebutuhan nyata akan transparansi, kolaborasi, dan kemudahan koordinasi antarmuka kerja dalam penyusunan instrumen akreditasi. Nama **SiLADATA** mencerminkan sistem layanan yang tangkas, sistematis, dan aman dalam mengelola data serta dokumen penting yang menjadi bukti kinerja sahih institusi. 

Dengan SiLADATA, proses koordinasi yang dulunya memakan waktu lama melalui pesan instan atau email kini tersentralisasi di dalam satu sistem. Setiap perubahan dokumen terdokumentasi dengan baik, dan pimpinan institusi dapat secara transparan melihat kriteria mana saja yang belum dilengkapi oleh program studi tanpa perlu melakukan penagihan manual yang berulang-ulang.

---

## 1.3 Tujuan Pengembangan

### 1.3.1 Tujuan Umum
Menyediakan sebuah sistem manajemen dan pemantauan dokumen akreditasi perguruan tinggi yang transparan, aman, akurat, dan efisien guna mendukung kelancaran proses penjaminan mutu eksternal institusi.

### 1.3.2 Tujuan Khusus
1. **Menerapkan Manajemen Hak Akses Berbasis Peran (*Role-Based Access Control*):** Membagi sistem ke dalam tiga hak akses pengguna yang terautentikasi (Administrator, Perguruan Tinggi, dan Program Studi/Unit Kerja) guna membatasi dan mengamankan alur kerja masing-masing aktor.
2. **Menyediakan Pelacakan Progres Waktu Nyata (*Real-Time Progress Tracking*):** Memfasilitasi pimpinan Perguruan Tinggi (Perti) untuk memantau persentase kelengkapan pengunggahan berkas akreditasi dari masing-masing program studi secara langsung melalui dasbor interaktif.
3. **Mengimplementasikan Mekanisme Riwayat Dokumen (*Document Versioning*):** Menjamin keandalan berkas melalui penandaan berkas aktif terbaru (`is_latest = true`) dan pengarsipan berkas versi lama ketika program studi mengunggah perbaikan dokumen.
4. **Menyediakan Fitur Ekspor PDF Rekapitulasi Progres:** Memungkinkan seluruh pengguna mengunduh rekapitulasi progres kelengkapan pengumpulan dokumen ke dalam format dokumen PDF formal yang dapat dijadikan bahan evaluasi rapat koordinasi.

---

## 1.4 Ruang Lingkup

Pengembangan aplikasi SiLADATA dibatasi pada ruang lingkup sebagai berikut:
1. **Teknologi Utama:** Aplikasi dikembangkan sebagai aplikasi berbasis web menggunakan bahasa pemrograman PHP dengan framework Laravel (pola arsitektur MVC) dan basis data relasional.
2. **Manajemen Konten:** Struktur pengumpulan dokumen diorganisasi berdasarkan Kriteria/Modul (Module) dan Butir Persyaratan (Requirement) yang dinamis dan dapat dikonfigurasi oleh Administrator.
3. **Penyimpanan Berkas:** Sistem mendukung pengunggahan berkas secara fisik ke dalam penyimpanan lokal server terproteksi (*storage link* Laravel) serta pengisian tautan eksternal dokumen (Google Drive).
4. **Fitur Pengunjung (Tamu/Guest):** Menyediakan landing page promosi produk yang berisi deskripsi aplikasi, paket harga, serta formulir interaktif pengajuan diskusi/konsultasi kerja sama yang terhubung langsung ke panel Administrator.
5. **Autentikasi:** Keamanan akses dikendalikan penuh oleh middleware bawaan Laravel yang dimodifikasi untuk membatasi akses berdasarkan peran masing-masing pengguna.
