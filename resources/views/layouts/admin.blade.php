<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savve Admin - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-blue-800 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-blue-700">
                🎒 Savve
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.events.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-700 {{ request()->routeIs('admin.events.*') ? 'bg-blue-700' : '' }}">
                    🎪 Kelola Event
                </a>
                {{-- <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-700 {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : '' }}">
                    👤 Kelola Kasir
                </a>
                <a href="{{ route('admin.transaksis.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-700 {{ request()->routeIs('admin.transaksis.*') ? 'bg-blue-700' : '' }}">
                    📋 Data Transaksi
                </a>
                <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-blue-700 {{ request()->routeIs('admin.laporan.*') ? 'bg-blue-700' : '' }}">
                    📈 Laporan
                </a> --}}
            </nav>
            <div class="p-4 border-t border-blue-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded-lg hover:bg-blue-700">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Header --}}
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-700">@yield('title')</h1>
                <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
            </header>

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto p-6">
                {{-- Alert Success --}}
                @if(session('success'))
                    <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Alert Error --}}
                @if(session('error'))
                    <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>