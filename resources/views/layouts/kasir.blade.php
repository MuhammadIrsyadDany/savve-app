<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savve Kasir — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'Inter', sans-serif; background: #f5f3ff;">

<div class="flex h-screen overflow-hidden">

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="w-[220px] flex-shrink-0 flex flex-col"
        style="background: linear-gradient(180deg, #1e1035 0%, #2d1b69 40%, #3b2180 100%);">

        {{-- Logo --}}
        <div class="px-5 py-5" style="border-bottom: 1px solid rgba(255,255,255,0.06)">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                    style="background: rgba(167,139,250,0.2); border: 1px solid rgba(167,139,250,0.3)">
                    <span class="text-base">🗃️</span>
                </div>
                <div>
                    <p class="font-black text-white text-base leading-none">SAVVE</p>
                    <p class="text-xs font-medium mt-0.5" style="color: #a78bfa; letter-spacing: 0.05em">Kasir Panel</p>
                </div>
            </div>
        </div>

        {{-- Role Badge --}}
        <div class="mx-3 mt-3 px-3 py-2 rounded-xl flex items-center gap-2"
            style="background: rgba(167,139,250,0.1); border: 1px solid rgba(167,139,250,0.2)">
            <span class="w-2 h-2 rounded-full flex-shrink-0 animate-pulse" style="background: #00ff4c"></span>
            <p class="text-xs font-bold" style="color: #a78bfa">CASHIER MODE AKTIF</p>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="text-xs font-bold px-3 mb-2 mt-1"
                style="color: rgba(255,255,255,0.2); letter-spacing: 0.1em; text-transform: uppercase; font-size: 9px">
                Menu Utama
            </p>

            <a href="{{ route('kasir.dashboard') }}"
                class="kasir-link {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
                <span class="w-5 text-center text-sm">⊞</span> Dashboard
            </a>
            <a href="{{ route('kasir.transaksi.create') }}"
                class="kasir-link {{ request()->routeIs('kasir.transaksi.create') ? 'active' : '' }}">
                <span class="w-5 text-center text-sm">➕</span> Titip Barang
            </a>
            <a href="{{ route('kasir.pengambilan.index') }}"
                class="kasir-link {{ request()->routeIs('kasir.pengambilan.*') ? 'active' : '' }}">
                <span class="w-5 text-center text-sm">📦</span> Ambil Barang
            </a>
            <a href="{{ route('kasir.transaksi.index') }}"
                class="kasir-link {{ request()->routeIs('kasir.transaksi.index') ? 'active' : '' }}">
                <span class="w-5 text-center text-sm">📋</span> Riwayat Transaksi
            </a>
        </nav>

        {{-- User --}}
        <div class="px-3 py-4" style="border-top: 1px solid rgba(255,255,255,0.06)">
            <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl mb-2"
                style="background: rgba(255,255,255,0.04)">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm text-white flex-shrink-0"
                    style="background: linear-gradient(135deg, #5b21b6, #a78bfa)">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-white text-xs font-semibold truncate leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-xs mt-0.5" style="color: #a78bfa">Kasir</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="kasir-link w-full text-left"
                    style="color: #f87171 !important">
                    <span class="w-5 text-center text-sm">🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ═══ MAIN ═══ --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-white flex-shrink-0 flex items-center justify-between px-6 py-3.5"
            style="border-bottom: 1px solid #ede9fe; box-shadow: 0 1px 8px rgba(0,0,0,0.04)">

            <div class="flex-1 max-w-sm">
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" style="font-size: 12px">🔍</span>
                    <input type="text" placeholder="Cari transaksi atau nama barang..."
                        class="w-full rounded-xl pl-9 pr-4 py-2.5 text-sm transition"
                        style="background: #faf5ff; border: 1.5px solid #ede9fe; font-size: 13px; color: #374151"
                        onfocus="this.style.borderColor='#a78bfa'; this.style.boxShadow='0 0 0 3px rgba(167,139,250,0.1)'"
                        onblur="this.style.borderColor='#ede9fe'; this.style.boxShadow='none'">
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                {{-- Cashier Active Badge --}}
                <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold"
                    style="background: #faf5ff; color: #7c3aed; border: 1px solid #ddd6fe">
                    <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background: #00ff4c"></span>
                    Cashier Active
                </div>

                <button class="relative w-9 h-9 flex items-center justify-center rounded-xl transition text-gray-500"
                    style="background: #faf5ff; border: 1.5px solid #ede9fe; font-size: 13px"
                    onmouseover="this.style.background='#ede9fe'" onmouseout="this.style.background='#faf5ff'">
                    🔔
                </button>
                <button class="w-9 h-9 flex items-center justify-center rounded-xl transition text-gray-500"
                    style="background: #faf5ff; border: 1.5px solid #ede9fe; font-size: 13px"
                    onmouseover="this.style.background='#ede9fe'" onmouseout="this.style.background='#faf5ff'">
                    ⚙️
                </button>

                <div class="flex items-center gap-2.5 pl-3" style="border-left: 1.5px solid #ede9fe">
                    <div class="text-right">
                        <p class="text-xs font-bold leading-none" style="color: #1e1035">{{ auth()->user()->name }}</p>
                        <p class="text-xs mt-0.5" style="color: #a78bfa">Kasir Mode</p>
                    </div>
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-sm text-white"
                        style="background: linear-gradient(135deg, #5b21b6, #a78bfa)">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6">

            @if(session('success'))
            <div class="anim-slide-down mb-5 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold"
                style="background: #faf5ff; border: 1.5px solid #ddd6fe; color: #7c3aed">
                <span>✓</span> {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="anim-slide-down mb-5 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold"
                style="background: #fff5f5; border: 1.5px solid #fecaca; color: #dc2626">
                <span>⚠</span> {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<style>
.kasir-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 500;
    color: #94a3b8;
    transition: all 0.2s ease;
    position: relative;
    text-decoration: none;
}
.kasir-link:hover {
    background: rgba(255,255,255,0.06);
    color: #e2e8f0;
}
.kasir-link.active {
    background: rgba(167,139,250,0.15);
    color: #fff;
    font-weight: 600;
}
.kasir-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 20%;
    height: 60%;
    width: 3px;
    background: #a78bfa;
    border-radius: 0 4px 4px 0;
}
</style>

</body>
</html>