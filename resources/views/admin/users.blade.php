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
                    <td class="p-4">
        @php
            $isSuperadmin = $user->role === 'superadmin';
            $amIAdmin = auth()->user()->role === 'admin';
            $amISuperadmin = auth()->user()->role === 'superadmin';
            $isMyself = auth()->user()->id === $user->id;

            // Logika Cerdas: Boleh edit JIKA (Saya Superadmin edit orang lain) ATAU (Saya Admin edit selain Superadmin dan diri sendiri)
            $canEdit = ($amISuperadmin && !$isMyself) || ($amIAdmin && !$isMyself && !$isSuperadmin);
        @endphp

        @if($canEdit)
            <form action="{{ route('admin.users.update_role', $user->id) }}" method="POST" class="flex flex-col gap-2">
                @csrf
                @method('PATCH')
                <div class="flex flex-wrap gap-2 items-center">
                    <select name="role" class="text-xs border border-slate-300 rounded-md px-2 py-1 outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="dosen" {{ $user->role === 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        @if($amISuperadmin)
                            <option value="superadmin" {{ $user->role === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                        @endif
                    </select>

                    <select name="is_active" class="text-xs border border-slate-300 rounded-md px-2 py-1 outline-none {{ $user->is_active ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                        <option value="1" {{ $user->is_active ? 'selected' : '' }}>🟢 Aktif</option>
                        <option value="0" {{ !$user->is_active ? 'selected' : '' }}>🔴 Nonaktif</option>
                    </select>

                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-md text-xs font-bold hover:bg-blue-700 transition">Save</button>
                </div>
            </form>
        @else
            <div class="flex flex-col gap-1.5 items-start">
                @if($user->role === 'superadmin')
                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-[10px] font-bold">👑 Superadmin</span>
                @elseif($user->role === 'admin')
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-bold">🛡️ Admin</span>
                @elseif($user->role === 'dosen')
                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-bold">👨‍🏫 Dosen</span>
                @else
                    <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-[10px] font-bold">🎓 Mahasiswa</span>
                @endif

                @if($user->is_active)
                    <span class="text-green-600 text-[10px] font-bold">🟢 Aktif</span>
                @else
                    <span class="text-red-600 text-[10px] font-bold">🔴 Nonaktif</span>
                @endif
            </div>
        @endif
    </td>

    <td class="p-4">
        @if($isMyself)
            <span class="text-xs text-slate-400 italic">Tidak bisa hapus diri sendiri</span>
        @elseif($amIAdmin && $isSuperadmin)
            <span class="text-[10px] font-bold text-red-500 border border-red-200 bg-red-50 px-2 py-1 rounded-md uppercase">Terlarang</span>
        @else
            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini secara permanen?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md text-xs font-bold hover:bg-red-700 transition shadow-sm">Delete</button>
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
