<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savve — Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        /* Animasi masuk */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes slideRight {
            from { opacity: 0; transform: translateX(-32px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-12px); }
        }
        @keyframes pulse-ring {
            0%   { transform: scale(1);   opacity: 0.4; }
            100% { transform: scale(1.6); opacity: 0; }
        }
        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        @keyframes blob {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            50%       { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        }

        .animate-fade-up   { animation: fadeUp    0.6s ease forwards; }
        .animate-fade-in   { animation: fadeIn    0.8s ease forwards; }
        .animate-slide-right { animation: slideRight 0.7s ease forwards; }
        .animate-float     { animation: float     3s ease-in-out infinite; }
        .animate-blob      { animation: blob      7s ease-in-out infinite; }
        .animate-spin-slow { animation: spin-slow 20s linear infinite; }

        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        .delay-400 { animation-delay: 0.4s; opacity: 0; }
        .delay-500 { animation-delay: 0.5s; opacity: 0; }
        .delay-600 { animation-delay: 0.6s; opacity: 0; }

        /* Shimmer button */
        .btn-shimmer {
            background: linear-gradient(135deg, #0f2044, #1a3a6b, #1e4d8c, #1a3a6b, #0f2044);
            background-size: 200% auto;
            animation: shimmer 3s linear infinite;
            box-shadow: 0 8px 32px rgba(15,32,68,0.35);
            transition: all 0.3s ease;
        }
        .btn-shimmer:hover {
            box-shadow: 0 12px 40px rgba(15,32,68,0.5);
            transform: translateY(-1px);
        }
        .btn-shimmer:active { transform: translateY(0px) scale(0.99); }

        /* Input focus */
        .input-field {
            transition: all 0.2s ease;
            border: 1.5px solid #e5e7eb;
        }
        .input-field:focus {
            border-color: #1a3a6b;
            box-shadow: 0 0 0 4px rgba(26,58,107,0.08);
            outline: none;
            background: #fff;
        }

        /* Pulse ring */
        .pulse-ring::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid #4a9eff;
            animation: pulse-ring 2s ease-out infinite;
        }

        /* Glass card */
        .glass {
            background: rgba(255,255,255,0.07);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.12);
        }

        /* Particle dots */
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>

<body class="min-h-screen flex overflow-hidden" style="background: #f0f4f8;">

    {{-- ═══════════════ LEFT PANEL ═══════════════ --}}
    <div class="hidden lg:flex w-[52%] flex-col justify-between p-14 relative overflow-hidden"
        style="background: linear-gradient(150deg, #091629 0%, #0f2044 40%, #162e5e 70%, #1a3a6b 100%);">

        {{-- Animated blobs background --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="animate-blob absolute w-96 h-96 opacity-20 top-[-80px] right-[-80px]"
                style="background: radial-gradient(circle, #1e4d8c, #4a9eff 60%, transparent)"></div>
            <div class="animate-blob absolute w-80 h-80 opacity-15 bottom-[-60px] left-[-60px]"
                style="background: radial-gradient(circle, #4a9eff, #1a3a6b 60%, transparent); animation-delay: 3s"></div>
            <div class="animate-blob absolute w-64 h-64 opacity-10 top-1/2 left-1/3"
                style="background: radial-gradient(circle, #ffffff, transparent); animation-delay: 1.5s"></div>

            {{-- Rotating ring --}}
            <div class="animate-spin-slow absolute w-[500px] h-[500px] rounded-full border border-white/5"
                style="top: 50%; left: 50%; transform: translate(-50%, -50%)"></div>
            <div class="animate-spin-slow absolute w-[380px] h-[380px] rounded-full border border-white/5"
                style="top: 50%; left: 50%; transform: translate(-50%, -50%); animation-direction: reverse; animation-duration: 15s"></div>

            {{-- Particles --}}
            <div class="particle w-2 h-2" style="top: 20%; left: 15%; animation-delay: 0s"></div>
            <div class="particle w-1.5 h-1.5" style="top: 60%; left: 80%; animation-delay: 1s"></div>
            <div class="particle w-1 h-1" style="top: 80%; left: 30%; animation-delay: 2s"></div>
            <div class="particle w-2.5 h-2.5" style="top: 35%; left: 70%; animation-delay: 0.5s"></div>
            <div class="particle w-1 h-1" style="top: 15%; left: 55%; animation-delay: 1.5s"></div>
        </div>

        {{-- Logo --}}
        <div class="relative z-10 animate-slide-right">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="pulse-ring relative w-11 h-11 glass rounded-xl flex items-center justify-center">
                        <span class="text-xl">🗃️</span>
                    </div>
                </div>
                <div>
                    <p class="text-white font-black text-xl tracking-tight">Vendor Savve</p>
                    <p class="text-xs font-medium tracking-widest uppercase" style="color: #4a9eff">Storage Management</p>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="relative z-10">

            {{-- Badge --}}
            <div class="animate-fade-up delay-100 inline-flex items-center gap-2 glass px-4 py-2 rounded-full mb-6">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-xs text-blue-200 font-medium">Sistem aktif & berjalan optimal</span>
            </div>

            <h1 class="animate-fade-up delay-200 text-5xl font-black text-white leading-[1.1] mb-5">
                Kelola<br>
                <span style="background: linear-gradient(90deg, #4a9eff, #a5c8ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Penitipan
                </span><br>
                Lebih Cerdas
            </h1>

            <p class="animate-fade-up delay-300 text-blue-200 text-sm leading-relaxed max-w-sm mb-8">
                Platform manajemen penitipan barang berbasis web untuk operasional event yang efisien, akurat, dan terstruktur.
            </p>

            {{-- Feature cards --}}
            <div class="animate-fade-up delay-400 space-y-3">
                @foreach([
                    ['🔄', 'Transaksi real-time & otomatis',       'Eliminasi pencatatan manual'],
                    ['🔒', 'Nomor transaksi unik & tervalidasi',    'Anti duplikasi & konflik'],
                    ['📊', 'Laporan & export Excel instan',         'Data akurat setiap saat'],
                ] as $f)
                <div class="glass rounded-2xl px-4 py-3 flex items-center gap-4 hover:bg-white/10 transition duration-300">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base flex-shrink-0"
                        style="background: rgba(74,158,255,0.2)">{{ $f[0] }}</div>
                    <div>
                        <p class="text-white text-sm font-semibold">{{ $f[1] }}</p>
                        <p class="text-blue-300 text-xs">{{ $f[2] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom --}}
        <div class="relative z-10 animate-fade-up delay-500">
            <div class="flex items-center justify-between">
                <p class="text-blue-400 text-xs">© {{ date('Y') }} Vendor Savve. All rights reserved.</p>
                <div class="flex gap-1.5">
                    <div class="w-1.5 h-1.5 bg-blue-400 rounded-full"></div>
                    <div class="w-4 h-1.5 rounded-full" style="background: #4a9eff"></div>
                    <div class="w-1.5 h-1.5 bg-blue-400 rounded-full"></div>
                </div>
            </div>
        </div>

    </div>

    {{-- ═══════════════ RIGHT PANEL ═══════════════ --}}
    <div class="flex-1 flex items-center justify-center p-6 lg:p-16 relative overflow-hidden">

        {{-- Background decoration --}}
        <div class="absolute top-0 right-0 w-72 h-72 rounded-full opacity-30 pointer-events-none"
            style="background: radial-gradient(circle, #dbeafe, transparent); transform: translate(30%, -30%)"></div>
        <div class="absolute bottom-0 left-0 w-56 h-56 rounded-full opacity-20 pointer-events-none"
            style="background: radial-gradient(circle, #dbeafe, transparent); transform: translate(-30%, 30%)"></div>

        <div class="w-full max-w-[420px] relative z-10">

            {{-- Mobile Logo --}}
            <div class="lg:hidden flex items-center gap-3 mb-8 animate-fade-up">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-base"
                    style="background: #1a3a6b">🗃️</div>
                <div>
                    <p class="font-black text-gray-800">Vendor Savve</p>
                    <p class="text-gray-400 text-xs">Storage Management</p>
                </div>
            </div>

            {{-- Greeting --}}
            <div class="animate-fade-up delay-100 mb-8">
                <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color: #1a3a6b">
                    Selamat Datang Kembali 👋
                </p>
                <h2 class="text-3xl font-black text-gray-900 leading-tight">
                    Masuk ke Akun Anda
                </h2>
                <p class="text-gray-400 text-sm mt-2">Kelola operasional penitipan barang hari ini.</p>
            </div>

            {{-- Error Alert --}}
            @if($errors->any())
            <div class="animate-fade-up mb-6 flex items-start gap-3 px-4 py-3.5 rounded-2xl border"
                style="background: #fff5f5; border-color: #fecaca;">
                <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="text-red-500 text-xs">!</span>
                </div>
                <div>
                    <p class="text-red-700 font-semibold text-sm">Email atau password salah</p>
                    <p class="text-red-400 text-xs mt-0.5">Periksa kembali dan coba lagi.</p>
                </div>
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div class="animate-fade-up delay-200">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 flex items-center justify-center text-gray-400">
                            ✉
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="input-field w-full bg-gray-50 rounded-xl pl-11 pr-4 py-3.5 text-sm text-gray-800 placeholder-gray-400"
                            placeholder="email@example.com">
                    </div>
                </div>

                {{-- Password --}}
                <div class="animate-fade-up delay-300">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm">🔒︎</div>
                        <input type="password" name="password" id="password" required
                            class="input-field w-full bg-gray-50 rounded-xl pl-11 pr-12 py-3.5 text-sm text-gray-800 placeholder-gray-400"
                            placeholder="Masukkan password">
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition text-sm">
                            <span id="eye-icon">👁</span>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="animate-fade-up delay-400 flex items-center justify-between">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember"
                            class="w-4 h-4 rounded border-gray-300 cursor-pointer"
                            style="accent-color: #1a3a6b">
                        <span class="text-sm text-gray-500">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="animate-fade-up delay-500 pt-2">
                    <button type="submit" class="btn-shimmer w-full py-4 rounded-xl text-white font-bold text-sm tracking-wide flex items-center justify-center gap-2">
                        <span>Masuk ke Dashboard</span>
                        <span class="text-base">→</span>
                    </button>
                </div>
            </form>

            {{-- Divider --}}
            <div class="animate-fade-up delay-600 flex items-center gap-4 my-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <p class="text-xs text-gray-400 font-medium">Hak Akses Sistem</p>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Role Cards --}}
            <div class="animate-fade-up delay-600 grid grid-cols-2 gap-3">
                <div class="group p-4 rounded-2xl border border-gray-100 bg-white hover:border-blue-200 hover:shadow-md transition duration-300 cursor-default">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base mb-3 transition duration-300"
                        style="background: #eef2ff">
                        ⚙️
                    </div>
                    <p class="text-sm font-bold text-gray-800">Admin</p>
                    <p class="text-xs text-gray-400 mt-0.5">Full system access</p>
                    <div class="mt-2 flex gap-1">
                        <div class="h-1 w-6 rounded-full" style="background: #1a3a6b"></div>
                        <div class="h-1 w-3 rounded-full bg-gray-200"></div>
                        <div class="h-1 w-2 rounded-full bg-gray-200"></div>
                    </div>
                </div>
                <div class="group p-4 rounded-2xl border border-gray-100 bg-white hover:border-orange-200 hover:shadow-md transition duration-300 cursor-default">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base mb-3 transition duration-300"
                        style="background: #fff7ed">
                        🧾
                    </div>
                    <p class="text-sm font-bold text-gray-800">Kasir</p>
                    <p class="text-xs text-gray-400 mt-0.5">Storage flow access</p>
                    <div class="mt-2 flex gap-1">
                        <div class="h-1 w-4 rounded-full" style="background: #ea580c"></div>
                        <div class="h-1 w-3 rounded-full bg-gray-200"></div>
                        <div class="h-1 w-2 rounded-full bg-gray-200"></div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <p class="animate-fade-up delay-600 text-center text-xs text-gray-300 mt-8">
                © {{ date('Y') }} Vendor Savve Information Systems
            </p>

        </div>
    </div>

</body>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type     = 'text';
            icon.textContent = '🙈';
        } else {
            input.type     = 'password';
            icon.textContent = '👁';
        }
    }
</script>

</html>