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
    @if(auth()->user()->role === 'superadmin' && auth()->user()->id !== $user->id)
        <form action="{{ route('admin.users.update_role', $user->id) }}" method="POST" class="flex items-center gap-2">
            @csrf
            @method('PATCH')
            <select name="role" class="text-xs border border-slate-300 rounded-md px-2 py-1 focus:ring-blue-500 outline-none">
                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User (Mahasiswa)</option>
                <option value="dosen" {{ $user->role === 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="superadmin" {{ $user->role === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded-md text-xs font-bold hover:bg-blue-700 transition">Save</button>
        </form>
    @else
        @if($user->role === 'superadmin')
            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">👑 Superadmin</span>
        @elseif($user->role === 'admin')
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">🛡️ Admin</span>
        @elseif($user->role === 'dosen')
            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">👨‍🏫 Dosen</span>
        @else
            <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-bold">🎓 Mahasiswa</span>
        @endif
    @endif
</td>
                    <td class="px-6 py-4 text-center">
                        @if(auth()->user()->id !== $user->id)
                            <form action="{{ route('admin.users.update_role', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded-md text-xs font-bold hover:bg-red-700 transition">Delete</button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400 italic">Tidak bisa menghapus diri sendiri</span>
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
