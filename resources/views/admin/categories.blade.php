@extends('layouts.admin_layout')

@section('content')
    <div class="flex gap-6">
        <div class="w-1/3 bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
            <h3 class="font-bold text-lg mb-4">Tambah Kategori</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Nama Kategori Baru" required class="w-full border p-2 rounded mb-3">
                <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Simpan</button>
            </form>
        </div>

        <div class="w-2/3 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-4 font-semibold">Nama Kategori</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($categories as $cat)
                    <tr>
                        <td class="p-4">{{ $cat->name }}</td>
                        <td class="p-4 text-right">
                            <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
