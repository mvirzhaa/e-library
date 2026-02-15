<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ebook;
use App\Models\User;

class AdminController extends Controller
{
    // 1. Dashboard Statistik
    public function index()
    {
        $stats = [
            'buku' => Ebook::count(),
            'user' => User::where('role', 'user')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'download' => Ebook::sum('download_count'),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // 2. Manajemen Buku (List)
    public function books()
    {
        $books = Ebook::latest()->paginate(10);
        return view('admin.books', compact('books'));
    }

    // Menampilkan halaman form tambah buku
    public function createBook()
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin', 'dosen'])) {
            abort(403, 'Maaf, hanya Dosen dan Admin yang boleh mengupload buku.');
        }
        // Ambil kategori & matkul yang aktif saja
        $categories = \App\Models\Category::where('is_active', true)->get();
        $courses = \App\Models\Course::where('is_active', true)->get();


        return view('admin.books_create', compact('categories', 'courses'));
    }


    // Memproses data simpan buku
    public function storeBook(\Illuminate\Http\Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin', 'dosen'])) {
            abort(403, 'Maaf, hanya Dosen dan Admin yang boleh mengupload buku.');
        }
        $request->validate([
            'judul_buku' => 'required|max:255',
            'penerbit' => 'required|max:255',
            'tahun_terbit' => 'required|numeric',
            'kategori' => 'required',
            'file_pdf' => 'required|mimes:pdf|max:30720', // Max 30MB
            'cover_image' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Simpan File PDF
        $pdfPath = $request->file('file_pdf')->store('ebooks', 'public');

        // Simpan Gambar Cover (Jika ada)
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        // Masukkan ke Database
        \App\Models\Ebook::create([
            'title'          => $request->judul_buku,
            'slug'           => \Illuminate\Support\Str::slug($request->judul_buku . '-' . time()),
            'publisher'      => $request->penerbit,
            'publish_year'   => $request->tahun_terbit,
            'category'       => $request->kategori,
            'is_textbook'    => $request->has('is_buku_kuliah'),
            'related_course' => $request->mata_kuliah_terkait,
            'file_path'      => $pdfPath,
            'cover_path'     => $coverPath,
            'download_count' => 0
        ]);

        return redirect()->route('admin.books')->with('success', 'Buku baru berhasil ditambahkan!');
    }

    // 3. Manajemen User (List)
    public function users()
    {
        if (auth()->user()->role === 'dosen') {
            abort(403, 'Akses Ditolak: Dosen hanya diizinkan mengelola buku.');
        }
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    // 4. Hapus Buku
    public function destroyBook($id)
    {
        Ebook::findOrFail($id)->delete();
        return back()->with('success', 'Buku berhasil dihapus.');
    }

    // 5. Hapus User
   public function destroyUser($id)
    {
        $user = \App\Models\User::findOrFail($id);

        // --- BENTENG KEAMANAN ANTI KUDETA ---
        if (auth()->user()->role === 'admin' && $user->role === 'superadmin') {
            return back()->with('error', 'Pemberontakan! Admin tidak bisa menghapus Superadmin.');
        }

        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }
        // ------------------------------------

        $user->delete();

        return back()->with('success', 'Akun berhasil dihapus selamanya.');
    }

    // --- MANAJEMEN KATEGORI ---
    public function categories()
    {
        if (auth()->user()->role === 'dosen') {
            abort(403, 'Akses Ditolak: Dosen hanya diizinkan mengelola buku.');
        }
        $categories = \App\Models\Category::latest()->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name']);
        \App\Models\Category::create(['name' => $request->name]);
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

   public function toggleCategory($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]); // Membalikkan status (true jadi false, dst)

        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Kategori berhasil {$status}.");
    }

    // --- MANAJEMEN MATA KULIAH ---
    public function courses()
    {
        if (auth()->user()->role === 'dosen') {
            abort(403, 'Akses Ditolak: Dosen hanya diizinkan mengelola buku.');
        }
        $courses = \App\Models\Course::latest()->get();
        return view('admin.courses', compact('courses'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate(['name' => 'required|unique:courses,name']);
        \App\Models\Course::create(['name' => $request->name]);
        return back()->with('success', 'Mata Kuliah berhasil ditambahkan.');
    }

    public function toggleCourse($id)
    {
        $course = \App\Models\Course::findOrFail($id);
        $course->update(['is_active' => !$course->is_active]);

        $status = $course->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Mata Kuliah berhasil {$status}.");
    }

    public function updateRole(\Illuminate\Http\Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        // --- BENTENG KEAMANAN ANTI KUDETA ---
        // 1. Admin dilarang mengubah data Superadmin
        if (auth()->user()->role === 'admin' && $user->role === 'superadmin') {
            return back()->with('error', 'Lancang! Admin tidak diizinkan mengubah data Superadmin.');
        }

        // 2. Admin dilarang mengangkat Superadmin baru
        if (auth()->user()->role === 'admin' && $request->role === 'superadmin') {
            return back()->with('error', 'Akses Ilegal: Hanya Superadmin yang bisa mengangkat Superadmin baru.');
        }

        // 3. Cegah ganti role sendiri dari sini
        if (auth()->id() === $user->id && $request->role !== auth()->user()->role) {
            return back()->with('error', 'Gunakan menu profil untuk mengubah data Anda sendiri.');
        }
        // ------------------------------------

        $user->update([
            'role' => $request->role,
            'is_active' => $request->is_active
        ]);

        return back()->with('success', "Akun {$user->name} berhasil diperbarui.");
    }

    // 1. Fungsi Menampilkan Form Edit
    public function editBook($id)
    {
        // Gembok Dosen, Admin, Superadmin
        if (!in_array(auth()->user()->role, ['admin', 'superadmin', 'dosen'])) {
            abort(403, 'Akses Ditolak');
        }

        $book = \App\Models\Ebook::findOrFail($id);
        $categories = \App\Models\Category::all(); // Ambil data kategori
        $courses = \App\Models\Course::all();     // Ambil data matkul

        return view('admin.books_edit', compact('book', 'categories', 'courses'));
    }

    // 2. Fungsi Memproses Perubahan Data
    public function updateBook(\Illuminate\Http\Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin', 'dosen'])) {
            abort(403, 'Akses Ditolak');
        }

        $book = \App\Models\Ebook::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publish_year' => 'nullable|integer',
            'category' => 'required|string',
            'related_course' => 'nullable|string',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Cover baru opsional
            'file' => 'nullable|mimes:pdf|max:20000',               // PDF baru opsional
        ]);

        // JIKA ADA UPLOAD COVER BARU
        if ($request->hasFile('cover')) {
            // Hapus cover lama dari server
            if ($book->cover_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->cover_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($book->cover_path);
            }
            // Simpan cover baru
            $book->cover_path = $request->file('cover')->store('covers', 'public');
        }

        // JIKA ADA UPLOAD PDF BARU
        if ($request->hasFile('file')) {
            // Hapus PDF lama dari server
            if ($book->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($book->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($book->file_path);
            }
            // Simpan PDF baru
            $book->file_path = $request->file('file')->store('ebooks', 'public');
        }

        // Update data teks
        $book->title = $request->title;
        $book->publisher = $request->publisher;
        $book->publish_year = $request->publish_year;
        $book->category = $request->category;
        $book->related_course = $request->related_course;

        $book->save();

        return redirect()->route('admin.books')->with('success', 'Buku berhasil diperbarui!');
    }

}
