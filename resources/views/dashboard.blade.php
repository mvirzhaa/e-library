<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - E-Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 text-white p-2 rounded-lg font-bold">EL</div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">E-Library</span>
                </div>

                <div class="flex items-center gap-6">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-500 uppercase">{{ Auth::user()->role ?? 'Mahasiswa' }}</p>
                    </div>
                    @if(in_array(auth()->user()->role, ['admin', 'superadmin', 'dosen']))
                    <a href="{{ route('admin.books.create') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                        + Upload Buku
                    </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-500 hover:text-red-600 font-medium text-sm transition">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(request()->query->count() == 0 && isset($popularBooks) && $popularBooks->count() > 0)
            <div class="mb-12">
                <div class="flex items-center mb-6">
                    <div class="p-2 bg-yellow-100 rounded-lg mr-3 text-yellow-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900">Paling Sering Diakses</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($popularBooks as $book)
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-1 shadow-xl relative group overflow-hidden flex flex-col">
                        <div class="absolute top-0 right-0 p-3 opacity-10 pointer-events-none">
                            <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div class="bg-white rounded-xl h-full p-4 flex flex-col relative z-10">
                            <div class="flex items-start gap-4 mb-3">
                                <div class="w-16 h-20 bg-slate-200 rounded-lg flex-shrink-0 overflow-hidden">
                                    @if($book->cover_path)
                                        <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <h3 class="font-bold text-slate-800 line-clamp-2 text-sm leading-tight mb-1" title="{{ $book->title }}">{{ $book->title }}</h3>
                                    <p class="text-xs text-slate-500">{{ $book->download_count }}x Diunduh</p>
                                    <span class="inline-block mt-2 px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase rounded-full tracking-wide">
                                        {{ $book->category }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-auto flex gap-2 w-full pt-3">
                                <a href="{{ route('ebooks.preview', $book->id) }}" class="flex-1 text-center bg-indigo-50 text-indigo-700 border border-indigo-200 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-600 hover:text-white transition">
                                    Preview
                                </a>
                                <a href="{{ route('ebooks.download', $book->id) }}" class="flex-1 text-center bg-slate-900 text-white py-1.5 rounded-lg text-xs font-bold hover:bg-blue-600 transition">
                                    Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 mb-8">
                <form action="{{ route('dashboard') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Cari Judul</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik judul buku..." class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori</label>
                            <select name="kategori" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}" {{ request('kategori') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mata Kuliah</label>
                            <select name="mata_kuliah" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                                <option value="">Semua Matkul</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->name }}" {{ request('mata_kuliah') == $course->name ? 'selected' : '' }}>{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <div class="flex-grow">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tahun</label>
                                <select name="year" class="w-full px-3 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white">
                                    <option value="">Semua</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 transition h-[42px] flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-slate-900">
                    @if(request()->query->count() > 0)
                        Hasil Pencarian
                        <a href="{{ route('dashboard') }}" class="ml-2 text-xs font-medium text-red-500 hover:underline border border-red-200 bg-red-50 px-2 py-1 rounded">Reset Filter</a>
                    @else
                        Eksplorasi Buku Terbaru
                    @endif
                </h2>
                <span class="text-sm text-slate-500">Menampilkan {{ $ebooks->count() }} dari {{ $ebooks->total() }} buku</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($ebooks as $book)
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 border border-slate-100 overflow-hidden flex flex-col h-full">

                    <div class="h-60 bg-slate-100 relative overflow-hidden">
                        @if($book->cover_path)
                            <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-slate-400">
                                <span class="text-4xl mb-2">📚</span>
                                <span class="text-sm font-medium">No Cover</span>
                            </div>
                        @endif
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-slate-700 shadow-sm border border-white/50">
                            {{ $book->category }}
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-slate-800 leading-snug line-clamp-2 mb-1 group-hover:text-blue-600 transition" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h3>
                            <p class="text-sm text-slate-500 font-medium">{{ $book->publisher }} <span class="mx-1">•</span> {{ $book->publish_year }}</p>
                            @if($book->related_course)
                                <p class="text-xs text-blue-500 mt-2 bg-blue-50 inline-block px-2 py-1 rounded">📚 {{ $book->related_course }}</p>
                            @endif
                        </div>

                        <div class="mt-auto pt-4 border-t border-slate-100 flex flex-col xl:flex-row items-start xl:items-center justify-between gap-3">
                            <div class="flex items-center text-slate-400 text-xs font-semibold">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                {{ $book->download_count }} Unduhan
                            </div>
                            <div class="flex items-center gap-2 w-full xl:w-auto">
                                <a href="{{ route('ebooks.preview', $book->id) }}" class="flex-1 xl:flex-none text-center text-indigo-600 hover:text-white hover:bg-indigo-600 border border-indigo-200 hover:border-indigo-600 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                    Preview
                                </a>
                                <a href="{{ route('ebooks.download', $book->id) }}" class="flex-1 xl:flex-none text-center text-blue-600 hover:text-white hover:bg-blue-600 border border-blue-200 hover:border-blue-600 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                    Unduh
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="inline-block p-4 rounded-full bg-slate-100 text-slate-400 mb-4 text-4xl">🔍</div>
                        <h3 class="text-lg font-bold text-slate-700">Tidak ditemukan</h3>
                        <p class="text-slate-500">Coba ubah filter atau kata kunci pencarianmu.</p>
                        <a href="{{ route('dashboard') }}" class="inline-block mt-4 text-blue-600 font-bold hover:underline">Reset Semua Filter</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $ebooks->links() }}
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-8 text-center text-slate-400 text-sm">
        &copy; {{ date('Y') }} E-Library Kampus. All rights reserved.
    </footer>
</body>
</html>
