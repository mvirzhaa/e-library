<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preview: {{ $ebook->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 h-screen flex flex-col overflow-hidden">

    <div class="bg-slate-800 text-white px-6 py-3 flex justify-between items-center shadow-lg z-10">
        <div class="flex items-center gap-4 overflow-hidden">
            <a href="{{ url()->previous() }}" class="text-slate-400 hover:text-white transition flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-sm md:text-base font-bold truncate text-slate-200">{{ $ebook->title }}</h1>
        </div>

        <div class="flex gap-3 flex-shrink-0">
            <a href="{{ route('ebooks.download', $ebook->id) }}" class="bg-blue-600 hover:bg-blue-500 px-4 py-2 rounded-lg text-xs md:text-sm font-bold transition flex items-center gap-2">
                <svg class="w-4 h-4 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download PDF
            </a>
        </div>
    </div>

    <div class="flex-grow w-full bg-slate-900">
        <iframe src="{{ asset('storage/' . $ebook->file_path) }}" class="w-full h-full border-none shadow-inner" allowfullscreen></iframe>
    </div>

</body>
</html>
