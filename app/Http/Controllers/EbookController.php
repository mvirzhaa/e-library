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
        // 1. Ambil Data untuk Dropdown Filter (Agar user tidak ngetik manual)
        $categories = Ebook::select('category')->distinct()->pluck('category');
        $years = Ebook::select('publish_year')->distinct()->orderBy('publish_year', 'desc')->pluck('publish_year');
        $courses = Ebook::whereNotNull('related_course')->select('related_course')->distinct()->pluck('related_course');

        // 2. Logic Filter (Pencarian)
        $query = Ebook::query();

        // Filter berdasarkan Pencarian Judul
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan Kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter berdasarkan Mata Kuliah
        if ($request->filled('course')) {
            $query->where('related_course', $request->course);
        }

        // Filter berdasarkan Tahun
        if ($request->filled('year')) {
            $query->where('publish_year', $request->year);
        }

        // Ambil data hasil filter (Paginate agar rapi)
        $ebooks = $query->latest()->paginate(12)->withQueryString();

        // 3. Ambil Buku Terpopuler (Untuk Section Atas)
        // Diurutkan berdasarkan download_count tertinggi, ambil 4 saja
        $popularBooks = Ebook::orderByDesc('download_count')->take(4)->get();

        return view('dashboard', compact('ebooks', 'popularBooks', 'categories', 'years', 'courses'));
    }

    public function create()
{
    $categories = \App\Models\Category::all();
    $courses = \App\Models\Course::all();
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
}
