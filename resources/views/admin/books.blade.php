@extends('layouts.admin_layout')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Buku</h2>

        <a href="{{ route('admin.books.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
        + Tambah Buku
    </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold uppercase">Cover</th>
                    <th class="px-6 py-4 text-sm font-semibold uppercase">Judul Buku</th>
                    <th class="px-6 py-4 text-sm font-semibold uppercase">Penerbit</th>
                    <th class="px-6 py-4 text-sm font-semibold uppercase">Unduhan</th>
                    <th class="px-6 py-4 text-sm font-semibold uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($books as $book)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        @if($book->cover_path)
                            <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $book->title }}">
                        @else
                            <div class="h-12 w-8 bg-gray-200 rounded flex items-center justify-center text-xs">N/A</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $book->title }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $book->publisher }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $book->download_count }}</td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.books.delete', $book->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini selamanya?');">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 font-medium text-sm bg-red-50 px-3 py-1 rounded-full hover:bg-red-100 transition">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">
            {{ $books->links() }}
        </div>
    </div>
@endsection
