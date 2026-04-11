@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- Greeting --}}
<div class="mb-6">
    <h1 class="text-2xl font-black text-gray-800">Selamat Datang, Admin! 👋</h1>
    <p class="text-gray-400 text-sm mt-1">Pantau arus keluar-masuk barang di semua storage Savve hari ini. Sistem berjalan optimal.</p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-lg">📊</div>
            <span class="text-xs font-semibold text-green-500 bg-green-50 px-2 py-0.5 rounded-full">+12%</span>
        </div>
        <p class="text-xs text-gray-400 mb-1">Total Transaksi Hari Ini</p>
        <p class="text-3xl font-black text-gray-800">{{ number_format($transaksiHariIni) }}</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-lg">🗃️</div>
            <span class="text-xs font-semibold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Active</span>
        </div>
        <p class="text-xs text-gray-400 mb-1">Barang Masih Dititipkan</p>
        <p class="text-3xl font-black text-gray-800">{{ number_format($belumDiambil) }}</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-lg">✅</div>
            <span class="text-xs font-semibold text-green-500 bg-green-50 px-2 py-0.5 rounded-full">98%</span>
        </div>
        <p class="text-xs text-gray-400 mb-1">Barang Sudah Diambil</p>
        <p class="text-3xl font-black text-gray-800">{{ number_format($sudahDiambil) }}</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center text-lg">🎪</div>
            <span class="text-xs font-semibold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">Live</span>
        </div>
        <p class="text-xs text-gray-400 mb-1">Event Aktif</p>
        <p class="text-3xl font-black text-gray-800">{{ number_format($totalEventAktif) }}</p>
    </div>
</div>

{{-- Middle Section --}}
<div class="grid grid-cols-2 gap-4 mb-6">

    {{-- Tren Mingguan --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-start mb-1">
            <div>
                <p class="font-bold text-gray-800">Tren Mingguan</p>
                <p class="text-xs text-gray-400">Volume transaksi 7 hari terakhir</p>
            </div>
            <a href="{{ route('admin.laporan.index') }}" class="text-xs text-indigo-600 font-semibold hover:underline">
                Laporan →
            </a>
        </div>

        {{-- Bar Chart Sederhana --}}
        <div class="mt-4">
            @php
                $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                $counts = [];
                for ($i = 6; $i >= 0; $i--) {
                    $counts[] = \App\Models\Transaksi::whereDate('created_at', now()->subDays($i))->count();
                }
                $max = max($counts) ?: 1;
                $peakDay = array_search(max($counts), $counts);
            @endphp

            <div class="flex items-end gap-2 h-24">
                @foreach($counts as $i => $count)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full rounded-t-md transition-all"
                        style="height: {{ ($count / $max) * 80 }}px;
                               background: {{ $i === $peakDay ? 'linear-gradient(to top, #4f46e5, #818cf8)' : '#e0e7ff' }}">
                    </div>
                </div>
                @endforeach
            </div>
            <div class="flex gap-2 mt-1">
                @foreach($days as $day)
                <div class="flex-1 text-center text-xs text-gray-400">{{ $day }}</div>
                @endforeach
            </div>
        </div>

        <div class="mt-4 bg-indigo-50 rounded-xl px-4 py-3 flex justify-between items-center">
            <div>
                <p class="text-xs text-indigo-400 font-semibold uppercase tracking-wider">Puncak Tertinggi</p>
                <p class="font-black text-gray-800">{{ $days[$peakDay] }}, {{ max($counts) }} Unit</p>
            </div>
            <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm">↗</div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <p class="font-bold text-gray-800">Recent Transactions</p>
            <a href="{{ route('admin.laporan.export') }}"
                class="text-xs bg-indigo-600 text-white px-3 py-1.5 rounded-lg font-semibold hover:bg-indigo-700">
                Export CSV
            </a>
        </div>

        <table class="w-full text-xs">
            <thead>
                <tr class="text-gray-400 border-b border-gray-100">
                    <th class="pb-2 text-left font-semibold">ID & CUSTOMER</th>
                    <th class="pb-2 text-left font-semibold">TIPE BARANG</th>
                    <th class="pb-2 text-left font-semibold">EVENT</th>
                    <th class="pb-2 text-left font-semibold">STATUS</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse(\App\Models\Transaksi::with(['event','details.kategori'])->latest()->take(4)->get() as $t)
                <tr class="hover:bg-gray-50">
                    <td class="py-2.5">
                        <p class="font-bold text-gray-700">#{{ substr($t->nomor_transaksi, -4) }}</p>
                        <p class="text-gray-400">{{ $t->nama_penitip }}</p>
                    </td>
                    <td class="py-2.5">
                        @foreach($t->details->take(2) as $d)
                        <span class="inline-block bg-gray-100 text-gray-600 text-xs px-1.5 py-0.5 rounded mr-0.5 mb-0.5">
                            {{ $d->nama_barang_custom ?? $d->kategori->nama_kategori }}
                        </span>
                        @endforeach
                    </td>
                    <td class="py-2.5 text-gray-500">
                        {{ Str::limit($t->event->nama_event, 12) }}
                    </td>
                    <td class="py-2.5">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $t->status === 'dititip' ? 'bg-blue-50 text-blue-600' : 'bg-gray-100 text-gray-500' }}">
                            {{ $t->status === 'dititip' ? 'Dititipkan' : 'Selesai' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-6 text-center text-gray-300">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Kapasitas per Ukuran --}}
<div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
    <div class="flex justify-between items-start mb-4">
        <div>
            <p class="font-bold text-gray-800">Kapasitas per Ukuran Barang</p>
            <p class="text-xs text-gray-400">Monitoring distribusi barang yang masih dititipkan</p>
        </div>
        <div class="flex items-center gap-3 text-xs text-gray-400">
            <span class="flex items-center gap-1"><span class="w-2 h-2 bg-indigo-600 rounded-full inline-block"></span> Terpakai</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 bg-gray-200 rounded-full inline-block"></span> Kosong</span>
        </div>
    </div>

    @php
        $ukurans = ['S', 'M', 'L', 'XL'];
        $labels = ['S - Loker Kecil', 'M - Area Sedang', 'L - Area Besar', 'XL - Area Koper'];
        $totalPerUkuran = [];
        foreach ($ukurans as $u) {
            $totalPerUkuran[$u] = \App\Models\DetailTransaksi::whereHas('transaksi', function($q) {
                $q->where('status', 'dititip');
            })->where('ukuran', $u)->sum('jumlah');
        }
        $maxUkuran = max($totalPerUkuran) ?: 1;
    @endphp

    <div class="space-y-4">
        @foreach($ukurans as $i => $u)
        @php $pct = round(($totalPerUkuran[$u] / $maxUkuran) * 100) @endphp
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <p class="text-sm font-semibold text-gray-700">{{ $labels[$i] }}</p>
                <p class="text-sm font-bold {{ $pct > 70 ? 'text-red-500' : ($pct > 40 ? 'text-yellow-500' : 'text-indigo-600') }}">
                    {{ $totalPerUkuran[$u] }} item
                </p>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2.5">
                <div class="h-2.5 rounded-full transition-all"
                    style="width: {{ $pct }}%;
                           background: linear-gradient(to right,
                           {{ $pct > 70 ? '#ef4444, #f87171' : ($pct > 40 ? '#f59e0b, #fbbf24' : '#4f46e5, #818cf8') }})">
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection