# TODO - hapus penilaian & bobot (upload only)

## Step 1
- [ ] Buat migration baru untuk menghapus tabel `assessments` (hapus penilaian total).


## Step 2
- [ ] Hapus relasi dan dependency `Assessment` dari model `Submission` dan `Assessment` itu sendiri (atau pertahankan model tapi tidak dipakai).

## Step 3
- [ ] Update controller asesor: hapus `AssessmentController` (viewer/store routes) dan sesuaikan queue/completed agar tidak query `assessment`.

## Step 4
- [ ] Update controller admin: hapus `AssessmentOverviewController` logic yang menampilkan skor/penilaian.

## Step 5
- [ ] Update report: hapus perhitungan weightedTotal/detailedRows berbasis `assessment.score` dan ganti menjadi ringkasan upload status.

## Step 6
- [ ] Hapus UI penilaian: konten score/comments di `resources/views/asesor/assessments/show.blade.php` dan tabel skor di `resources/views/asesor/completed.blade.php`.

## Step 7
- [ ] Hapus routes terkait penilaian di `routes/web.php`.

## Step 8
- [ ] Update seeder: `AccreditationDemoSeeder` supaya tidak mengisi `weight` (jika tidak dipakai) dan tidak membuat data assessments.

## Step 9
- [ ] Jalankan `php artisan migrate` dan `php artisan test` (kalau ada).

