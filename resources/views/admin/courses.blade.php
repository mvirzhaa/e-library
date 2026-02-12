@extends('layouts.admin_layout')

@section('content')
    <div class="flex gap-6">
        <div class="w-1/3 bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
            <h3 class="font-bold text-lg mb-4 text-slate-800">Tambah Mata Kuliah</h3>
            <form action="{{ route('admin.courses.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-600 mb-2">Nama Mata Kuliah</label>
                    <input type="text" name="name" placeholder="Contoh: Algoritma" required class="w-full border border-slate-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <button class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-bold hover:bg-blue-700 transition">Simpan</button>
            </form>
        </div>

        <div class="w-2/3 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-lg text-slate-800">Daftar Mata Kuliah</h3>
            </div>
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="p-4 font-semibold text-slate-600">Nama Mata Kuliah</th>
                        <th class="p-4 text-right font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($courses as $course)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-medium text-slate-700">{{ $course->name }}</td>
                        <td class="p-4 text-right">
                            <form action="{{ route('admin.courses.delete', $course->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?');">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 text-sm font-semibold bg-red-50 px-3 py-1 rounded-full">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="p-8 text-center text-slate-400">
                            Belum ada data mata kuliah.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
