<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

# E-Library - Platform Perpustakaan Digital

E-Library adalah platform perpustakaan digital berbasis web yang dibangun dengan Laravel 12. Aplikasi ini menyediakan manajemen buku digital (Ebook) dengan klasifikasi kategori, relasi mata kuliah, pencarian cepat, serta sistem pertahanan keamanan multi-role (User, Dosen, Admin, Superadmin).

Aplikasi ini telah direfaktor menggunakan **Clean Architecture** (pola berlapis) untuk memastikan skalabilitas, efisiensi tinggi, kemudahan pengujian unit (testability), dan kode bersih bebas spaghetti.

---

## 🚀 Fitur Utama

1. **Autentikasi & Otorisasi Multi-Role**:
   - **Superadmin**: Akses penuh ke seluruh fitur dan pengaturan role pengguna.
   - **Admin**: Mengelola buku, kategori, mata kuliah, dan status pengguna biasa (tidak bisa memodifikasi Superadmin).
   - **Dosen**: Mengupload dan mengedit buku digital.
   - **User**: Mencari, mengunduh, dan melakukan preview PDF buku.
2. **Manajemen Ebook**: Upload file PDF (hingga 30MB), cover gambar, download count tracking, dan download PDF aman.
3. **Kategori & Mata Kuliah**: Pengelompokan buku berdasarkan kategori dinamis dan mata kuliah tertentu yang dapat diaktifkan/dinonaktifkan secara instan.
4. **Pencarian & Penyaringan**: Cari buku berdasarkan judul, kategori, dan mata kuliah secara real-time.
5. **Benteng Keamanan Anti-Kudeta**: Proteksi berlapis pada tingkat controller dan service untuk menghalangi Admin mengambil alih/menghapus akun Superadmin atau menghapus akun mereka sendiri.

---

## 🏗️ Clean Architecture

Kode program dipisahkan berdasarkan tanggung jawabnya ke dalam beberapa layer terisolasi:

```text
app/
├── Http/
│   ├── Controllers/             <-- Presentation Layer (Hanya menangani request, response & views)
│   │   ├── AdminController.php
│   │   └── EbookController.php
│   ├── Middleware/
│   │   └── IsAdmin.php
│   └── Requests/                <-- Validation Layer (Validasi input request yang didelegasikan)
│       ├── StoreBookRequest.php
│       ├── UpdateBookRequest.php
│       ├── StoreCategoryRequest.php
│       ├── StoreCourseRequest.php
│       └── UpdateUserRequest.php
├── Models/                      <-- Domain/Entity Layer (Struktur data Eloquent)
│   ├── Category.php
│   ├── Course.php
│   ├── Ebook.php
│   └── User.php
├── Repositories/                <-- Infrastructure Layer (Query database terisolasi)
│   ├── Contracts/               <-- Interface/Abstraksi Repository
│   │   ├── CategoryRepositoryInterface.php
│   │   ├── CourseRepositoryInterface.php
│   │   ├── EbookRepositoryInterface.php
│   │   └── UserRepositoryInterface.php
│   └── Eloquent/                <-- Implementasi Query dengan Eloquent ORM
│       ├── CategoryRepository.php
│       ├── CourseRepository.php
│       ├── EbookRepository.php
│       └── UserRepository.php
├── Services/                    <-- Application/Business Logic Layer (Logika bisnis utama)
│   ├── CategoryService.php
│   ├── CourseService.php
│   ├── EbookService.php
│   └── UserService.php
└── Providers/
    └── RepositoryServiceProvider.php <-- Dependency Injection binder untuk IOC Container
```

### Penjelasan Flow Layer
1. **Request** masuk ke **Controller** (Presentation Layer).
2. Request divalidasi secara otomatis menggunakan **Form Request** (Validation Layer).
3. Controller memanggil **Service** (Business Logic Layer) untuk memproses logika bisnis (seperti pengecekan hak akses internal, pemrosesan file, dsb).
4. Service berinteraksi dengan database melalui abstraksi **Repository** (Infrastructure Layer) sehingga database atau metode query bisa diganti di kemudian hari tanpa merusak logika bisnis.

---

## 🛠️ Langkah Instalasi

Ikuti langkah di bawah ini untuk menjalankan project di lokal Anda:

1. **Clone Repository**:
   ```bash
   git clone <url-repository>
   cd e-library
   ```

2. **Instalasi Dependensi PHP**:
   ```bash
   composer install
   ```

3. **Instalasi Dependensi Frontend**:
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**:
   Salin file `.env.example` menjadi `.env` lalu sesuaikan kredensial database Anda (default menggunakan SQLite atau MySQL).
   ```bash
   copy .env.example .env
   ```

5. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database & Seeding**:
   ```bash
   php artisan migrate --seed
   ```

7. **Buat Symlink Storage**:
   Untuk menampilkan file PDF dan Cover gambar di browser:
   ```bash
   php artisan storage:link
   ```

8. **Jalankan Development Server**:
   ```bash
   # Terminal 1: Menjalankan Laravel server
   php artisan serve

   # Terminal 2: Menjalankan Vite bundler
   npm run dev
   ```

---

## 📝 Lisensi
Platform E-Library ini dilisensikan di bawah lisensi [MIT](https://opensource.org/licenses/MIT).
