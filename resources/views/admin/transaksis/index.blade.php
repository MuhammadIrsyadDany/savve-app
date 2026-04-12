@extends('layouts.admin')
@section('title', 'Data Transaksi')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Data Transaksi</h1>
        <p class="text-gray-400 text-sm mt-1">Monitor seluruh transaksi penitipan barang dari semua kasir.</p>
    </div>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
    <form method="GET" action="{{ route('admin.transaksis.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Cari Penitip / No. Transaksi</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">🔍</span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                    placeholder="Nama penitip atau nomor transaksi...">
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Event</label>
            <select name="event_id"
                class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <option value="">Semua Event</option>
                @foreach($events as $event)
                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                    {{ $event->nama_event }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Status</label>
            <select name="status"
                class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                <option value="">Semua Status</option>
                <option value="dititip" {{ request('status') === 'dititip' ? 'selected' : '' }}>Dititipkan</option>
                <option value="sudah_diambil" {{ request('status') === 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
        </div>
        <button type="submit"
            class="px-5 py-2.5 rounded-xl text-white font-semibold text-sm transition hover:opacity-90"
            style="background: linear-gradient(135deg, #3730a3, #4f46e5)">
            Filter
        </button>
        <a href="{{ route('admin.transaksis.index') }}"
            class="px-4 py-2.5 bg-gray-100 text-gray-500 rounded-xl text-sm font-semibold hover:bg-gray-200">
            🔄
        </a>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">No. Transaksi</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Penitip</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Barang</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Event</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Kasir</th>
                <th class="px-5 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Waktu</th>
                <th class="px-5 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($transaksis as $t)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-4">
                    <a href="{{ route('admin.transaksis.show', $t) }}"
                        class="font-bold text-indigo-600 hover:underline font-mono text-xs">
                        {{ $t->nomor_transaksi }}
                    </a>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($t->nama_penitip, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700">{{ $t->nama_penitip }}</p>
                            <p class="text-xs text-gray-400">{{ $t->no_whatsapp }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4">
                    @foreach($t->details->take(1) as $d)
                    <p class="font-medium text-gray-700">{{ $d->nama_barang_custom ?? $d->kategori->nama_kategori }}</p>
                    <p class="text-xs text-gray-400">Ukuran {{ $d->ukuran }}</p>
                    @endforeach
                    @if($t->details->count() > 1)
                    <p class="text-xs text-indigo-400">+{{ $t->details->count() - 1 }} lainnya</p>
                    @endif
                </td>
                <td class="px-5 py-4 text-gray-500 text-xs">{{ Str::limit($t->event->nama_event, 18) }}</td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($t->kasir->name, 0, 1)) }}
                        </div>
                        <span class="text-xs text-gray-500">{{ $t->kasir->name }}</span>
                    </div>
                </td>
                <td class="px-5 py-4 text-right font-bold text-gray-800">
                    Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                </td>
                <td class="px-5 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $t->status === 'dititip' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600' }}">
                        {{ $t->status === 'dititip' ? 'DITITIPKAN' : 'DIAMBIL' }}
                    </span>
                </td>
                <td class="px-5 py-4 text-gray-400 text-xs">
                    {{ $t->waktu_penitipan->format('d M Y') }}<br>
                    {{ $t->waktu_penitipan->format('H:i') }} WIB
                </td>
                <td class="px-5 py-4">
                    <a href="{{ route('admin.transaksis.show', $t) }}"
                        class="text-gray-300 hover:text-indigo-500 text-lg">⋯</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-5 py-16 text-center">
                    <p class="text-4xl mb-3">📋</p>
                    <p class="text-gray-400 font-medium">Belum ada transaksi.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($transaksis->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $transaksis->links() }}
    </div>
    @endif
</div>

@endsection