@extends('layouts.admin')
@section('title', 'Kelola Kasir')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Kelola Kasir</h1>
        <p class="text-gray-400 text-sm mt-1">Kelola akun kasir yang dapat mengakses sistem.</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
        style="background: linear-gradient(135deg, #3730a3, #4f46e5)">
        ➕ Tambah Kasir
    </a>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Kasir</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Transaksi</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Transaksi Hari Ini</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Bergabung</th>
                <th class="px-5 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-sm flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">Kasir</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4 text-gray-500">{{ $user->email }}</td>
                <td class="px-5 py-4">
                    <span class="font-bold text-gray-800">{{ $user->transaksis->count() }}</span>
                    <span class="text-xs text-gray-400 ml-1">transaksi</span>
                </td>
                <td class="px-5 py-4">
                    <span class="font-bold text-gray-800">
                        {{ $user->transaksis->where('created_at', '>=', today())->count() }}
                    </span>
                    <span class="text-xs text-gray-400 ml-1">hari ini</span>
                </td>
                <td class="px-5 py-4 text-gray-400 text-xs">
                    {{ $user->created_at->format('d M Y') }}
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.users.edit', $user) }}"
                            class="text-indigo-600 hover:underline text-xs font-semibold">Edit</a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                            onsubmit="return confirm('Hapus kasir ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-xs font-semibold">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-16 text-center">
                    <p class="text-4xl mb-3">👤</p>
                    <p class="text-gray-400 font-medium">Belum ada kasir.</p>
                    <a href="{{ route('admin.users.create') }}"
                        class="inline-block mt-3 text-sm text-indigo-600 font-semibold hover:underline">
                        Tambah kasir pertama →
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection