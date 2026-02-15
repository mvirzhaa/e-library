<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - E-Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-slate-900 text-slate-300 flex-shrink-0 hidden md:flex flex-col shadow-2xl z-20">
            <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-slate-950/50">
                <div class="bg-blue-600 text-white p-2.5 rounded-lg font-bold mr-3 text-sm shadow-lg shadow-blue-900/50">EL</div>
                <div>
                    <span class="block text-lg font-bold tracking-wider text-white leading-tight">ADMIN PANEL</span>
                    <span class="block text-[10px] text-slate-500 uppercase tracking-widest mt-0.5">E-Library System</span>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">

                @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20 font-semibold' : 'hover:bg-slate-800 hover:text-white font-medium' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-blue-200' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        Dashboard
                    </a>
                @endif

                <a href="{{ route('admin.books') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.books') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20 font-semibold' : 'hover:bg-slate-800 hover:text-white font-medium' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.books') ? 'text-blue-200' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    Manajemen Buku
                </a>

                @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                    <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20 font-semibold' : 'hover:bg-slate-800 hover:text-white font-medium' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.users') ? 'text-blue-200' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        Manajemen User
                    </a>

                    <a href="{{ route('admin.categories') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.categories') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20 font-semibold' : 'hover:bg-slate-800 hover:text-white font-medium' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.categories') ? 'text-blue-200' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        Kategori
                    </a>

                    <a href="{{ route('admin.courses') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.courses') ? 'bg-blue-600 text-white shadow-md shadow-blue-900/20 font-semibold' : 'hover:bg-slate-800 hover:text-white font-medium' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.courses') ? 'text-blue-200' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Mata Kuliah
                    </a>
                @endif
            </nav>

            <div class="p-4 border-t border-slate-800 bg-slate-950/30">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-red-600 text-slate-300 hover:text-white py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 group">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-hidden relative bg-slate-50">

            <header class="bg-white border-b border-slate-200 h-20 flex items-center justify-between px-6 md:px-8 z-10 flex-shrink-0 shadow-sm">
                <div class="flex items-center md:hidden">
                    <span class="font-bold text-lg text-slate-800">Admin Panel</span>
                </div>

                <div class="hidden md:block">
                    <h2 class="text-xl font-bold text-slate-800">Ruang Kerja</h2>
                    <p class="text-xs text-slate-500 font-medium">Sistem Kelola E-Library</p>
                </div>

                <div class="flex items-center gap-4 md:gap-6">
                    <a href="{{ route('dashboard') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span class="hidden sm:inline">Kembali ke Web</span>
                    </a>

                    <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-slate-800 leading-tight">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ auth()->user()->role ?? 'Admin' }}</p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold border-2 border-white shadow-md">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-6 md:p-8">
                @yield('content')
            </div>

        </main>
    </div>

</body>
</html>
