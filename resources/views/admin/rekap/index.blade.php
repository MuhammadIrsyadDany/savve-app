@extends('layouts.admin')
@section('title', 'Rekap Event')

@section('content')

{{-- Header --}}
<div class="anim-fade-up delay-1 mb-6">
    <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #1a3a6b">Laporan</p>
    <h1 class="text-xl lg:text-2xl font-black text-gray-900">Rekap per Event</h1>
    <p class="text-gray-400 text-sm mt-1">Ringkasan performa seluruh event penitipan barang.</p>
</div>

{{-- Summary Cards --}}
<div class="anim-fade-up delay-2 grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: linear-gradient(135deg, #eff6ff, #dbeafe)">
                <span class="text-base">🎪</span>
            </div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Event</p>
        </div>
        <p class="text-3xl font-black text-gray-900">{{ $events->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: linear-gradient(135deg, #f0fdf4, #dcfce7)">
                <span class="text-base">💰</span>
            </div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pendapatan</p>
        </div>
        <p class="text-2xl font-black" style="color: #0f2044">
            Rp {{ number_format($totalSemuaPendapatan, 0, ',', '.') }}
        </p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: linear-gradient(135deg, #fff7ed, #fed7aa)">
                <span class="text-base">📋</span>
            </div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Transaksi</p>
        </div>
        <p class="text-3xl font-black text-gray-900">{{ number_format($totalSemuaEvent) }}</p>
    </div>
</div>

{{-- Tabel Event --}}
<div class="anim-fade-up delay-3 bg-white rounded-2xl border border-gray-100 overflow-hidden"
    style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
    <div class="px-5 py-4 border-b border-gray-100">
        <p class="font-black text-gray-800">Daftar Event</p>
        <p class="text-xs text-gray-400 mt-0.5">Klik event untuk melihat rekap detail</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" style="min-width: 600px">
            <thead>
                <tr style="background: #f8faff; border-bottom: 2px solid #e2e8f0">
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Event</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Tanggal</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Status</th>
                    <th class="px-5 py-4 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Transaksi</th>
                    <th class="px-5 py-4 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Total Barang</th>
                    <th class="px-5 py-4 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Pendapatan</th>
                    <th class="px-5 py-4 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Selesai</th>
                    <th class="px-5 py-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                <tr class="table-row" style="border-top: 1px solid #f1f5f9">
                    <td class="px-5 py-4">
                        <p class="font-bold text-gray-800">{{ $event->nama_event }}</p>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <p class="text-xs text-gray-600">{{ $event->tanggal_mulai->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">s/d {{ $event->tanggal_selesai->format('d M Y') }}</p>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-bold"
                            style="background: {{ $event->status === 'aktif' ? '#f0fdf4' : '#f8faff' }};
                                   color: {{ $event->status === 'aktif' ? '#15803d' : '#94a3b8' }}">
                            {{ $event->status === 'aktif' ? '● Aktif' : '● Selesai' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right whitespace-nowrap">
                        <span class="font-black text-gray-800">{{ $event->total_transaksi }}</span>
                    </td>
                    <td class="px-5 py-4 text-right whitespace-nowrap">
                        <span class="font-black text-gray-800">{{ $event->total_barang }}</span>
                        <span class="text-xs text-gray-400 ml-1">unit</span>
                    </td>
                    <td class="px-5 py-4 text-right whitespace-nowrap">
                        <span class="font-black" style="color: #0f2044">
                            Rp {{ number_format($event->total_pendapatan, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right whitespace-nowrap">
                        @if($event->total_transaksi > 0)
                        <span class="text-sm font-bold" style="color: #15803d">
                            {{ round(($event->total_diambil / $event->total_transaksi) * 100) }}%
                        </span>
                        @else
                        <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.rekap.show', $event) }}"
                            class="flex items-center gap-1 text-xs font-bold hover:underline transition"
                            style="color: #1a3a6b">
                            Detail →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl"
                                style="background: #f8faff">🎪</div>
                            <p class="font-semibold text-gray-400">Belum ada event.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection