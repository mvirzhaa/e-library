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
    <th class="p-4 font-semibold text-slate-600">Nama Kategori</th>
    <th class="p-4 font-semibold text-slate-600 text-center">Status</th> <th class="p-4 text-right font-semibold text-slate-600">Aksi</th>
</tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($categories as $cat)
                    <tr>
                        <td class="p-4 font-medium text-slate-700">{{ $cat->name }}</td>

<td class="p-4 text-center">
    @if($cat->is_active)
        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Aktif</span>
    @else
        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Nonaktif</span>
    @endif
</td>

<td class="p-4 text-right">
    <form action="{{ route('admin.categories.toggle', $cat->id) }}" method="POST">
        @csrf @method('PATCH')
        <button class="{{ $cat->is_active ? 'text-red-600 hover:text-red-800 bg-red-50' : 'text-green-600 hover:text-green-800 bg-green-50' }} px-4 py-1.5 rounded-full text-sm font-semibold transition">
            {{ $cat->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
        </button>
    </form>
</td>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
