<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Library Kampus Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-50">

    <nav class="absolute w-full z-20 top-0 left-0 px-8 py-6 flex justify-between items-center">
        <div class="text-2xl font-extrabold text-white tracking-tight">📚 ELIB<span class="text-blue-200">KAMPUS</span></div>
        <div class="space-x-4">
            @auth
                <a href="{{ route('dashboard') }}" class="text-white font-medium hover:text-blue-200 transition">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-white font-medium hover:text-blue-200 transition">Masuk</a>
                <a href="{{ route('register') }}" class="bg-white text-blue-700 px-5 py-2.5 rounded-full font-bold hover:bg-blue-50 transition shadow-lg">Daftar Akun</a>
            @endauth
        </div>
    </nav>

    <section class="relative bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-800 text-white min-h-[600px] flex items-center pt-10 overflow-hidden ">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-blue-500 rounded-full blur-3xl opacity-30"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-indigo-500 rounded-full blur-3xl opacity-30"></div>

        <div class="container mx-auto px-6 text-center relative z-10">
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight mb-6 tracking-tight">
                Jelajahi Ilmu <br> Tanpa <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-cyan-300">Batas Ruang</span>
            </h1>
            <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                Akses ribuan jurnal, e-book, dan karya ilmiah mahasiswa secara gratis. Tingkatkan literasi digitalmu sekarang.
            </p>
            <div class="flex justify-center gap-4 flex-col md:flex-row">
                <a href="{{ route('login') }}" class="px-8 py-4 bg-white text-blue-700 font-bold rounded-full hover:shadow-2xl hover:scale-105 transition transform duration-300">
                    Mulai Jelajahi
                </a>
                <a href="#fitur" class="px-8 py-4 border border-blue-300 text-white font-bold rounded-full hover:bg-white/10 transition backdrop-blur-sm">
                    Fitur Unggulan
                </a>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-20 -mt-20 relative z-20">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-xl hover:-translate-y-2 transition duration-300 border-b-4 border-blue-500">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-3xl mb-6">📖</div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Koleksi Lengkap</h3>
                    <p class="text-gray-500 leading-relaxed">Ribuan buku ajar dan referensi mata kuliah tersedia dalam format digital.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-xl hover:-translate-y-2 transition duration-300 border-b-4 border-indigo-500">
                    <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center text-3xl mb-6">🚀</div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Upload Karya</h3>
                    <p class="text-gray-500 leading-relaxed">Mahasiswa dapat berkontribusi dengan mengunggah jurnal atau skripsi.</p>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-xl hover:-translate-y-2 transition duration-300 border-b-4 border-cyan-500">
                    <div class="w-14 h-14 bg-cyan-100 rounded-xl flex items-center justify-center text-3xl mb-6">⚡</div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Akses Cepat</h3>
                    <p class="text-gray-500 leading-relaxed">Server cepat dan tampilan responsif memudahkan akses dari mana saja.</p>
                </div>
            </div>
        </div>
    </section>

</body>
</html>
