@extends('layouts.admin_layout')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
        <h2 class="text-2xl font-bold text-slate-800">Edit Buku</h2>
        <a href="{{ route('admin.books') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800">Batal & Kembali</a>
    </div>

    <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Judul Buku <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $book->title) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Penerbit</label>
                <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Tahun Terbit</label>
                <input type="number" name="publish_year" value="{{ old('publish_year', $book->publish_year) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="category" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}" {{ $book->category == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Mata Kuliah</label>
                <select name="related_course" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <option value="">-- Tidak ada --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->name }}" {{ $book->related_course == $course->name ? 'selected' : '' }}>{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl mt-4">
            <h3 class="text-sm font-bold text-slate-800 mb-3 border-b border-slate-200 pb-2">File Buku (Kosongkan jika tidak ingin diubah)</h3>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ganti Cover (JPG/PNG)</label>
                <div class="flex items-center gap-4">
                    @if($book->cover_path)
                        <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-12 h-16 object-cover rounded shadow-sm border border-slate-300">
                    @endif
                    <input type="file" name="cover" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ganti File PDF</label>
                <input type="file" name="file" accept=".pdf" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                <p class="text-[10px] text-slate-400 mt-1">*File PDF saat ini sudah tersimpan dan aman.</p>
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg shadow-blue-200">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
