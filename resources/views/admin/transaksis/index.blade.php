@extends('layouts.admin')
@section('title', 'Data Transaksi')

@section('content')
{{-- Filter --}}
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <form method="GET" action="{{ route('admin.transaksis.index') }}" class="flex flex-wrap gap-3">
        <select name="event_id" class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Event</option>
            @foreach($events as $event)
            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                {{ $event->nama_event }}
            </option>
            @endforeach
        </select>
        <select name="status" class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Status</option>
            <option value="dititip" {{ request('status') === 'dititip' ? 'selected' : '' }}>Dititip</option>
            <option value="sudah_diambil" {{ request('status') === 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
        </select>
        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Filter
        </button>
        <a href="{{ route('admin.transaksis.index') }}" class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-50">
            Reset
        </a>
    </form>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left">No. Transaksi</th>
                <th class="px-4 py-3 text-left">Penitip</th>
                <th class="px-4 py-3 text-left">Event</th>
                <th class="px-4 py-3 text-left">Kasir</th>
                <th class="px-4 py-3 text-left">Total</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($transaksis as $t)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono font-medium">{{ $t->nomor_transaksi }}</td>
                <td class="px-4 py-3">{{ $t->nama_penitip }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $t->event->nama_event }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $t->kasir->name }}</td>
                <td class="px-4 py-3">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $t->status === 'dititip' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                        {{ $t->status === 'dititip' ? 'Dititip' : 'Sudah Diambil' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.transaksis.show', $t) }}" class="text-blue-600 hover:underline">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-6 text-center text-gray-400">Belum ada transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $transaksis->links() }}</div>
</div>
@endsection