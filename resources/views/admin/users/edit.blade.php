@extends('layouts.admin')
@section('title', 'Edit Kasir')

@section('content')

<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Edit Kasir</h1>
        <p class="text-gray-400 text-sm mt-1">Ubah informasi akun kasir.</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
        class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 shadow-sm">
        ← Kembali
    </a>
</div>

<div class="flex gap-6">

    <div class="flex-1">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf @method('PUT')

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
                <h3 class="font-black text-gray-700 mb-4">Informasi Akun</h3>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    @error('name') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    @error('email') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">
                        Password Baru
                        <span class="text-gray-300 font-normal normal-case">(kosongkan jika tidak diganti)</span>
                    </label>
                    <input type="password" name="password"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="Minimal 6 karakter">
                    @error('password') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="Ulangi password baru">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #3730a3, #4f46e5)">
                    💾 Update Kasir
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="px-6 py-3.5 rounded-xl bg-gray-100 text-gray-600 font-bold text-sm hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Info Panel --}}
    <div class="w-72 space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Statistik Kasir</p>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400">Bergabung {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Total Transaksi</span>
                    <span class="font-bold text-gray-700">{{ $user->transaksis->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Masih Dititip</span>
                    <span class="font-bold text-orange-500">{{ $user->transaksis->where('status','dititip')->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Sudah Diambil</span>
                    <span class="font-bold text-green-600">{{ $user->transaksis->where('status','sudah_diambil')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection