<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savve Admin — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body style="font-family: 'Inter', sans-serif; background: #f0f4f8;">

<div class="flex h-screen overflow-hidden">

    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="w-[220px] flex-shrink-0 flex flex-col"
        style="background: linear-gradient(180deg, #091629 0%, #0c1e3d 40%, #0f2044 100%);">

        {{-- Logo --}}
        <div class="px-5 py-5" style="border-bottom: 1px solid rgba(255,255,255,0.06)">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                    style="background: rgba(74,158,255,0.15); border: 1px solid rgba(74,158,255,0.25)">
                    <span class="text-base">🗃️</span>
                </div>
                <div>
                    <p class="font-black text-white text-base leading-none">Savve</p>
                    <p class="text-xs font-medium mt-0.5" style="color: #4a9eff; letter-spacing: 0.05em">Admin Panel</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="text-xs font-bold px-3 mb-2 mt-1"
                style="color: rgba(255,255,255,0.2); letter-spacing: 0.1em; text-transform: uppercase; font-size: 9px">
                Main Menu
            </p>

            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="w-5 text-center text-sm">⊞</span> Dashboard
            </a>
            <a href="{{ route('admin.events.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                <span class="w-5 text-center text-sm">📅</span> Kelola Event
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="w-5 text-center text-sm">👤</span> Kelola Kasir
            </a>

            <p class="text-xs font-bold px-3 mb-2 mt-4"
                style="color: rgba(255,255,255,0.2); letter-spacing: 0.1em; text-transform: uppercase; font-size: 9px">
                Laporan
            </p>

            <a href="{{ route('admin.transaksis.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.transaksis.*') ? 'active' : '' }}">
                <span class="w-5 text-center text-sm">📋</span> Data Transaksi
            </a>
            <a href="{{ route('admin.laporan.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <span class="w-5 text-center text-sm">📈</span> Laporan Harian
            </a>
        </nav>

        {{-- User --}}
        <div class="px-3 py-4" style="border-top: 1px solid rgba(255,255,255,0.06)">
            <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl mb-2"
                style="background: rgba(255,255,255,0.04)">
                <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm text-white flex-shrink-0"
                    style="background: linear-gradient(135deg, #1e4d8c, #4a9eff)">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-white text-xs font-semibold truncate leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-xs mt-0.5" style="color: #4a9eff">Administrator</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left"
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
            style="border-bottom: 1px solid #e8edf5; box-shadow: 0 1px 8px rgba(0,0,0,0.04)">

            <div class="flex-1 max-w-sm">
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" style="font-size: 12px">🔍</span>
                    <input type="text" placeholder="Cari transaksi, penitip..."
                        class="w-full rounded-xl pl-9 pr-4 py-2.5 text-sm transition"
                        style="background: #f8faff; border: 1.5px solid #e8edf5; font-size: 13px; color: #374151;"
                        onfocus="this.style.borderColor='#4a9eff'; this.style.boxShadow='0 0 0 3px rgba(74,158,255,0.1)'"
                        onblur="this.style.borderColor='#e8edf5'; this.style.boxShadow='none'">
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <button class="relative w-9 h-9 flex items-center justify-center rounded-xl transition text-gray-500"
                    style="background: #f8faff; border: 1.5px solid #e8edf5; font-size: 13px"
                    onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='#f8faff'">
                    🔔
                    <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                </button>
                <button class="w-9 h-9 flex items-center justify-center rounded-xl transition text-gray-500"
                    style="background: #f8faff; border: 1.5px solid #e8edf5; font-size: 13px"
                    onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='#f8faff'">
                    ⚙️
                </button>
                <div class="flex items-center gap-2.5 pl-3" style="border-left: 1.5px solid #e8edf5">
                    <div class="text-right">
                        <p class="text-xs font-bold leading-none" style="color: #0f2044">{{ auth()->user()->name }}</p>
                        <p class="text-xs mt-0.5" style="color: #4a9eff">Administrator</p>
                    </div>
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-sm text-white"
                        style="background: linear-gradient(135deg, #0f2044, #1e4d8c)">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6">

            @if(session('success'))
            <div class="anim-slide-down mb-5 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold"
                style="background: #f0fdf4; border: 1.5px solid #bbf7d0; color: #15803d">
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

</body>
</html>