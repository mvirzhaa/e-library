<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EbookController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil HANYA nama kategori & matkul yang statusnya AKTIF
        $activeCategories = \App\Models\Category::where('is_active', true)->pluck('name');
        $activeCourses = \App\Models\Course::where('is_active', true)->pluck('name');

        // 2. Buat Query Dasar: Filter buku berdasarkan status aktif tersebut
        $query = \App\Models\Ebook::whereIn('category', $activeCategories)
            ->where(function($q) use ($activeCourses) {
                $q->whereIn('related_course', $activeCourses)
                  ->orWhereNull('related_course')
                  ->orWhere('related_course', ''); // Tetap tampilkan buku umum (tanpa matkul)
            });

        // 3. Fitur Pencarian & Filter (Bawaan Anda sebelumnya)
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('category', $request->kategori);
        }

        if ($request->has('mata_kuliah') && $request->mata_kuliah != '') {
            $query->where('related_course', $request->mata_kuliah);
        }

        // 4. Ambil datanya (Paginasi)
        $ebooks = $query->latest()->paginate(10);

        // Kirim juga data kategori & matkul aktif ke View untuk dropdown filter di Dashboard
        $categories = \App\Models\Category::where('is_active', true)->get();
        $courses = \App\Models\Course::where('is_active', true)->get();

        $years = \App\Models\Ebook::select('publish_year')
                    ->distinct()
                    ->orderBy('publish_year', 'desc')
                    ->pluck('publish_year');

        // --- TAMBAHAN BARU: Mengambil data buku terpopuler ---
        $popularBooks = \App\Models\Ebook::whereIn('category', $activeCategories)
            ->where(function($q) use ($activeCourses) {
                $q->whereIn('related_course', $activeCourses)
                  ->orWhereNull('related_course')
                  ->orWhere('related_course', '');
            })
            ->orderBy('download_count', 'desc')
            ->take(5) // Ambil 5 buku terpopuler
            ->get();

        // Jangan lupa $popularBooks dimasukkan ke dalam compact()
        return view('dashboard', compact('ebooks', 'categories', 'courses', 'years', 'popularBooks'));
    }

    public function create()
    {
        $categories = \App\Models\Category::where('is_active', true)->get(); // Hanya ambil yang aktif
        $courses = \App\Models\Course::where('is_active', true)->get();    // Hanya ambil yang aktif
        return view('ebooks.create', compact('categories', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_buku' => 'required|max:255',
            'penerbit' => 'required|max:255',
            'tahun_terbit' => 'required|numeric',
            'file_pdf' => 'required|mimes:pdf|max:30720', // 30MB
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $pdfPath = $request->file('file_pdf')->store('ebooks', 'public');
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        Ebook::create([
            'title'          => $request->judul_buku,
            'slug'           => Str::slug($request->judul_buku . '-' . time()),
            'publisher'      => $request->penerbit,
            'publish_year'   => $request->tahun_terbit,
            'category'       => $request->kategori,
            'is_textbook'    => $request->has('is_buku_kuliah'),
            'related_course' => $request->mata_kuliah_terkait,
            'file_path'      => $pdfPath,
            'cover_path'     => $coverPath,
            'download_count' => 0
        ]);

        return redirect()->route('dashboard')->with('success', 'Buku berhasil diupload!');
    }

    public function download($id)
    {
        $ebook = Ebook::findOrFail($id);
        $ebook->increment('download_count');
        return Storage::disk('public')->download($ebook->file_path, $ebook->slug . '.pdf');
    }

    // Fungsi untuk menampilkan halaman Preview PDF
    public function preview($id)
    {
        $ebook = \App\Models\Ebook::findOrFail($id);

        // Kita kirim data buku ke halaman tampilan preview
        return view('ebooks.preview', compact('ebook'));
    }
}
