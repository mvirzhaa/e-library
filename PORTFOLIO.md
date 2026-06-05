# Laporan Portofolio Sertifikasi: E-Library Clean Architecture

Laporan portofolio ini disusun khusus untuk pengujian sertifikasi kompetensi. Laporan ini mendokumentasikan refaktorisasi arsitektur perangkat lunak dari platform perpustakaan digital **E-Library** menjadi arsitektur bersih (**Clean Architecture**), efisien, dan siap dikembangkan dalam skala besar (scalable).

---

## 💎 Ringkasan Sistem (System Overview)
E-Library adalah aplikasi berbasis web menggunakan framework **Laravel 12**, **Blade Templates**, dan **Tailwind CSS**. Sistem ini digunakan untuk mendistribusikan buku elektronik (ebook) dalam lingkungan institusi pendidikan (seperti kampus atau sekolah) dengan membatasi akses buku digital berdasarkan status keaktifan Kategori dan Mata Kuliah secara dinamis.

---

## 🏗️ Implementasi Clean Architecture
Untuk menghindari *spaghetti code* dan *fat controllers*, arsitektur sistem ini dibagi menjadi 4 layer utama yang mengikuti prinsip pemisahan tanggung jawab (*Separation of Concerns*):

### 1. Domain/Entities Layer (`app/Models`)
Layer terdalam yang mendefinisikan skema tabel data Eloquent sebagai representasi entitas bisnis inti tanpa mengetahui asal muasal data.
*   [User.php](file:///c:/laragon/www/e-library/app/Models/User.php): Mengatur hak akses role dan keaktifan akun.
*   [Ebook.php](file:///c:/laragon/www/e-library/app/Models/Ebook.php): Mengatur metadata berkas buku dan path file penyimpanan fisik.
*   [Category.php](file:///c:/laragon/www/e-library/app/Models/Category.php) & [Course.php](file:///c:/laragon/www/e-library/app/Models/Course.php): Mengatur data pengelompokan buku.

### 2. Infrastructure/Data Access Layer (`app/Repositories`)
Layer yang bertanggung jawab atas seluruh query database. Dibuat menggunakan pola **Repository Pattern** dengan kontrak interface agar logika aplikasi tidak terikat langsung pada framework database tertentu (misal: jika ingin beralih dari Eloquent ke Query Builder atau API eksternal).
*   `UserRepositoryInterface` & `UserRepository`
*   `EbookRepositoryInterface` & `EbookRepository`
*   `CategoryRepositoryInterface` & `CategoryRepository`
*   `CourseRepositoryInterface` & `CourseRepository`
*   [RepositoryServiceProvider.php](file:///c:/laragon/www/e-library/app/Providers/RepositoryServiceProvider.php): Menghubungkan abstraksi interface dengan implementasi konkritnya (IoC Container).

### 3. Application/Business Logic Layer (`app/Services`)
Layer tempat seluruh logika bisnis dan aturan aplikasi dijalankan. Layer ini independen dari request HTTP.
*   [EbookService.php](file:///c:/laragon/www/e-library/app/Services/EbookService.php): Mengatur upload file, slug SEO, penghapusan file lama di server saat update, dan download tracking.
*   [UserService.php](file:///c:/laragon/www/e-library/app/Services/UserService.php): Mengatur statistik dashboard serta aturan proteksi manipulasi role.
*   `CategoryService` & `CourseService`: Mengatur alur pembuatan dan pergantian status aktif-nonaktif.

### 4. Presentation & Validation Layer (`app/Http`)
Layer terluar yang menangani interaksi pengguna, memproses masukan HTTP, dan mengembalikan tampilan (View) atau berkas unduhan.
*   **Form Requests** (`app/Http/Requests`): Memisahkan aturan validasi masukan form dari controller.
    *   `StoreBookRequest`, `UpdateBookRequest`, `StoreCategoryRequest`, `StoreCourseRequest`, `UpdateUserRequest`.
*   **Controllers** (`app/Http/Controllers`): Menjadi sangat tipis (*thin controller*). Hanya menerima input HTTP, melemparkan ke Service Layer, lalu merender output ke template Blade.
    *   [EbookController.php](file:///c:/laragon/www/e-library/app/Http/Controllers/EbookController.php)
    *   [AdminController.php](file:///c:/laragon/www/e-library/app/Http/Controllers/AdminController.php)

---

## 🛡️ Matriks Otorisasi & Pertahanan Keamanan (Security Matrix)

### Matriks Role Pengguna
| Fitur / Aksi | Superadmin | Admin | Dosen | User (Mahasiswa/Umum) |
| :--- | :---: | :---: | :---: | :---: |
| Mengakses Dashboard Statistik | Ya | Ya | Tidak | Tidak |
| Melihat & Mengunduh Buku | Ya | Ya | Ya | Ya |
| Melakukan Preview Buku (PDF) | Ya | Ya | Ya | Ya |
| Mengupload & Mengedit Buku | Ya | Ya | Ya | Tidak |
| Mengelola Kategori & Matkul | Ya | Ya | Tidak | Tidak |
| Mengelola Akun / Ubah Role | Ya | Ya | Tidak | Tidak |
| Menghapus Akun Superadmin | Ya | Tidak | Tidak | Tidak |

### Benteng Keamanan Anti-Kudeta (Anti-Coup Rules)
Diproteksi di tingkat `UserService.php` untuk mencegah penyalahgunaan hak akses:
1.  **Cegah Kudeta Admin**: Akun ber-role `admin` dilarang mengubah status/role akun `superadmin`, serta dilarang menghapus akun `superadmin`.
2.  **Cegah Self-Promotion & Promosi Ilegal**: Akun `admin` dilarang menaikkan tingkat role pengguna lain menjadi `superadmin`.
3.  **Cegah Bunuh Diri Akun**: Pengguna (Admin maupun Superadmin) dilarang menghapus akun mereka sendiri atau menurunkan role mereka sendiri dari panel manajemen untuk mencegah hilangnya kendali sistem.

---

## 📝 Skenario Pengujian Sertifikasi (Test Scenarios)

Penguji sertifikasi dapat melakukan validasi fungsionalitas dan arsitektur menggunakan panduan skenario berikut:

### Skenario 1: Verifikasi Clean Architecture di Codebase
1.  Buka file [EbookController.php](file:///c:/laragon/www/e-library/app/Http/Controllers/EbookController.php). Perhatikan bahwa **tidak ada** query SQL, Eloquent, `Ebook::create()`, maupun manipulasi file `$request->file()->store()` di dalam controller. Semua didelegasikan ke `EbookService` melalui *Dependency Injection* pada constructor.
2.  Buka file [EbookService.php](file:///c:/laragon/www/e-library/app/Services/EbookService.php). Perhatikan pemisahan logika penyimpanan berkas PDF & Cover dari database, serta penanganan otomatis pembersihan storage (`Storage::disk()->delete()`) saat buku diperbarui atau dihapus.

### Skenario 2: Validasi Proteksi Keamanan (Anti-Kudeta)
1.  Login menggunakan akun ber-role **Admin**.
2.  Masuk ke menu Manajemen User (`/admin/users`).
3.  Coba klik tombol **Hapus** pada akun yang ber-role **Superadmin**.
4.  **Hasil yang diharapkan**: Sistem akan menggagalkannya dan menampilkan notifikasi kesalahan *"Pemberontakan! Admin tidak bisa menghapus Superadmin."*.
5.  Coba lakukan perubahan role pada akun lain dan pilih opsi role **Superadmin**.
6.  **Hasil yang diharapkan**: Sistem mendeteksi bypass request dan mengembalikan pesan *"Akses Ilegal: Hanya Superadmin yang bisa mengangkat Superadmin baru."*.

### Skenario 3: Filter & Klasifikasi Buku Dinamis
1.  Login sebagai **Admin**, lalu masuk ke menu **Kategori** atau **Mata Kuliah** di panel admin.
2.  Ubah salah satu kategori menjadi **Nonaktif** (misalnya kategori "Novel").
3.  Buka halaman Dashboard utama pengguna umum (`/dashboard`).
4.  **Hasil yang diharapkan**: Seluruh buku dengan kategori "Novel" akan otomatis disembunyikan dari halaman depan dan tidak dapat dicari, untuk memastikan efisiensi penayangan buku aktif secara dinamis.
