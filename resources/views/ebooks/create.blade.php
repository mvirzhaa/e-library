<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Buku - E-Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                        <div class="bg-blue-600 text-white p-2 rounded-lg font-bold">EL</div>
                        <span class="text-xl font-bold tracking-tight text-slate-900">E-Library</span>
                    </a>
                </div>

                <div class="flex items-center gap-6">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500">Mahasiswa</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex-grow py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">

            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kontribusi Jurnal & Buku</h1>
                <p class="text-slate-500 mt-2">Bagikan referensi akademikmu untuk membantu rekan mahasiswa lainnya.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="bg-slate-50 px-8 py-4 border-b border-slate-100 flex items-center">
                    <div class="w-3 h-3 bg-red-400 rounded-full mr-2"></div>
                    <div class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></div>
                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                    <span class="ml-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Form Upload Baru</span>
                </div>

                <form action="{{ route('ebooks.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Buku / Jurnal</label>
                        <input type="text" name="judul_buku" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-slate-700 placeholder-slate-400" placeholder="Contoh: Pengantar Algoritma Pemrograman">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Penerbit</label>
                            <input type="text" name="penerbit" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Nama Penerbit">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Terbit</label>
                            <input type="number" name="tahun_terbit" required min="1900" max="2099" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="2024">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                        <div class="relative">
                            <select name="kategori" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none transition appearance-none bg-white">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="is_buku_kuliah" name="is_buku_kuliah" type="checkbox" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_buku_kuliah" class="font-medium text-slate-800">Buku Ajar / Mata Kuliah?</label>
                                <p class="text-slate-500 text-xs">Centang jika buku ini digunakan untuk mata kuliah tertentu.</p>
                            </div>
                        </div>

                        <div class="mt-3 ml-7">
                            <select name="mata_kuliah_terkait" class="w-full px-3 py-2 text-sm border border-blue-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="">-- Pilih Mata Kuliah (Opsional) --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->name }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Cover Buku (Gambar)</label>
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-xs text-slate-500">Klik untuk upload Cover</p>
                                </div>
                                <input type="file" name="cover_image" accept="image/*" class="hidden" />
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">File PDF <span class="text-red-500">*</span></label>
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-xs text-slate-500">Klik untuk upload PDF</p>
                                </div>
                                <input type="file" name="file_pdf" accept="application/pdf" required class="hidden" />
                            </label>
                        </div>
                    </div>

                    <div class="pt-6 flex items-center justify-end gap-4 border-t border-slate-100">
                        <a href="{{ route('dashboard') }}" class="px-6 py-2.5 rounded-xl text-slate-600 font-semibold hover:bg-slate-100 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-2.5 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-blue-300 transition transform hover:-translate-y-0.5">
                            Simpan Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
