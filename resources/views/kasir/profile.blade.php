@extends('layouts.kasir')
@section('title', 'Profil Saya')

@section('content')

    <div class="anim-fade-up delay-1 mb-6">
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #7c3aed">Akun</p>
        <h1 class="text-xl lg:text-2xl font-black text-gray-900">Profil Saya</h1>
        <p class="text-gray-400 text-sm mt-1">Kelola informasi akun dan keamanan password kamu.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">

        {{-- Kiri --}}
        <div class="flex-1 space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden"
                style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">

                {{-- Tab Header --}}
                <div class="flex border-b border-gray-100">
                    <button onclick="switchTab('info')" id="tab-info"
                        class="flex-1 py-3.5 text-sm font-bold transition tab-btn active-tab">
                        👤 Informasi Profil
                    </button>
                    <button onclick="switchTab('password')" id="tab-password"
                        class="flex-1 py-3.5 text-sm font-bold transition tab-btn">
                        🔒 Ganti Password
                    </button>
                </div>

                {{-- Tab Info --}}
                <div id="panel-info" class="p-6">
                    @if (session('success') && !session('tab'))
                        <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold"
                            style="background: #faf5ff; border: 1.5px solid #ddd6fe; color: #7c3aed">
                            ✓ {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('kasir.profile.update') }}" method="POST">
                        @csrf @method('PUT')

                        <div class="mb-4">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5"
                                style="color: #64748b">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full rounded-xl px-4 py-3 text-sm transition"
                                style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #1e293b"
                                onfocus="this.style.borderColor='#a78bfa'; this.style.boxShadow='0 0 0 3px rgba(167,139,250,0.1)'"
                                onblur="this.style.borderColor='#ede9fe'; this.style.boxShadow='none'">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5"
                                style="color: #64748b">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full rounded-xl px-4 py-3 text-sm transition"
                                style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #1e293b"
                                onfocus="this.style.borderColor='#a78bfa'; this.style.boxShadow='0 0 0 3px rgba(167,139,250,0.1)'"
                                onblur="this.style.borderColor='#ede9fe'; this.style.boxShadow='none'">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="px-6 py-3 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                            style="background: linear-gradient(135deg, #5b21b6, #7c3aed)">
                            💾 Simpan Perubahan
                        </button>
                    </form>
                </div>

                {{-- Tab Password --}}
                <div id="panel-password" class="p-6 hidden">
                    @if (session('success') && session('tab') === 'password')
                        <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold"
                            style="background: #faf5ff; border: 1.5px solid #ddd6fe; color: #7c3aed">
                            ✓ {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('kasir.profile.password') }}" method="POST">
                        @csrf @method('PUT')

                        <div class="mb-4">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5"
                                style="color: #64748b">Password Saat Ini</label>
                            <input type="password" name="current_password"
                                class="w-full rounded-xl px-4 py-3 text-sm transition"
                                style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #1e293b"
                                placeholder="Masukkan password saat ini"
                                onfocus="this.style.borderColor='#a78bfa'; this.style.boxShadow='0 0 0 3px rgba(167,139,250,0.1)'"
                                onblur="this.style.borderColor='#ede9fe'; this.style.boxShadow='none'">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5"
                                style="color: #64748b">Password Baru</label>
                            <input type="password" name="password" class="w-full rounded-xl px-4 py-3 text-sm transition"
                                style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #1e293b"
                                placeholder="Minimal 6 karakter"
                                onfocus="this.style.borderColor='#a78bfa'; this.style.boxShadow='0 0 0 3px rgba(167,139,250,0.1)'"
                                onblur="this.style.borderColor='#ede9fe'; this.style.boxShadow='none'">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5"
                                style="color: #64748b">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                class="w-full rounded-xl px-4 py-3 text-sm transition"
                                style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #1e293b"
                                placeholder="Ulangi password baru"
                                onfocus="this.style.borderColor='#a78bfa'; this.style.boxShadow='0 0 0 3px rgba(167,139,250,0.1)'"
                                onblur="this.style.borderColor='#ede9fe'; this.style.boxShadow='none'">
                        </div>

                        <button type="submit"
                            class="px-6 py-3 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                            style="background: linear-gradient(135deg, #5b21b6, #7c3aed)">
                            🔒 Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kanan --}}
        <div class="w-full lg:w-72 flex-shrink-0 space-y-4">

            <div class="bg-white rounded-2xl border border-gray-100 p-5 text-center"
                style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
                <div class="w-20 h-20 rounded-full flex items-center justify-center font-black text-3xl text-white mx-auto mb-3"
                    style="background: linear-gradient(135deg, #5b21b6, #a78bfa)">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <p class="font-black text-gray-800 text-lg">{{ $user->name }}</p>
                <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold"
                    style="background: #faf5ff; color: #7c3aed">
                    🧾 Kasir
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-5" style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
                <p class="text-xs font-bold uppercase tracking-wider mb-3" style="color: #94a3b8">Statistik Saya</p>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Bergabung</span>
                        <span class="font-bold text-gray-700 text-sm">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Total Transaksi</span>
                        <span class="font-black" style="color: #5b21b6">{{ $user->transaksis->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Masih Dititip</span>
                        <span class="font-black" style="color: #ea580c">
                            {{ $user->transaksis->where('status', 'dititip')->count() }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Sudah Diambil</span>
                        <span class="font-black" style="color: #15803d">
                            {{ $user->transaksis->where('status', 'sudah_diambil')->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #1e293b, #334155)">
                <p class="font-bold text-sm mb-2">🔐 Tips Keamanan</p>
                <ul class="space-y-1.5 text-xs" style="color: #94a3b8">
                    <li>• Ganti password secara berkala</li>
                    <li>• Jangan bagikan password ke siapapun</li>
                    <li>• Gunakan kombinasi huruf & angka</li>
                    <li>• Minimal 6 karakter</li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        .tab-btn {
            color: #94a3b8;
            border-bottom: 2px solid transparent;
        }

        .active-tab {
            color: #5b21b6;
            border-bottom: 2px solid #7c3aed;
        }
    </style>

    <script>
        function switchTab(tab) {
            document.getElementById('panel-info').classList.add('hidden');
            document.getElementById('panel-password').classList.add('hidden');
            document.getElementById('tab-info').classList.remove('active-tab');
            document.getElementById('tab-password').classList.remove('active-tab');
            document.getElementById('panel-' + tab).classList.remove('hidden');
            document.getElementById('tab-' + tab).classList.add('active-tab');
        }

        @if (session('tab') === 'password' || $errors->has('current_password') || $errors->has('password'))
            switchTab('password');
        @endif
    </script>

@endsection
