@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- Greeting --}}
<div class="anim-fade-up delay-1 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #1a3a6b">
            {{ now()->format('l, d F Y') }}
        </p>
        <h1 class="text-xl lg:text-2xl font-black text-gray-900">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-gray-400 text-sm mt-1">Pantau aktivitas penitipan barang hari ini secara real-time.</p>
    </div>
    <div class="flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold self-start flex-shrink-0"
        style="background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0">
        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse inline-block"></span>
        Sistem Online
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">

    <div class="stat-card anim-fade-up delay-2 bg-white rounded-2xl p-4 lg:p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex justify-between items-start mb-3 lg:mb-4">
            <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl flex items-center justify-center"
                style="background: linear-gradient(135deg, #eff6ff, #dbeafe)">
                <span class="text-base lg:text-lg">📊</span>
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full"
                style="background: #f0fdf4; color: #15803d">+12%</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1" style="font-size: 9px">Transaksi Hari Ini</p>
        <p class="text-2xl lg:text-3xl font-black text-gray-900">{{ number_format($transaksiHariIni) }}</p>
        <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full" style="width: 65%; background: linear-gradient(90deg, #1a3a6b, #4a9eff)"></div>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-3 bg-white rounded-2xl p-4 lg:p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex justify-between items-start mb-3 lg:mb-4">
            <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl flex items-center justify-center"
                style="background: linear-gradient(135deg, #fff7ed, #fed7aa)">
                <span class="text-base lg:text-lg">🗃️</span>
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full"
                style="background: #fff7ed; color: #c2410c">Active</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1" style="font-size: 9px">Masih Dititipkan</p>
        <p class="text-2xl lg:text-3xl font-black text-gray-900">{{ number_format($belumDiambil) }}</p>
        <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full" style="width: 45%; background: linear-gradient(90deg, #ea580c, #fb923c)"></div>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-4 bg-white rounded-2xl p-4 lg:p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex justify-between items-start mb-3 lg:mb-4">
            <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl flex items-center justify-center"
                style="background: linear-gradient(135deg, #f0fdf4, #dcfce7)">
                <span class="text-base lg:text-lg">✅</span>
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full"
                style="background: #f0fdf4; color: #15803d">98%</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1" style="font-size: 9px">Sudah Diambil</p>
        <p class="text-2xl lg:text-3xl font-black text-gray-900">{{ number_format($sudahDiambil) }}</p>
        <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full" style="width: 80%; background: linear-gradient(90deg, #16a34a, #4ade80)"></div>
        </div>
    </div>

        <div class="stat-card anim-fade-up delay-5 rounded-2xl p-4 lg:p-5 relative overflow-hidden text-white"
                style="background: linear-gradient(135deg, #0f2044 0%, #1a3a6b 60%, #1e4d8c 100%); box-shadow: 0 8px 24px rgba(15,32,68,0.25)">
        <div class="flex justify-between items-start mb-3 lg:mb-4">
            <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl flex items-center justify-center"
                style="background: rgba(255,255,255,0.15)">
                <span class="text-base lg:text-lg">🎪</span>
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full"
                style="background: rgba(74,158,255,0.25); color: #93c5fd">Live</span>
        </div>
        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: rgba(255,255,255,0.6); font-size: 9px">Event Aktif</p>
        <p class="text-2xl lg:text-3xl font-black">{{ number_format($totalEventAktif) }}</p>
        <div class="mt-3 h-1 rounded-full overflow-hidden" style="background: rgba(255,255,255,0.15)">
            <div class="h-full rounded-full" style="width: 70%; background: #4a9eff"></div>
        </div>
        <div class="absolute -bottom-4 -right-4 w-20 h-20 rounded-full" style="background: rgba(255,255,255,0.05)"></div>
    </div>

</div>

{{-- Middle --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-4">

    {{-- Tren Mingguan --}}
    <div class="lg:col-span-2 anim-fade-up delay-6 bg-white rounded-2xl p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="font-black text-gray-800">Tren Mingguan</p>
                <p class="text-xs text-gray-400 mt-0.5">Volume transaksi 7 hari terakhir</p>
            </div>
            <a href="{{ route('admin.laporan.index') }}"
                class="text-xs font-semibold hover:underline transition flex-shrink-0" style="color: #1a3a6b">
                Laporan →
            </a>
        </div>

        @php
            $days   = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
            $counts = [];
            for ($i = 6; $i >= 0; $i--) {
                $counts[] = \App\Models\Transaksi::whereDate('created_at', now()->subDays($i))->count();
            }
            $max     = max($counts) ?: 1;
            $peakDay = array_search(max($counts), $counts);
        @endphp

        <div class="flex items-end gap-1.5 h-20 mb-2">
            @foreach($counts as $i => $count)
            <div class="flex-1 flex flex-col items-center gap-1 group">
                <div class="w-full rounded-t-lg transition-all duration-500 relative"
                    style="height: {{ max(4, ($count / $max) * 72) }}px;
                           background: {{ $i === $peakDay
                               ? 'linear-gradient(to top, #0f2044, #4a9eff)'
                               : 'linear-gradient(to top, #e2e8f0, #cbd5e1)' }}">
                </div>
            </div>
            @endforeach
        </div>
        <div class="flex gap-1.5">
            @foreach($days as $i => $day)
            <div class="flex-1 text-center" style="font-size: 9px; color: {{ $i === $peakDay ? '#1a3a6b' : '#94a3b8' }}; font-weight: {{ $i === $peakDay ? '700' : '400' }}">
                {{ $day }}
            </div>
            @endforeach
        </div>

        <div class="mt-4 rounded-xl px-4 py-3 flex justify-between items-center"
            style="background: linear-gradient(135deg, #eff6ff, #dbeafe)">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider" style="color: #3b82f6; font-size: 9px">Puncak Tertinggi</p>
                <p class="font-black text-gray-800 text-sm">{{ $days[$peakDay] }}, {{ max($counts) }} Transaksi</p>
            </div>
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                style="background: linear-gradient(135deg, #1a3a6b, #4a9eff)">↗</div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="lg:col-span-3 anim-fade-up delay-7 bg-white rounded-2xl border border-gray-100 overflow-hidden"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex justify-between items-center px-5 py-4 border-b border-gray-50">
            <div>
                <p class="font-black text-gray-800">Transaksi Terbaru</p>
                <p class="text-xs text-gray-400 mt-0.5">10 transaksi terakhir</p>
            </div>
            <a href="{{ route('admin.laporan.export') }}"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white transition hover:opacity-90 flex-shrink-0"
                style="background: linear-gradient(135deg, #0f2044, #1e4d8c)">
                ⬇ Export
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs" style="min-width: 400px">
                <thead>
                    <tr style="background: #f8faff">
                        <th class="px-4 py-3 text-left font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 10px">ID</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 10px">Penitip</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 10px">Barang</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap" style="font-size: 10px">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Transaksi::with(['event','details.kategori'])->latest()->take(8)->get() as $t)
                    <tr class="table-row border-t border-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <a href="{{ route('admin.transaksis.show', $t) }}"
                                class="font-bold hover:underline" style="color: #1a3a6b; font-family: monospace">
                                #{{ substr($t->nomor_transaksi, -4) }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                    style="background: linear-gradient(135deg, #1a3a6b, #4a9eff); font-size: 9px">
                                    {{ strtoupper(substr($t->nama_penitip, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-700 whitespace-nowrap">{{ Str::limit($t->nama_penitip, 10) }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @foreach($t->details->take(1) as $d)
                            <span class="px-2 py-0.5 rounded-md text-gray-600 font-medium whitespace-nowrap"
                                style="background: #f1f5f9; font-size: 10px">
                                {{ Str::limit($d->nama_barang_custom ?? $d->kategori->nama_kategori, 10) }}
                            </span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-full font-bold"
                                style="font-size: 9px;
                                       background: {{ $t->status === 'dititip' ? '#eff6ff' : '#f0fdf4' }};
                                       color: {{ $t->status === 'dititip' ? '#1d4ed8' : '#15803d' }}">
                                {{ $t->status === 'dititip' ? 'Dititip' : 'Diambil' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-300">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Bottom: Kapasitas --}}
<div class="anim-fade-up delay-8 bg-white rounded-2xl p-5 border border-gray-100"
    style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-5">
        <div>
            <p class="font-black text-gray-800">Distribusi Barang per Ukuran</p>
            <p class="text-xs text-gray-400 mt-0.5">Monitoring barang yang masih dititipkan saat ini</p>
        </div>
        <div class="flex items-center gap-4 text-xs text-gray-400 flex-shrink-0">
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-sm inline-block" style="background: linear-gradient(135deg, #1a3a6b, #4a9eff)"></span>
                Dititipkan
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-sm inline-block bg-gray-200"></span>
                Kapasitas
            </span>
        </div>
    </div>

    @php
        $ukurans  = ['S','M','L','XL'];
        $labels   = ['S — Loker Kecil','M — Area Sedang','L — Area Besar','XL — Area Koper'];
        $colors   = [
            ['#1a3a6b','#4a9eff'],
            ['#ea580c','#fb923c'],
            ['#15803d','#4ade80'],
            ['#7c3aed','#a78bfa'],
        ];
        $totalPerUkuran = [];
        foreach ($ukurans as $u) {
            $totalPerUkuran[$u] = \App\Models\DetailTransaksi::whereHas('transaksi', fn($q) => $q->where('status','dititip'))->where('ukuran', $u)->sum('jumlah');
        }
        $maxUkuran = max($totalPerUkuran) ?: 1;
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
        @foreach($ukurans as $i => $u)
        @php $pct = round(($totalPerUkuran[$u] / $maxUkuran) * 100) @endphp
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <p class="text-sm font-semibold text-gray-700">{{ $labels[$i] }}</p>
                <p class="text-sm font-black flex-shrink-0 ml-2" style="color: {{ $colors[$i][0] }}">
                    {{ $totalPerUkuran[$u] }} item
                </p>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700"
                    style="width: {{ $pct }}%;
                           background: linear-gradient(90deg, {{ $colors[$i][0] }}, {{ $colors[$i][1] }})">
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection