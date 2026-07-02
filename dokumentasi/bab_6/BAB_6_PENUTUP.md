# BAB 6 PENUTUP

## 6.1 Kesimpulan

Berdasarkan hasil analisis kebutuhan, perancangan, dan implementasi yang telah dilakukan, dapat ditarik beberapa kesimpulan mengenai pengembangan aplikasi **SiLADATA (Sistem Layanan Dokumen Akreditasi)**:

1. **Efisiensi Kolaborasi Multi-Pengguna (*Multi-User Collaboration*):** SiLADATA berhasil membagi sistem ke dalam peran khusus terautentikasi (Administrator, Perguruan Tinggi, dan Program Studi) dengan hak akses yang terisolasi secara aman. Pembagian ini mempermudah alur kerja pengumpulan dokumen dari bawah ke atas secara terstruktur.
2. **Transparansi Pemantauan Real-Time:** Penerapan dasbor interaktif dengan kalkulator progres otomatis terbukti memudahkan pimpinan Perguruan Tinggi (Perti) memantau tingkat kesiapan dokumen dari masing-masing program studi secara langsung (*real-time*). Hal ini meminimalkan keterlambatan deteksi kendala pengumpulan berkas borang akreditasi.
3. **Keandalan Data Berkat *Versioning System*:** Fitur pengelolaan versi dokumen (`is_latest`) berhasil menjamin keandalan dan keakuratan data. Pengguna tidak perlu khawatir kehilangan draf lama saat melakukan revisi dokumen, sementara penilai/pimpinan dijamin selalu mendapatkan berkas versi paling mutakhir.
4. **Kemudahan Evaluasi Offline:** Integrasi dengan *Report PDF Engine* mempermudah tim penjaminan mutu mencetak status rekapitulasi progres pengerjaan borang untuk kebutuhan rapat koordinasi fisik maupun lampiran administratif formal.

---

## 6.2 Saran Pengembangan

Untuk meningkatkan fungsionalitas dan kegunaan aplikasi SiLADATA di masa mendatang, disarankan beberapa poin pengembangan sebagai berikut:

1. **Integrasi API Notifikasi WhatsApp Otomatis:**
   - Menambahkan modul *gateway* WhatsApp untuk mengirimkan pesan pengingat (*reminder*) otomatis kepada perwakilan Unit Kerja ketika mendekati batas akhir (*deadline*) tanggal pengumpulan dokumen kriteria tertentu.
   - Mengirim notifikasi instan kepada pimpinan Perti ketika sebuah program studi telah berhasil melengkapi 100% dari seluruh kriteria dokumen yang disyaratkan.
2. **Implementasi Tanda Tangan Elektronik (*E-Signature*):**
   - Mengintegrasikan sertifikat tanda tangan digital tersertifikasi (misalnya BSrE atau penyedia jasa tanda tangan digital lainnya) untuk memvalidasi keabsahan dokumen bukti kinerja yang diunggah ke sistem.
3. **Fitur Audit Trail yang Lebih Mendalam:**
   - Menyediakan pencatatan log riwayat aktivitas pengguna secara rinci (siapa, kapan, dan apa yang diubah/diunduh) untuk meningkatkan aspek keamanan sistem dan kepatuhan audit sistem informasi.
4. **Analitik Prediktif Kelulusan Akreditasi:**
   - Menambahkan modul penilaian mandiri (*self-assessment score*) berdasarkan kelengkapan berkas untuk memprediksi potensi perolehan nilai akreditasi prodi (misal: Unggul, Sangat Baik, Baik).
