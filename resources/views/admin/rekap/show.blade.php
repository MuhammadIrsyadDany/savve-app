@extends('layouts.admin')
@section('title', 'Rekap ' . $event->nama_event)

@section('content')

{{-- Header --}}
<div class="anim-fade-up delay-1 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #1a3a6b">Rekap Event</p>
        <h1 class="text-xl lg:text-2xl font-black text-gray-900">{{ $event->nama_event }}</h1>
        <p class="text-gray-400 text-sm mt-1">
            {{ $event->tanggal_mulai->format('d M Y') }} — {{ $event->tanggal_selesai->format('d M Y') }}
        </p>
    </div>
    <div class="flex gap-3 self-start flex-shrink-0">
        <a href="{{ route('admin.laporan.index', ['event_id' => $event->id]) }}"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
            style="background: linear-gradient(135deg, #0f2044, #1e4d8c)">
            📈 Lihat Laporan
        </a>
        <a href="{{ route('admin.rekap.index') }}"
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold transition"
            style="background: white; border: 1.5px solid #e2e8f0; color: #374151">
            ← Kembali
        </a>
    </div>
</div>

{{-- Stat Cards --}}
<div class="anim-fade-up delay-2 grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
    <div class="bg-white rounded-2xl p-4 lg:p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1" style="font-size: 9px">Total Transaksi</p>
        <p class="text-2xl lg:text-3xl font-black text-gray-900">{{ $totalTransaksi }}</p>
        <div class="mt-2 h-1 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full" style="width: 100%; background: linear-gradient(90deg, #1a3a6b, #4a9eff)"></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 lg:p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1" style="font-size: 9px">Total Barang</p>
        <p class="text-2xl lg:text-3xl font-black text-gray-900">{{ $totalBarang }}</p>
        <p class="text-xs text-gray-400 mt-1">unit dititipkan</p>
    </div>
    <div class="bg-white rounded-2xl p-4 lg:p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1" style="font-size: 9px">Masih Dititip</p>
        <p class="text-2xl lg:text-3xl font-black text-gray-900">{{ $totalDititip }}</p>
        <div class="mt-2 h-1 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full" style="width: {{ $totalTransaksi > 0 ? round(($totalDititip/$totalTransaksi)*100) : 0 }}%; background: linear-gradient(90deg, #ea580c, #fb923c)"></div>
        </div>
    </div>
    <div class="rounded-2xl p-4 lg:p-5 text-white relative overflow-hidden"
        style="background: linear-gradient(135deg, #0f2044, #1a3a6b); box-shadow: 0 8px 24px rgba(15,32,68,0.2)">
        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: rgba(255,255,255,0.6); font-size: 9px">Total Pendapatan</p>
        <p class="text-lg lg:text-xl font-black leading-tight">
            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </p>
        <p class="text-xs mt-1" style="color: #93c5fd">{{ $totalDiambil }} transaksi selesai</p>
        <div class="absolute -bottom-3 -right-3 w-16 h-16 rounded-full" style="background: rgba(255,255,255,0.05)"></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

    {{-- Rekap per Ukuran --}}
    <div class="anim-fade-up delay-3 bg-white rounded-2xl border border-gray-100 overflow-hidden"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="font-black text-gray-800">Rekap per Ukuran Barang</p>
            <p class="text-xs text-gray-400 mt-0.5">Distribusi barang berdasarkan ukuran S/M/L/XL</p>
        </div>
        <div class="p-5 space-y-4">
            @php
                $ukuranColors = ['S'=>['#1a3a6b','#4a9eff'], 'M'=>['#ea580c','#fb923c'], 'L'=>['#15803d','#4ade80'], 'XL'=>['#7c3aed','#a78bfa']];
                $maxJumlah = max(array_column($rekapUkuran, 'jumlah')) ?: 1;
            @endphp
            @foreach($rekapUkuran as $ukuran => $data)
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded-lg text-xs font-black text-white"
                            style="background: {{ $ukuranColors[$ukuran][0] }}">{{ $ukuran }}</span>
                        <span class="text-sm font-semibold text-gray-700">{{ $data['jumlah'] }} unit</span>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold" style="color: {{ $ukuranColors[$ukuran][0] }}">
                            Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-400">Tarif: Rp {{ number_format($data['tarif'], 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700"
                        style="width: {{ round(($data['jumlah'] / $maxJumlah) * 100) }}%;
                               background: linear-gradient(90deg, {{ $ukuranColors[$ukuran][0] }}, {{ $ukuranColors[$ukuran][1] }})">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Rekap per Kasir --}}
    <div class="anim-fade-up delay-4 bg-white rounded-2xl border border-gray-100 overflow-hidden"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="px-5 py-4 border-b border-gray-100">
            <p class="font-black text-gray-800">Performa Kasir</p>
            <p class="text-xs text-gray-400 mt-0.5">Kontribusi setiap kasir di event ini</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background: #f8faff; border-bottom: 1px solid #e2e8f0">
                        <th class="px-5 py-3 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Kasir</th>
                        <th class="px-5 py-3 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Transaksi</th>
                        <th class="px-5 py-3 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Selesai</th>
                        <th class="px-5 py-3 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapKasir as $kasir)
                    <tr class="table-row" style="border-top: 1px solid #f1f5f9">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                    style="background: linear-gradient(135deg, #0f2044, #4a9eff); font-size: 10px">
                                    {{ strtoupper(substr($kasir['nama'], 0, 1)) }}
                                </div>
                                <span class="font-semibold text-gray-700 text-sm">{{ $kasir['nama'] }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-right font-black text-gray-800">{{ $kasir['total_transaksi'] }}</td>
                        <td class="px-5 py-3 text-right">
                            <span class="font-bold text-sm" style="color: #15803d">{{ $kasir['total_diambil'] }}</span>
                            <span class="text-xs text-gray-400"> / {{ $kasir['total_transaksi'] }}</span>
                        </td>
                        <td class="px-5 py-3 text-right font-black whitespace-nowrap" style="color: #0f2044; font-size: 12px">
                            Rp {{ number_format($kasir['total_pendapatan'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-300 text-sm">Belum ada data kasir.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Rekap per Kategori Barang --}}
<div class="anim-fade-up delay-5 bg-white rounded-2xl border border-gray-100 overflow-hidden"
    style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
    <div class="px-5 py-4 border-b border-gray-100">
        <p class="font-black text-gray-800">Rekap per Kategori Barang</p>
        <p class="text-xs text-gray-400 mt-0.5">Jenis barang yang paling banyak dititipkan</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" style="min-width: 400px">
            <thead>
                <tr style="background: #f8faff; border-bottom: 2px solid #e2e8f0">
                    <th class="px-5 py-3 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Kategori Barang</th>
                    <th class="px-5 py-3 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Jumlah Unit</th>
                    <th class="px-5 py-3 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Total Pendapatan</th>
                    <th class="px-5 py-3 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Proporsi</th>
                </tr>
            </thead>
            <tbody>
                @php $maxBarang = max(array_column($rekapKategori, 'jumlah')) ?: 1; @endphp
                @forelse($rekapKategori as $nama => $data)
                <tr class="table-row" style="border-top: 1px solid #f1f5f9">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full flex-shrink-0"
                                style="background: #1a3a6b"></div>
                            <span class="font-semibold text-gray-700">{{ $nama }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-right font-black text-gray-800">
                        {{ $data['jumlah'] }} <span class="text-xs font-normal text-gray-400">unit</span>
                    </td>
                    <td class="px-5 py-3 text-right font-bold whitespace-nowrap" style="color: #0f2044">
                        Rp {{ number_format($data['pendapatan'], 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-100 rounded-full h-1.5 overflow-hidden" style="min-width: 60px">
                                <div class="h-full rounded-full"
                                    style="width: {{ round(($data['jumlah'] / $maxBarang) * 100) }}%;
                                           background: linear-gradient(90deg, #1a3a6b, #4a9eff)"></div>
                            </div>
                            <span class="text-xs text-gray-400 flex-shrink-0">
                                {{ round(($data['jumlah'] / $totalBarang) * 100) }}%
                            </span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-5 py-8 text-center text-gray-300">Belum ada data barang.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection