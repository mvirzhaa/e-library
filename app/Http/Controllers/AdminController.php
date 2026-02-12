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

    public function destroyCategory($id)
    {
        \App\Models\Category::findOrFail($id)->delete();
        return back()->with('success', 'Kategori dihapus.');
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

    public function destroyCourse($id)
    {
        \App\Models\Course::findOrFail($id)->delete();
        return back()->with('success', 'Mata Kuliah dihapus.');
    }
}
