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
        // Ambil kategori & matkul yang aktif saja
        $categories = \App\Models\Category::where('is_active', true)->get();
        $courses = \App\Models\Course::where('is_active', true)->get();

        return view('admin.books_create', compact('categories', 'courses'));
    }

    // Memproses data simpan buku
    public function storeBook(\Illuminate\Http\Request $request)
    {
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
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak bisa menghapus sesama Admin!');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // --- MANAJEMEN KATEGORI ---
    public function categories()
    {
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
        // 1. Keamanan Lapis Baja: HANYA SUPERADMIN yang boleh mengubah role!
        if (auth()->user()->role !== 'superadmin') {
            return back()->with('error', 'Akses Ditolak! Hanya Superadmin yang bisa mengubah jabatan.');
        }

        // 2. Validasi input role (Hanya boleh 4 ini)
        $request->validate([
            'role' => 'required|in:superadmin,admin,dosen,user'
        ]);

        $user = \App\Models\User::findOrFail($id);

        // 3. Keamanan: Cegah superadmin mengubah rolenya sendiri jadi user biasa (agar tidak bunuh diri/terkunci)
        if ($user->id === auth()->id() && $request->role !== 'superadmin') {
            return back()->with('error', 'Anda tidak bisa menurunkan jabatan Anda sendiri!');
        }

        // 4. Update rolenya
        $user->update(['role' => $request->role]);

        return back()->with('success', "Role {$user->name} berhasil diubah menjadi " . strtoupper($request->role));
    }

}
