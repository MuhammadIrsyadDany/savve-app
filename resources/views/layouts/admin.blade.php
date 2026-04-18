<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savve Admin — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50" style="font-family: 'Inter', sans-serif;">

<div class="flex h-screen overflow-hidden">

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="w-[220px] flex-shrink-0 flex flex-col"
        style="background: linear-gradient(180deg, #0c1e3d 0%, #0f2044 50%, #122654 100%);">

        {{-- Logo --}}
        <div class="px-5 py-5 border-b" style="border-color: rgba(255,255,255,0.07)">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm flex-shrink-0"
                    style="background: rgba(74,158,255,0.2); border: 1px solid rgba(74,158,255,0.3)">
                    🗃️
                </div>
                <div>
                    <p class="text-white font-black text-base leading-none">Savve</p>
                    <p class="text-xs font-medium mt-0.5" style="color: #4a9eff; letter-spacing: 0.05em">Admin Panel</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="text-xs font-semibold px-3 mb-2" style="color: rgba(255,255,255,0.25); letter-spacing: 0.08em; text-transform: uppercase;">
                Main Menu
            </p>
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="text-base w-5 text-center">⊞</span>
                Dashboard
            </a>
            <a href="{{ route('admin.events.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                <span class="text-base w-5 text-center">📅</span>
                Kelola Event
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="text-base w-5 text-center">👤</span>
                Kelola Kasir
            </a>

            <p class="text-xs font-semibold px-3 mb-2 mt-4" style="color: rgba(255,255,255,0.25); letter-spacing: 0.08em; text-transform: uppercase;">
                Laporan
            </p>
            <a href="{{ route('admin.transaksis.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.transaksis.*') ? 'active' : '' }}">
                <span class="text-base w-5 text-center">📋</span>
                Data Transaksi
            </a>
            <a href="{{ route('admin.laporan.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <span class="text-base w-5 text-center">📈</span>
                Laporan Harian
            </a>
        </nav>

        {{-- User --}}
        <div class="px-3 py-4" style="border-top: 1px solid rgba(255,255,255,0.07)">
            <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl mb-2"
                style="background: rgba(255,255,255,0.05)">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0 text-white"
                    style="background: linear-gradient(135deg, #1e4d8c, #4a9eff)">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs" style="color: #4a9eff">Administrator</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="sidebar-link w-full text-left hover:text-red-400"
                    style="color: #ef4444; background: rgba(239,68,68,0.05)">
                    <span class="text-base w-5 text-center">🚪</span>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ═══ MAIN ═══ --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-100 px-6 py-3 flex items-center justify-between flex-shrink-0"
            style="box-shadow: 0 1px 8px rgba(0,0,0,0.04)">
            <div class="flex-1 max-w-sm">
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs">🔍</span>
                    <input type="text" placeholder="Cari transaksi, penitip..."
                        class="w-full bg-gray-50 border border-gray-100 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:border-blue-300 focus:ring-2 focus:ring-blue-50 transition"
                        style="font-size: 13px">
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button class="relative w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 hover:bg-gray-100 transition text-gray-500 text-sm">
                    🔔
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                </button>
                <button class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 hover:bg-gray-100 transition text-gray-500 text-sm">
                    ⚙️
                </button>
                <div class="flex items-center gap-2.5 pl-3 border-l border-gray-100">
                    <div class="text-right">
                        <p class="text-xs font-bold text-gray-800 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-xs mt-0.5" style="color: #1a3a6b">Administrator</p>
                    </div>
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm text-white"
                        style="background: linear-gradient(135deg, #0f2044, #1e4d8c)">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6 anim-fade-in">
            @if(session('success'))
            <div class="anim-slide-down mb-5 flex items-center gap-3 px-4 py-3 rounded-xl border text-sm font-medium"
                style="background: #f0fdf4; border-color: #bbf7d0; color: #15803d">
                <span>✓</span> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="anim-slide-down mb-5 flex items-center gap-3 px-4 py-3 rounded-xl border text-sm font-medium"
                style="background: #fff5f5; border-color: #fecaca; color: #dc2626">
                <span>⚠</span> {{ session('error') }}
            </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>