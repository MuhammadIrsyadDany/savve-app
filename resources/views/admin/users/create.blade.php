@extends('layouts.admin')
@section('title', 'Tambah Kasir')

@section('content')

<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Tambah Kasir</h1>
        <p class="text-gray-400 text-sm mt-1">Buat akun baru untuk kasir.</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
        class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 shadow-sm">
        ← Kembali
    </a>
</div>

<div class="flex gap-6">

    <div class="flex-1">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
                <h3 class="font-black text-gray-700 mb-4">Informasi Akun</h3>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="Nama lengkap kasir">
                    @error('name') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="email@example.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Password</label>
                    <input type="password" name="password"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="Minimal 6 karakter">
                    @error('password') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="Ulangi password">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #3730a3, #4f46e5)">
                    💾 Simpan Kasir
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="px-6 py-3.5 rounded-xl bg-gray-100 text-gray-600 font-bold text-sm hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Info Panel --}}
    <div class="w-72">
        <div class="rounded-2xl p-6 text-white"
            style="background: linear-gradient(135deg, #3730a3, #6366f1)">
            <div class="text-3xl mb-3">👤</div>
            <p class="font-black text-lg mb-2">Info Akun Kasir</p>
            <ul class="text-indigo-200 text-xs space-y-2 leading-relaxed">
                <li>• Kasir hanya bisa akses menu transaksi dan pengambilan barang.</li>
                <li>• Password minimal 6 karakter.</li>
                <li>• Kasir bisa login dan memilih event sendiri.</li>
                <li>• Akun bisa diedit atau dihapus kapan saja.</li>
            </ul>
        </div>
    </div>

</div>

@endsection