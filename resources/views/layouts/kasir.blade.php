<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savve Kasir - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-green-800 text-white flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-green-700">
                🎒 Savve
            </div>
            {{-- <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('kasir.dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-700 {{ request()->routeIs('kasir.dashboard') ? 'bg-green-700' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('kasir.transaksi.create') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-700 {{ request()->routeIs('kasir.transaksi.create') ? 'bg-green-700' : '' }}">
                    ➕ Titip Barang
                </a>
                <a href="{{ route('kasir.pengambilan.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-700 {{ request()->routeIs('kasir.pengambilan.*') ? 'bg-green-700' : '' }}">
                    📦 Ambil Barang
                </a>
                <a href="{{ route('kasir.transaksi.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-green-700 {{ request()->routeIs('kasir.transaksi.index') ? 'bg-green-700' : '' }}">
                    📋 Riwayat Transaksi
                </a>
            </nav> --}}
            <div class="p-4 border-t border-green-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded-lg hover:bg-green-700">
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
                @if(session('success'))
                    <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

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