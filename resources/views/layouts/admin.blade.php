<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savve Admin — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }

        /* Sidebar transition */
        #sidebar {
            transition: transform 0.3s ease, width 0.3s ease;
        }

        /* Overlay */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 40;
            backdrop-filter: blur(2px);
        }
        #sidebar-overlay.active { display: block; }

        /* Mobile sidebar */
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 50;
                transform: translateX(-100%);
            }
            #sidebar.open {
                transform: translateX(0);
            }
        }

        .sidebar-link {
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
        .sidebar-link:hover {
            background: rgba(255,255,255,0.06);
            color: #e2e8f0;
        }
        .sidebar-link.active {
            background: rgba(74,158,255,0.12);
            color: #fff;
            font-weight: 600;
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 3px;
            background: #4a9eff;
            border-radius: 0 4px 4px 0;
        }

        .stat-card { transition: all 0.3s ease; cursor: default; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 32px rgba(0,0,0,0.08); }
        .table-row { transition: background 0.15s ease; }
        .table-row:hover { background: #f8faff; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim-fade-up   { animation: fadeUp   0.5s ease forwards; }
        .anim-slide-down { animation: slideDown 0.4s ease forwards; }
        .anim-fade-in   { animation: fadeUp 0.5s ease forwards; }
        .anim-scale-in  { animation: fadeUp 0.4s ease forwards; }
        .delay-1 { animation-delay: 0.05s; opacity: 0; }
        .delay-2 { animation-delay: 0.10s; opacity: 0; }
        .delay-3 { animation-delay: 0.15s; opacity: 0; }
        .delay-4 { animation-delay: 0.20s; opacity: 0; }
        .delay-5 { animation-delay: 0.25s; opacity: 0; }
        .delay-6 { animation-delay: 0.30s; opacity: 0; }
        .delay-7 { animation-delay: 0.35s; opacity: 0; }
        .delay-8 { animation-delay: 0.40s; opacity: 0; }
    </style>
</head>
<body style="background: #f0f4f8;">

{{-- Overlay mobile --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div class="flex h-screen overflow-hidden">

    {{-- ═══ SIDEBAR ═══ --}}
    <aside id="sidebar" class="w-[220px] flex-shrink-0 flex flex-col lg:relative lg:translate-x-0"
        style="background: linear-gradient(180deg, #091629 0%, #0c1e3d 40%, #0f2044 100%);">

        {{-- Logo + Close Button (mobile) --}}
        <div class="px-5 py-4 flex items-center justify-between"
            style="border-bottom: 1px solid rgba(255,255,255,0.06)">
            <div class="flex-1 flex items-center justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Savve Logo"
                    class="h-12 w-auto object-contain">
            </div>
            <button onclick="closeSidebar()"
                class="lg:hidden w-7 h-7 flex items-center justify-center rounded-lg text-white flex-shrink-0 ml-2"
                style="background: rgba(255,255,255,0.1)">
                ✕
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <p class="text-xs font-bold px-3 mb-2 mt-1"
                style="color: rgb(255, 255, 255); letter-spacing: 0.1em; text-transform: uppercase; font-size: 9px">
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
                style="color: rgb(255, 255, 255); letter-spacing: 0.1em; text-transform: uppercase; font-size: 9px">
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
            <a href="{{ route('admin.rekap.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.rekap.*') ? 'active' : '' }}">
                <span class="w-5 text-center text-sm">📊</span> Rekap Event
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
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- Topbar --}}
        <header class="bg-white flex-shrink-0 flex items-center justify-between px-4 lg:px-6 py-3.5"
            style="border-bottom: 1px solid #e8edf5; box-shadow: 0 1px 8px rgba(0,0,0,0.04)">

            <div class="flex items-center gap-3 flex-1">
                {{-- Hamburger (mobile only) --}}
                <button onclick="openSidebar()"
                    class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl flex-shrink-0"
                    style="background: #f8faff; border: 1.5px solid #e8edf5">
                    <span class="text-gray-600">☰</span>
                </button>

                {{-- Search --}}
                <div class="flex-1 max-w-xs hidden sm:block">
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" style="font-size: 12px">🔍</span>
                        <input type="text" placeholder="Cari transaksi, penitip..."
                            class="w-full rounded-xl pl-9 pr-4 py-2.5 text-sm transition"
                            style="background: #f8faff; border: 1.5px solid #e8edf5; font-size: 13px; color: #374151"
                            onfocus="this.style.borderColor='#4a9eff'; this.style.boxShadow='0 0 0 3px rgba(74,158,255,0.1)'"
                            onblur="this.style.borderColor='#e8edf5'; this.style.boxShadow='none'">
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button class="relative w-9 h-9 flex items-center justify-center rounded-xl transition text-gray-500 flex-shrink-0"
                    style="background: #f8faff; border: 1.5px solid #e8edf5; font-size: 13px">
                    🔔
                    <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-red-500 rounded-full border border-white"></span>
                </button>
                <button class="hidden sm:flex w-9 h-9 items-center justify-center rounded-xl transition text-gray-500 flex-shrink-0"
                    style="background: #f8faff; border: 1.5px solid #e8edf5; font-size: 13px">
                    ⚙️
                </button>
                <div class="flex items-center gap-2 pl-2 lg:pl-3" style="border-left: 1.5px solid #e8edf5">
                    <div class="text-right hidden md:block">
                        <p class="text-xs font-bold leading-none" style="color: #0f2044">{{ auth()->user()->name }}</p>
                        <p class="text-xs mt-0.5" style="color: #4a9eff">Administrator</p>
                    </div>
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-sm text-white flex-shrink-0"
                        style="background: linear-gradient(135deg, #0f2044, #1e4d8c)">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-4 lg:p-6">
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

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebar-overlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close sidebar when clicking a nav link on mobile
    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 1024) {
                closeSidebar();
            }
        });
    });

    // Close on resize to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            closeSidebar();
        }
    });
</script>

</body>
</html>