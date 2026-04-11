<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savve — Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4"
    style="background: linear-gradient(135deg, #eef2ff 0%, #f0f4ff 50%, #ede9fe 100%);">

    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-xl px-10 py-10">

            {{-- Logo --}}
            <div class="flex items-center justify-center gap-2 mb-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-lg"
                    style="background: linear-gradient(135deg, #4f46e5, #6366f1)">
                    🗃️
                </div>
                <span class="text-xl font-bold text-indigo-700">Vendor Savve</span>
            </div>

            {{-- Title --}}
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Sistem Informasi Penitipan Barang</h1>
                <p class="text-gray-400 text-sm mt-2">Selamat datang kembali. Silakan masuk untuk mengelola inventaris gudang Anda.</p>
            </div>

            {{-- Alert Error --}}
            @if($errors->any())
            <div class="mb-5 flex items-start gap-3 px-4 py-3 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <span class="text-red-500 mt-0.5">⊙</span>
                <div>
                    <p class="text-red-600 font-semibold text-sm">Username atau Password Salah</p>
                    <p class="text-red-400 text-xs mt-0.5">Mohon periksa kembali kredensial Anda.</p>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                        Username
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">👤</span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white transition"
                            placeholder="Masukkan ID pengguna">
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Password
                        </label>
                        <span class="text-xs text-indigo-600 font-semibold cursor-pointer hover:underline">Lupa Password?</span>
                    </div>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">🔒</span>
                        <input type="password" name="password" id="password" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-10 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white transition"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            👁️
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2 mb-6">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="remember" class="text-sm text-gray-500">Ingat perangkat ini</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3.5 rounded-xl text-white font-semibold text-sm tracking-wide transition hover:opacity-90 flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #4338ca, #4f46e5)">
                    Masuk ke Dashboard →
                </button>
            </form>

            {{-- Info Akses --}}
            <div class="mt-8">
                <p class="text-center text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">
                    Informasi Akses Sistem
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex items-center gap-3 bg-indigo-50 rounded-xl px-4 py-3">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-sm">
                            ⚙️
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-700">Admin</p>
                            <p class="text-xs text-gray-400">Full Access</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-orange-50 rounded-xl px-4 py-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center text-sm">
                            🧾
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-700">Cashier</p>
                            <p class="text-xs text-gray-400">Storage Flow</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-6 space-y-1">
            <p class="text-xs text-gray-400">© {{ date('Y') }} Vendor Savve Information Systems. All rights reserved.</p>
            <div class="flex justify-center gap-4 text-xs text-gray-400">
                <span class="hover:text-gray-600 cursor-pointer">SECURITY</span>
                <span>•</span>
                <span class="hover:text-gray-600 cursor-pointer">PRIVACY</span>
                <span>•</span>
                <span class="hover:text-gray-600 cursor-pointer">HELP CENTER</span>
            </div>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</body>
</html>