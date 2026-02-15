<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\AdminController; // Nanti kita buat ini

// 1. Landing Page (Public) - Tidak perlu login
Route::get('/', function () {
    return view('landing');
});

// 2. Grup User & Admin (Harus Login)
Route::middleware('auth')->group(function () {

    // Dashboard User (Tempat list buku)
    // Route Dashboard (Pengecekan Role)
Route::get('/dashboard', function (Request $request) { // 1. Tambahkan Request $request disini
    if (in_array(auth()->user()->role, ['admin', 'superadmin']))   {
        return redirect()->route('admin.dashboard');
    }

    // 2. Kirim $request ke dalam fungsi index
    return app(App\Http\Controllers\EbookController::class)->index($request);
})->name('dashboard');

    // Fitur Download
    Route::get('/download/{id}', [EbookController::class, 'download'])->name('ebooks.download');
    Route::get('/ebooks/{id}/preview', [\App\Http\Controllers\EbookController::class, 'preview'])->name('ebooks.preview');

    // Fitur Upload (SEKARANG BISA DIAKSES SEMUA USER LOGIN)
    Route::get('/upload', [EbookController::class, 'create'])->name('ebooks.create');
    Route::post('/upload', [EbookController::class, 'store'])->name('ebooks.store');
});

// 3. Grup Khusus ADMIN (Monitoring & Manage)
// Grup Khusus ADMIN
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::patch('/users/{id}/role', [AdminController::class, 'updateRole'])->name('users.update_role');
    Route::get('/books', [AdminController::class, 'books'])->name('books');
    // --- 2 BARIS BARU INI UNTUK FITUR TAMBAH BUKU ---
    Route::get('/books/create', [AdminController::class, 'createBook'])->name('books.create');
    Route::post('/books', [AdminController::class, 'storeBook'])->name('books.store');
    // ------------------------------------------------
    Route::delete('/books/{id}', [AdminController::class, 'destroyBook'])->name('books.delete');

    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.delete');
   // Manajemen Kategori
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');

    // INI YANG BERUBAH (Dari delete menjadi patch dan memanggil fungsi toggle)
    Route::patch('/categories/{id}/toggle', [AdminController::class, 'toggleCategory'])->name('categories.toggle');

    // Manajemen Mata Kuliah
    Route::get('/courses', [AdminController::class, 'courses'])->name('courses');
    Route::post('/courses', [AdminController::class, 'storeCourse'])->name('courses.store');

    // INI YANG BERUBAH (Dari delete menjadi patch dan memanggil fungsi toggle)
    Route::patch('/courses/{id}/toggle', [AdminController::class, 'toggleCourse'])->name('courses.toggle');
});

// Route Profil Bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
