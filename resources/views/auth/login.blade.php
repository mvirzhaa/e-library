<x-guest-layout>
    <div class="text-center mb-8">

        <h2 class="text-2xl font-bold text-slate-800">Selamat Datang Kembali!</h2>
        <p class="text-slate-500 text-sm mt-1">Silakan masuk untuk mengakses perpustakaan.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-5">
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Kampus</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none text-slate-800 placeholder-slate-400"
                placeholder="nama@mahasiswa.ac.id">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors hover:underline">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none text-slate-800 placeholder-slate-400"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mb-8">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer" name="remember">
                <span class="ml-2 text-sm text-slate-500 font-medium group-hover:text-slate-800 transition-colors">Ingat Saya</span>
            </label>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                    Buat Akun Baru
                </a>
            @endif
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-600/30 hover:bg-blue-700 hover:shadow-blue-700/40 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex justify-center items-center gap-2">
            Masuk Sekarang
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
        </button>
    </form>
</x-guest-layout>
