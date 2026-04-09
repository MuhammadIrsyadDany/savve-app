@extends('layouts.admin')
@section('title', 'Laporan Transaksi')

@section('content')

{{-- Filter --}}
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Event</label>
            <select name="event_id" class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Event</option>
                @foreach($events as $event)
                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                    {{ $event->nama_event }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Status</label>
            <select name="status" class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="dititip" {{ request('status') === 'dititip' ? 'selected' : '' }}>Dititip</option>
                <option value="sudah_diambil" {{ request('status') === 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Tampilkan
        </button>
        <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-50">
            Reset
        </a>
        @if($transaksis->count() > 0)
        <a href="{{ route('admin.laporan.export', request()->query()) }}"
            class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 ml-auto">
            ⬇️ Export Excel
        </a>
        @endif
    </form>
</div>

@if($transaksis->count() > 0)
{{-- Ringkasan --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500">Total Transaksi</p>
        <p class="text-2xl font-bold text-blue-600">{{ $transaksis->count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500">Total Pendapatan</p>
        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500">Belum Diambil / Sudah Diambil</p>
        <p class="text-2xl font-bold text-gray-700">{{ $totalDititip }} / {{ $totalDiambil }}</p>
    </div>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left">No. Transaksi</th>
                <th class="px-4 py-3 text-left">Penitip</th>
                <th class="px-4 py-3 text-left">Event</th>
                <th class="px-4 py-3 text-left">Kasir</th>
                <th class="px-4 py-3 text-right">Total</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($transaksis as $t)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono font-medium">{{ $t->nomor_transaksi }}</td>
                <td class="px-4 py-3">{{ $t->nama_penitip }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $t->event->nama_event }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $t->kasir->name }}</td>
                <td class="px-4 py-3 text-right">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $t->status === 'dititip' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                        {{ $t->status === 'dititip' ? 'Dititip' : 'Sudah Diambil' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $t->waktu_penitipan->format('d M Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="bg-white rounded-xl shadow p-12 text-center text-gray-400">
    Pilih filter di atas untuk menampilkan laporan.
</div>
@endif

@endsection