@extends('layouts.admin_layout')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen User</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold uppercase">Nama</th>
                    <th class="px-6 py-4 text-sm font-semibold uppercase">Email</th>
                    <th class="px-6 py-4 text-sm font-semibold uppercase">Role</th>
                    <th class="px-6 py-4 text-sm font-semibold uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        @if($user->role === 'admin')
                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">Admin</span>
                        @else
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">Mahasiswa</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($user->role !== 'admin')
                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?');">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 font-medium text-sm bg-red-50 px-3 py-1 rounded-full hover:bg-red-100 transition">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
@endsection
