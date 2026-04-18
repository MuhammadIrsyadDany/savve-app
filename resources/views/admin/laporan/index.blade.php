@extends('layouts.admin')
@section('title', 'Laporan Harian')

@section('content')

{{-- Header --}}
<div class="anim-fade-up delay-1 flex justify-between items-start mb-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #1a3a6b">Laporan</p>
        <h1 class="text-2xl font-black text-gray-900">Laporan Harian</h1>
        <p class="text-gray-400 text-sm mt-1">Pantau aktivitas penitipan barang masuk dan keluar secara real-time.</p>
    </div>
    <a href="{{ route('admin.laporan.export', request()->query()) }}"
        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90 flex-shrink-0"
        style="background: linear-gradient(135deg, #0f2044, #1e4d8c); box-shadow: 0 4px 12px rgba(15,32,68,0.2)">
        ⬇ Export Excel
    </a>
</div>

{{-- Filter --}}
<div class="anim-fade-up delay-2 bg-white rounded-2xl border border-gray-100 p-5 mb-5"
    style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
    <form method="GET" action="{{ route('admin.laporan.index') }}">
        <input type="hidden" name="show" value="1">
        <div class="flex flex-wrap gap-4 items-end">

            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Pilih Event</label>
                <select name="event_id" id="event_id"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                    <option value="">Semua Event</option>
                    @foreach($events as $event)
                    <option value="{{ $event->id }}"
                        data-mulai="{{ $event->tanggal_mulai->format('Y-m-d') }}"
                        data-selesai="{{ $event->tanggal_selesai->format('Y-m-d') }}"
                        {{ request('event_id') == $event->id ? 'selected' : '' }}>
                        {{ $event->nama_event }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
            </div>

            <div class="flex-1 min-w-[140px]">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                <select name="status"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition">
                    <option value="">Semua Status</option>
                    <option value="dititip" {{ request('status') === 'dititip' ? 'selected' : '' }}>Dititipkan</option>
                    <option value="sudah_diambil" {{ request('status') === 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
                </select>
            </div>

            <div class="flex gap-2 flex-shrink-0">
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #0f2044, #1e4d8c)">
                    Tampilkan
                </button>
                <a href="{{ route('admin.laporan.index') }}"
                    class="px-3.5 py-2.5 bg-gray-100 text-gray-500 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
                    🔄
                </a>
            </div>

        </div>
    </form>
</div>

{{-- Summary Cards --}}
@if(($transaksis instanceof \Illuminate\Pagination\LengthAwarePaginator ? $transaksis->total() : $transaksis->count()) > 0)
<div class="anim-fade-up delay-3 grid grid-cols-3 gap-4 mb-5">
    <div class="bg-white rounded-2xl p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: linear-gradient(135deg, #eff6ff, #dbeafe)">
                <span class="text-base">📋</span>
            </div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Transaksi</p>
        </div>
        <p class="text-3xl font-black text-gray-900">{{ $transaksis instanceof \Illuminate\Pagination\LengthAwarePaginator ? $transaksis->total() : $transaksis->count() }} transaksi</p>
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
            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: linear-gradient(135deg, #fff7ed, #fed7aa)">
                <span class="text-base">📦</span>
            </div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Dititip / Diambil</p>
        </div>
        <p class="text-2xl font-black text-gray-900">
            <span style="color: #ea580c">{{ $totalDititip }}</span>
            <span class="text-gray-300 mx-1">/</span>
            <span style="color: #15803d">{{ $totalDiambil }}</span>
        </p>
    </div>
</div>
@endif

{{-- Tabel --}}
<div class="anim-fade-up delay-4 bg-white rounded-2xl border border-gray-100 overflow-hidden"
    style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background: #f8faff; border-bottom: 2px solid #e2e8f0">
                    <th class="px-5 py-4 text-left whitespace-nowrap"
                        style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em">
                        No. Transaksi
                    </th>
                    <th class="px-5 py-4 text-left whitespace-nowrap"
                        style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em">
                        Penitip
                    </th>
                    <th class="px-5 py-4 text-left whitespace-nowrap"
                        style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em">
                        Barang & Ukuran
                    </th>
                    <th class="px-5 py-4 text-left whitespace-nowrap"
                        style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em">
                        Kasir
                    </th>
                    <th class="px-5 py-4 text-right whitespace-nowrap"
                        style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em">
                        Total
                    </th>
                    <th class="px-5 py-4 text-left whitespace-nowrap"
                        style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em">
                        Status
                    </th>
                    <th class="px-5 py-4 text-left whitespace-nowrap"
                        style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em">
                        Waktu
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr class="table-row border-t border-gray-50">
                    <td class="px-5 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.transaksis.show', $t) }}"
                            class="font-bold hover:underline transition"
                            style="color: #1a3a6b; font-family: monospace; font-size: 12px">
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
                        <p class="font-medium text-gray-700 text-sm">
                            {{ $d->nama_barang_custom ?? $d->kategori->nama_kategori }}
                        </p>
                        <span class="inline-block px-2 py-0.5 rounded-md text-xs font-bold mt-0.5"
                            style="background: #eff6ff; color: #1d4ed8">
                            Ukuran {{ $d->ukuran }}
                        </span>
                        @endforeach
                        @if($t->details->count() > 1)
                        <p class="text-xs mt-0.5" style="color: #1a3a6b">
                            +{{ $t->details->count() - 1 }} barang lain
                        </p>
                        @endif
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                style="background: #1a3a6b; font-size: 9px">
                                {{ strtoupper(substr($t->kasir->name, 0, 1)) }}
                            </div>
                            <span class="text-gray-500 text-xs">{{ $t->kasir->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-right whitespace-nowrap">
                        <span class="font-black text-gray-900 text-sm">
                            Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                        </span>
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
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl"
                                style="background: #f8faff">📋</div>
                            <p class="font-semibold text-gray-400">
                                @if(request()->has('show'))
                                    Tidak ada data yang sesuai filter.
                                @else
                                    Pilih filter di atas untuk menampilkan laporan.
                                @endif
                            </p>
                            <p class="text-xs text-gray-300">Pilih event atau tanggal untuk memulai</p>
                        </div>
                    </td>
                </tr>
                @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    @if($transaksis instanceof \Illuminate\Pagination\LengthAwarePaginator && $transaksis->hasPages())
    <div class="px-5 py-4 flex items-center justify-between"
        style="border-top: 1px solid #f1f5f9">
        <p class="text-xs text-gray-400">
            Menampilkan {{ $transaksis->firstItem() }}–{{ $transaksis->lastItem() }}
            dari {{ $transaksis->total() }} transaksi
        </p>
        <div class="flex items-center gap-1">
            {{-- Prev --}}
            @if($transaksis->onFirstPage())
            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 text-sm cursor-not-allowed"
                style="background: #f8faff; border: 1.5px solid #e2e8f0">‹</span>
            @else
            <a href="{{ $transaksis->previousPageUrl() }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 text-sm transition hover:text-white"
                style="background: #f8faff; border: 1.5px solid #e2e8f0"
                onmouseover="this.style.background='#1a3a6b'; this.style.borderColor='#1a3a6b'"
                onmouseout="this.style.background='#f8faff'; this.style.borderColor='#e2e8f0'">‹</a>
            @endif

            {{-- Page Numbers --}}
            @foreach($transaksis->getUrlRange(1, $transaksis->lastPage()) as $page => $url)
                @if($page == $transaksis->currentPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-lg text-white text-sm font-bold"
                    style="background: linear-gradient(135deg, #0f2044, #1e4d8c)">{{ $page }}</span>
                @elseif(abs($page - $transaksis->currentPage()) <= 2)
                <a href="{{ $url }}"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 text-sm transition"
                    style="background: #f8faff; border: 1.5px solid #e2e8f0"
                    onmouseover="this.style.background='#eff6ff'; this.style.color='#1a3a6b'"
                    onmouseout="this.style.background='#f8faff'; this.style.color='#6b7280'">{{ $page }}</a>
                @elseif($page == 1 || $page == $transaksis->lastPage())
                <a href="{{ $url }}"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 text-sm transition"
                    style="background: #f8faff; border: 1.5px solid #e2e8f0">{{ $page }}</a>
                @elseif(abs($page - $transaksis->currentPage()) == 3)
                <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-sm">…</span>
                @endif
            @endforeach

            {{-- Next --}}
            @if($transaksis->hasMorePages())
            <a href="{{ $transaksis->nextPageUrl() }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 text-sm transition"
                style="background: #f8faff; border: 1.5px solid #e2e8f0"
                onmouseover="this.style.background='#1a3a6b'; this.style.color='white'; this.style.borderColor='#1a3a6b'"
                onmouseout="this.style.background='#f8faff'; this.style.color='#6b7280'; this.style.borderColor='#e2e8f0'">›</a>
            @else
            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-300 text-sm cursor-not-allowed"
                style="background: #f8faff; border: 1.5px solid #e2e8f0">›</span>
            @endif
        </div>
    </div>
    @endif

</div>
    </div>

{{-- Footer --}}
<p class="text-center text-xs text-gray-300 mt-8">
    © {{ date('Y') }} Vendor Savve — Storage Management System
</p>

@endsection

<script>
document.getElementById('event_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const tanggalInput = document.getElementById('tanggal');
    if (selected.value && selected.dataset.mulai) {
        tanggalInput.min = selected.dataset.mulai;
        tanggalInput.max = selected.dataset.selesai;
    } else {
        tanggalInput.min = '';
        tanggalInput.max = '';
        tanggalInput.value = '';
    }
});

window.addEventListener('load', function() {
    const select = document.getElementById('event_id');
    const selected = select.options[select.selectedIndex];
    const tanggalInput = document.getElementById('tanggal');
    if (selected.value && selected.dataset.mulai) {
        tanggalInput.min = selected.dataset.mulai;
        tanggalInput.max = selected.dataset.selesai;
    }
});
</script>