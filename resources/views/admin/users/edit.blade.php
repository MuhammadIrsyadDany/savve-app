@extends('layouts.admin')
@section('title', 'Edit Kasir')

@section('content')

<div class="anim-fade-up delay-1 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #1a3a6b">Management</p>
        <h1 class="text-xl lg:text-2xl font-black text-gray-900">Edit Kasir</h1>
        <p class="text-gray-400 text-sm mt-1">Ubah informasi akun kasir.</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition self-start flex-shrink-0"
        style="background: white; border: 1.5px solid #e2e8f0; color: #374151">
        ← Kembali
    </a>
</div>

<div class="flex flex-col lg:flex-row gap-6">
    <div class="flex-1">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf @method('PUT')

            <div class="anim-fade-up delay-2 bg-white rounded-2xl border border-gray-100 p-5 lg:p-6 mb-4"
                style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
                <h3 class="font-black text-gray-800 mb-4">Informasi Akun</h3>

                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full rounded-xl px-4 py-3 text-sm transition"
                        style="background: #f8faff; border: 1.5px solid #e2e8f0; color: #1e293b"
                        onfocus="this.style.borderColor='#4a9eff'; this.style.boxShadow='0 0 0 3px rgba(74,158,255,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                    @error('name') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full rounded-xl px-4 py-3 text-sm transition"
                        style="background: #f8faff; border: 1.5px solid #e2e8f0; color: #1e293b"
                        onfocus="this.style.borderColor='#4a9eff'; this.style.boxShadow='0 0 0 3px rgba(74,158,255,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                    @error('email') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">
                        Password Baru
                        <span class="font-normal normal-case ml-1" style="color: #94a3b8">(kosongkan jika tidak diganti)</span>
                    </label>
                    <input type="password" name="password"
                        class="w-full rounded-xl px-4 py-3 text-sm transition"
                        style="background: #f8faff; border: 1.5px solid #e2e8f0; color: #1e293b"
                        placeholder="Minimal 6 karakter"
                        onfocus="this.style.borderColor='#4a9eff'; this.style.boxShadow='0 0 0 3px rgba(74,158,255,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                    @error('password') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                        class="w-full rounded-xl px-4 py-3 text-sm transition"
                        style="background: #f8faff; border: 1.5px solid #e2e8f0; color: #1e293b"
                        placeholder="Ulangi password baru"
                        onfocus="this.style.borderColor='#4a9eff'; this.style.boxShadow='0 0 0 3px rgba(74,158,255,0.1)'"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                </div>
            </div>

            <div class="anim-fade-up delay-3 flex gap-3">
                <button type="submit"
                    class="flex-1 py-3.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #0f2044, #1e4d8c); box-shadow: 0 4px 12px rgba(15,32,68,0.2)">
                    💾 Update Kasir
                </button>
                <a href="{{ route('admin.users.index') }}"
                    class="px-4 lg:px-6 py-3.5 rounded-xl font-bold text-sm flex-shrink-0"
                    style="background: #f1f5f9; color: #64748b">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <div class="w-full lg:w-64 flex-shrink-0 space-y-4">
        <div class="anim-fade-up delay-2 bg-white rounded-2xl border border-gray-100 p-5"
            style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
            <p class="text-xs font-bold uppercase tracking-wider mb-4" style="color: #94a3b8">Statistik Kasir</p>
            <div class="flex items-center gap-3 mb-4 pb-4" style="border-bottom: 1px solid #f1f5f9">
                <div class="w-12 h-12 rounded-full flex items-center justify-center font-black text-lg text-white flex-shrink-0"
                    style="background: linear-gradient(135deg, #0f2044, #4a9eff)">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400">Bergabung {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
            <div class="space-y-2.5">
                @foreach([
                    ['Total Transaksi', $user->transaksis->count(), '#0f2044'],
                    ['Masih Dititip',   $user->transaksis->where('status','dititip')->count(), '#ea580c'],
                    ['Sudah Diambil',   $user->transaksis->where('status','sudah_diambil')->count(), '#15803d'],
                ] as $s)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">{{ $s[0] }}</span>
                    <span class="font-black" style="color: {{ $s[2] }}">{{ $s[1] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection