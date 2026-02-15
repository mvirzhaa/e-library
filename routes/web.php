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
// 3. Grup Khusus ADMIN (Monitoring & Manage)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');

    // Manajemen User
    Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::patch('/users/{id}/role', [\App\Http\Controllers\AdminController::class, 'updateRole'])->name('users.update_role');
    Route::delete('/users/{id}', [\App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.delete');

    // Manajemen Buku (Perhatikan 2 baris edit & update ada di sini!)
    Route::get('/books', [\App\Http\Controllers\AdminController::class, 'books'])->name('books');
    Route::get('/books/create', [\App\Http\Controllers\AdminController::class, 'createBook'])->name('books.create');
    Route::post('/books', [\App\Http\Controllers\AdminController::class, 'storeBook'])->name('books.store');
    Route::get('/books/{id}/edit', [\App\Http\Controllers\AdminController::class, 'editBook'])->name('books.edit');
    Route::put('/books/{id}', [\App\Http\Controllers\AdminController::class, 'updateBook'])->name('books.update');
    Route::delete('/books/{id}', [\App\Http\Controllers\AdminController::class, 'destroyBook'])->name('books.delete');

    // Manajemen Kategori
    Route::get('/categories', [\App\Http\Controllers\AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [\App\Http\Controllers\AdminController::class, 'storeCategory'])->name('categories.store');
    Route::patch('/categories/{id}/toggle', [\App\Http\Controllers\AdminController::class, 'toggleCategory'])->name('categories.toggle');

    // Manajemen Mata Kuliah
    Route::get('/courses', [\App\Http\Controllers\AdminController::class, 'courses'])->name('courses');
    Route::post('/courses', [\App\Http\Controllers\AdminController::class, 'storeCourse'])->name('courses.store');
    Route::patch('/courses/{id}/toggle', [\App\Http\Controllers\AdminController::class, 'toggleCourse'])->name('courses.toggle');

});

// Route Profil Bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
