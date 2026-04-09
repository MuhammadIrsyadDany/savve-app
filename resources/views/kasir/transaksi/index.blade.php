@extends('layouts.kasir')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left">No. Transaksi</th>
                <th class="px-4 py-3 text-left">Penitip</th>
                <th class="px-4 py-3 text-left">Event</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Waktu</th>
                <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($transaksis as $t)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono font-medium">{{ $t->nomor_transaksi }}</td>
                <td class="px-4 py-3">{{ $t->nama_penitip }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $t->event->nama_event }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $t->status === 'dititip' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                        {{ $t->status === 'dititip' ? 'Dititip' : 'Sudah Diambil' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $t->waktu_penitipan->format('d M Y H:i') }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('kasir.transaksi.show', $t) }}" class="text-blue-600 hover:underline">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $transaksis->links() }}</div>
</div>
@endsection