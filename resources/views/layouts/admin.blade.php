<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Savve - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-56 bg-white border-r border-gray-100 flex flex-col flex-shrink-0">

        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-gray-100">
            <p class="text-lg font-black text-indigo-700 leading-none">Vendor Savve</p>
            <p class="text-xs text-gray-400 font-semibold tracking-widest uppercase mt-0.5">Storage Management</p>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                <span>⊞</span> Dashboard
            </a>
            <a href="{{ route('admin.events.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('admin.events.*') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                <span>📅</span> Kelola Event
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                <span>👤</span> Kelola Kasir
            </a>
            <a href="{{ route('admin.transaksis.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('admin.transaksis.*') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                <span>📋</span> Data Transaksi
            </a>
            <a href="{{ route('admin.laporan.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('admin.laporan.*') ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                <span>📈</span> Laporan Harian
            </a>
        </nav>

        {{-- User Info + Logout --}}
        <div class="px-4 py-4 border-t border-gray-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700 leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Warehouse Head</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 text-sm text-red-500 hover:text-red-700 font-medium transition">
                    🚪 Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-100 px-6 py-3 flex items-center justify-between flex-shrink-0">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">🔍</span>
                    <input type="text" placeholder="Cari transaksi atau barang..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button class="text-gray-400 hover:text-gray-600 text-lg">🔔</button>
                <button class="text-gray-400 hover:text-gray-600 text-lg">⚙️</button>
                <div class="flex items-center gap-2 pl-4 border-l border-gray-100">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-700 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400">Admin Utama</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>