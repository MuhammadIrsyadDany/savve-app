@extends('layouts.admin')
@section('title', 'Data Transaksi')

@section('content')

<div class="anim-fade-up delay-1 mb-6">
    <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #1a3a6b">Laporan</p>
    <h1 class="text-xl lg:text-2xl font-black text-gray-900">Data Transaksi</h1>
    <p class="text-gray-400 text-sm mt-1">Monitor seluruh transaksi penitipan barang dari semua kasir.</p>
</div>

{{-- Filter --}}
<div class="anim-fade-up delay-2 bg-white rounded-2xl border border-gray-100 p-4 mb-5"
    style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
    <form method="GET" action="{{ route('admin.transaksis.index') }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">Cari Penitip / No. Transaksi</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" style="font-size: 12px">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full rounded-xl pl-9 pr-4 py-2.5 text-sm transition"
                        style="background: #f8faff; border: 1.5px solid #e2e8f0; color: #374151"
                        placeholder="Nama atau nomor transaksi..."
                        onfocus="this.style.borderColor='#4a9eff'" onblur="this.style.borderColor='#e2e8f0'">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">Event</label>
                <select name="event_id"
                    class="w-full rounded-xl px-3 py-2.5 text-sm transition"
                    style="background: #f8faff; border: 1.5px solid #e2e8f0; color: #374151">
                    <option value="">Semua Event</option>
                    @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                        {{ $event->nama_event }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">Status</label>
                <select name="status"
                    class="w-full rounded-xl px-3 py-2.5 text-sm transition"
                    style="background: #f8faff; border: 1.5px solid #e2e8f0; color: #374151">
                    <option value="">Semua Status</option>
                    <option value="dititip" {{ request('status') === 'dititip' ? 'selected' : '' }}>Dititipkan</option>
                    <option value="sudah_diambil" {{ request('status') === 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">Tanggal</label>
                <div class="flex gap-2">
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                        class="flex-1 rounded-xl px-3 py-2.5 text-sm transition"
                        style="background: #f8faff; border: 1.5px solid #e2e8f0; color: #374151">
                    <button type="submit"
                        class="px-4 py-2.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90 flex-shrink-0"
                        style="background: linear-gradient(135deg, #0f2044, #1e4d8c)">
                        🔍
                    </button>
                    <a href="{{ route('admin.transaksis.index') }}"
                        class="px-3 py-2.5 rounded-xl font-semibold text-sm flex-shrink-0"
                        style="background: #f1f5f9; color: #64748b">
                        🔄
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="anim-fade-up delay-3 bg-white rounded-2xl border border-gray-100 overflow-hidden"
    style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
    <div class="overflow-x-auto">
        <table class="w-full text-sm" style="min-width: 700px">
            <thead>
                <tr style="background: #f8faff; border-bottom: 2px solid #e2e8f0">
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">No. Transaksi</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Penitip</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Barang</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Event</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Kasir</th>
                    <th class="px-5 py-4 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Total</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Status</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Waktu</th>
                    <th class="px-5 py-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr class="table-row" style="border-top: 1px solid #f1f5f9">
                    <td class="px-5 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.transaksis.show', $t) }}"
                            class="font-bold hover:underline" style="color: #1a3a6b; font-family: monospace; font-size: 12px">
                            {{ $t->nomor_transaksi }}
                        </a>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                style="background: linear-gradient(135deg, #0f2044, #4a9eff); font-size: 11px">
                                {{ strtoupper(substr($t->nama_penitip, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm whitespace-nowrap">{{ $t->nama_penitip }}</p>
                                <p class="text-gray-400" style="font-size: 11px">{{ $t->no_whatsapp }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        @foreach($t->details->take(1) as $d)
                        <p class="font-medium text-gray-700 text-sm whitespace-nowrap">{{ Str::limit($d->nama_barang_custom ?? $d->kategori->nama_kategori, 15) }}</p>
                        <span class="inline-block px-2 py-0.5 rounded-md text-xs font-bold mt-0.5"
                            style="background: #eff6ff; color: #1d4ed8">
                            Ukuran {{ $d->ukuran }}
                        </span>
                        @endforeach
                        @if($t->details->count() > 1)
                        <p class="text-xs mt-0.5" style="color: #1a3a6b">+{{ $t->details->count() - 1 }} lainnya</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-gray-500 text-xs whitespace-nowrap">{{ Str::limit($t->event->nama_event, 15) }}</td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                style="background: #1a3a6b; font-size: 9px">
                                {{ strtoupper(substr($t->kasir->name, 0, 1)) }}
                            </div>
                            <span class="text-xs text-gray-500 whitespace-nowrap">{{ Str::limit($t->kasir->name, 10) }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-right whitespace-nowrap">
                        <span class="font-black text-gray-900 text-sm">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-bold"
                            style="background: {{ $t->status === 'dititip' ? '#eff6ff' : '#f0fdf4' }};
                                   color: {{ $t->status === 'dititip' ? '#1d4ed8' : '#15803d' }}">
                            {{ $t->status === 'dititip' ? 'DITITIPKAN' : 'DIAMBIL' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap text-gray-400 text-xs">
                        {{ $t->waktu_penitipan->format('d M Y') }}<br>
                        {{ $t->waktu_penitipan->format('H:i') }} WIB
                    </td>
                    <td class="px-5 py-4">
                        <a href="{{ route('admin.transaksis.show', $t) }}"
                            class="text-gray-300 hover:text-indigo-500 text-lg transition">⋯</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl"
                                style="background: #f8faff">📋</div>
                            <p class="font-semibold text-gray-400">Belum ada transaksi.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transaksis->hasPages())
    <div class="px-5 py-4 flex flex-col sm:flex-row items-center justify-between gap-3"
        style="border-top: 1px solid #f1f5f9">
        <p class="text-xs text-gray-400">
            Menampilkan {{ $transaksis->firstItem() }}–{{ $transaksis->lastItem() }}
            dari {{ $transaksis->total() }} transaksi
        </p>
        <div class="flex items-center gap-1">
            @if($transaksis->onFirstPage())
            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 text-sm"
                style="background: #f8faff; border: 1.5px solid #e2e8f0">‹</span>
            @else
            <a href="{{ $transaksis->previousPageUrl() }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 text-sm transition"
                style="background: #f8faff; border: 1.5px solid #e2e8f0">‹</a>
            @endif
            @foreach($transaksis->getUrlRange(1, $transaksis->lastPage()) as $page => $url)
                @if($page == $transaksis->currentPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-lg text-white text-sm font-bold"
                    style="background: linear-gradient(135deg, #0f2044, #1e4d8c)">{{ $page }}</span>
                @elseif(abs($page - $transaksis->currentPage()) <= 2)
                <a href="{{ $url }}"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 text-sm transition"
                    style="background: #f8faff; border: 1.5px solid #e2e8f0">{{ $page }}</a>
                @elseif($page == 1 || $page == $transaksis->lastPage())
                <a href="{{ $url }}"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 text-sm"
                    style="background: #f8faff; border: 1.5px solid #e2e8f0">{{ $page }}</a>
                @elseif(abs($page - $transaksis->currentPage()) == 3)
                <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-sm">…</span>
                @endif
            @endforeach
            @if($transaksis->hasMorePages())
            <a href="{{ $transaksis->nextPageUrl() }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 text-sm transition"
                style="background: #f8faff; border: 1.5px solid #e2e8f0">›</a>
            @else
            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 text-sm"
                style="background: #f8faff; border: 1.5px solid #e2e8f0">›</span>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection