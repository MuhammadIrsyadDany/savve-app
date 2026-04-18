@extends('layouts.admin')
@section('title', 'Tambah Kasir')

@section('content')

<div class="anim-fade-up delay-1 flex justify-between items-start mb-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #1a3a6b">Management</p>
        <h1 class="text-2xl font-black text-gray-900">Tambah Kasir</h1>
        <p class="text-gray-400 text-sm mt-1">Buat akun baru untuk kasir.</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition flex-shrink-0"
        style="background: white; border: 1.5px solid #e2e8f0; color: #374151"
        onmouseover="this.style.background='#f8faff'" onmouseout="this.style.background='white'">
        ← Kembali
    </a>
</div>

<div class="flex gap-6">
    <div class="flex-1">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="anim-fade-up delay-2 bg-white rounded-2xl border border-gray-100 p-6 mb-4"
                style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
                <h3 class="font-black text-gray-800 mb-4">Informasi Akun</h3>

                @foreach([
                    ['name', 'Nama Lengkap', 'text', 'Nama lengkap kasir'],
                    ['email', 'Email', 'email', 'email@example.com'],
                ] as $f)
                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">{{ $f[1] }}</label>
                    <input type="{{ $f[2] }}" name="{{ $f[0] }}" value="{{ old($f[0]) }}"
                        class="w-full rounded-xl px-4 py-3 text-sm transition"
                        style="background: #f8faff; border: 1.5px solid #e2e8f0; color: #1e293b"
                        placeholder="{{ $f[3] }}"
                        onfocus="this.style.borderColor='#4a9eff'; this.style.boxShadow='0 0 0 3px rgba(74,158,255,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                    @error($f[0]) <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>
                @endforeach

                @foreach([
                    ['password', 'Password', 'Minimal 6 karakter'],
                    ['password_confirmation', 'Konfirmasi Password', 'Ulangi password'],
                ] as $f)
                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">{{ $f[1] }}</label>
                    <input type="password" name="{{ $f[0] }}"
                        class="w-full rounded-xl px-4 py-3 text-sm transition"
                        style="background: #f8faff; border: 1.5px solid #e2e8f0; color: #1e293b"
                        placeholder="{{ $f[2] }}"
                        onfocus="this.style.borderColor='#4a9eff'; this.style.boxShadow='0 0 0 3px rgba(74,158,255,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                    @error($f[0]) <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>
                @endforeach
            </div>

            <div class="anim-fade-up delay-3 flex gap-3">
                <button type="submit"
                    class="flex-1 py-3.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #0f2044, #1e4d8c); box-shadow: 0 4px 12px rgba(15,32,68,0.2)">
                    💾 Simpan Kasir
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="px-6 py-3.5 rounded-xl font-bold text-sm"
                    style="background: #f1f5f9; color: #64748b">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <div class="w-64 flex-shrink-0">
        <div class="anim-fade-up delay-2 rounded-2xl p-5 text-white"
            style="background: linear-gradient(150deg, #091629, #0f2044, #1a3a6b)">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl mb-3"
                style="background: rgba(74,158,255,0.2)">👤</div>
            <p class="font-black text-base mb-3">Info Akun Kasir</p>
            <ul class="space-y-2.5">
                @foreach([
                    'Kasir hanya bisa akses menu transaksi & pengambilan.',
                    'Password minimal 6 karakter.',
                    'Kasir login dan pilih event sendiri.',
                    'Akun bisa diedit atau dihapus kapan saja.',
                ] as $tip)
                <li class="flex items-start gap-2 text-xs leading-relaxed" style="color: #93c5fd">
                    <span class="text-blue-400 mt-0.5 flex-shrink-0">•</span> {{ $tip }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@endsection