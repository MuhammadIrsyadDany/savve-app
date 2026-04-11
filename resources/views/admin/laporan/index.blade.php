@extends('layouts.admin')
@section('title', 'Laporan Harian')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Laporan Harian</h1>
        <p class="text-gray-400 text-sm mt-1">Pantau aktivitas penitipan barang masuk dan keluar secara real-time.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.laporan.export', request()->query()) }}"
            class="flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 shadow-sm">
            📄 Export Excel
        </a>
        <button onclick="window.print()"
            class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 rounded-xl text-sm font-semibold text-white hover:bg-indigo-700 shadow-sm">
            🖨️ Print Harian
        </button>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
    <form method="GET" action="{{ route('admin.laporan.index') }}">
        <div class="flex items-end gap-4">

            {{-- Tanggal --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Periode Tanggal</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">📅</span>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
            </div>

            {{-- Event --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Pilih Event</label>
                <select name="event_id"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    <option value="">Semua Event</option>
                    @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                        {{ $event->nama_event }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
                <div class="flex gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}"
                        class="px-4 py-2.5 rounded-xl text-sm font-semibold transition
                        {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                        Semua
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'dititip']) }}"
                        class="px-4 py-2.5 rounded-xl text-sm font-semibold transition
                        {{ request('status') === 'dititip' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                        Dititipkan
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'sudah_diambil']) }}"
                        class="px-4 py-2.5 rounded-xl text-sm font-semibold transition
                        {{ request('status') === 'sudah_diambil' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                        Diambil
                    </a>
                </div>
            </div>

            {{-- Submit & Reset --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">
                    Tampilkan
                </button>
                <a href="{{ route('admin.laporan.index') }}"
                    class="px-3 py-2.5 bg-gray-100 text-gray-500 rounded-xl text-sm font-semibold hover:bg-gray-200">
                    🔄
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm mb-5">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">No. Transaksi</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Penitip</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Barang & Ukuran</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Jumlah</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Harga</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal</th>
                <th class="px-5 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($transaksis as $t)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-4">
                    <a href="{{ route('admin.transaksis.show', $t) }}"
                        class="font-bold text-indigo-600 hover:underline">
                        #{{ substr($t->nomor_transaksi, -5) }}
                    </a>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($t->nama_penitip, 0, 2)) }}
                        </div>
                        <span class="font-semibold text-gray-700">{{ $t->nama_penitip }}</span>
                    </div>
                </td>
                <td class="px-5 py-4">
                    @foreach($t->details->take(1) as $d)
                    <p class="font-medium text-gray-700">{{ $d->nama_barang_custom ?? $d->kategori->nama_kategori }}</p>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Ukuran {{ $d->ukuran }}</p>
                    @endforeach
                    @if($t->details->count() > 1)
                    <p class="text-xs text-indigo-400">+{{ $t->details->count() - 1 }} barang lain</p>
                    @endif
                </td>
                <td class="px-5 py-4 font-semibold text-gray-700">
                    {{ $t->details->sum('jumlah') }}
                </td>
                <td class="px-5 py-4">
                    <span class="font-bold text-gray-800">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</span>
                </td>
                <td class="px-5 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $t->status === 'dititip' ? 'bg-orange-100 text-orange-600' : 'bg-green-100 text-green-600' }}">
                        {{ $t->status === 'dititip' ? 'DITITIPKAN' : 'DIAMBIL' }}
                    </span>
                </td>
                <td class="px-5 py-4 text-gray-400 text-xs">
                    {{ $t->waktu_penitipan->format('d M Y') }},<br>
                    {{ $t->waktu_penitipan->format('H:i') }}
                </td>
                <td class="px-5 py-4">
                    <a href="{{ route('admin.transaksis.show', $t) }}"
                        class="text-gray-300 hover:text-gray-500 text-lg">⋯</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-12 text-center text-gray-300">
                    <p class="text-4xl mb-2">📋</p>
                    <p>Pilih filter di atas untuk menampilkan laporan.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer Tabel --}}
    @if($transaksis->count() > 0)
    <div class="px-5 py-4 border-t border-gray-100 flex justify-between items-center">
        <div class="flex gap-6">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Total Transaksi</p>
                <p class="text-xl font-black text-gray-800">{{ $transaksis->count() }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Estimasi Pendapatan</p>
                <p class="text-xl font-black text-indigo-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
        </div>
        {{-- Pagination placeholder --}}
        <div class="flex items-center gap-1">
            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 hover:bg-gray-200">‹</button>
            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-600 text-white font-bold text-sm">1</button>
            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 text-sm">2</button>
            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 text-sm">3</button>
            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 hover:bg-gray-200">›</button>
        </div>
    </div>
    @endif
</div>

{{-- Bottom Stats --}}
@if($transaksis->count() > 0)
<div class="grid grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-xl">🗃️</div>
        <div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Kapasitas Gudang</p>
            <p class="text-xl font-black text-gray-800">{{ $transaksis->where('status','dititip')->sum(fn($t) => $t->details->sum('jumlah')) }} Unit</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center text-xl">⏳</div>
        <div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Belum Diambil</p>
            <p class="text-xl font-black text-gray-800">{{ $totalDititip }} Transaksi</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-xl">✅</div>
        <div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Selesai Hari Ini</p>
            <p class="text-xl font-black text-gray-800">{{ $totalDiambil }} Transaksi</p>
        </div>
    </div>
</div>
@endif

{{-- Footer --}}
<p class="text-center text-xs text-gray-300 mt-8 uppercase tracking-widest">
    © {{ date('Y') }} Savve Logistics Architecture • Premium Management System
</p>

@endsection