@extends('layouts.admin_layout') @section('content')
<div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold text-slate-900 mb-6 border-b pb-4">Tambah Buku Baru</h2>

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Buku <span class="text-red-500">*</span></label>
                <input type="text" name="judul_buku" required placeholder="Contoh: Pemrograman Web Lanjut" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Penerbit <span class="text-red-500">*</span></label>
                <input type="text" name="penerbit" required placeholder="Contoh: Erlangga" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Terbit <span class="text-red-500">*</span></label>
                <input type="number" name="tahun_terbit" required placeholder="Contoh: 2024" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <option value="">Pilih Kategori...</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Mata Kuliah Terkait (Opsional)</label>
                <select name="mata_kuliah_terkait" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <option value="">-- Tidak Terkait Matkul --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->name }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center md:mt-8">
                <input type="checkbox" name="is_buku_kuliah" id="is_buku_kuliah" value="1" class="w-5 h-5 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                <label for="is_buku_kuliah" class="ml-3 text-sm font-bold text-slate-700 cursor-pointer">Tandai sebagai Buku Teks Kuliah</label>
            </div>

            <div class="md:col-span-2 mt-4 p-4 border border-dashed border-slate-300 rounded-xl bg-slate-50">
                <label class="block text-sm font-bold text-slate-700 mb-2">File Buku (PDF) <span class="text-red-500">*</span></label>
                <input type="file" name="file_pdf" accept=".pdf" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer">
                <p class="text-xs text-slate-500 mt-2">Maksimal ukuran file: 30MB</p>
            </div>

            <div class="md:col-span-2 p-4 border border-dashed border-slate-300 rounded-xl bg-slate-50">
                <label class="block text-sm font-bold text-slate-700 mb-2">Cover Buku (Gambar JPG/PNG)</label>
                <input type="file" name="cover_image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 transition cursor-pointer">
                <p class="text-xs text-slate-500 mt-2">Opsional. Maksimal ukuran file: 2MB.</p>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
            <a href="{{ route('admin.books') }}" class="px-6 py-2.5 rounded-lg border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-8 py-2.5 rounded-lg font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                Simpan Buku
            </button>
        </div>
    </form>
</div>
@endsection
