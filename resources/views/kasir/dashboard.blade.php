@extends('layouts.kasir')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Transaksi Saya Hari Ini</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $transaksiHariIni }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Barang Belum Diambil</p>
        <p class="text-3xl font-bold text-orange-500 mt-1">{{ $belumDiambil }}</p>
    </div>
</div>

{{-- Event Aktif --}}
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h3 class="font-semibold text-gray-700 mb-3">Event Aktif</h3>
    @forelse($eventAktif as $event)
    <div class="flex justify-between items-center py-2 border-b last:border-0">
        <div>
            <p class="font-medium">{{ $event->nama_event }}</p>
            <p class="text-xs text-gray-400">
                {{ $event->tanggal_mulai->format('d M Y') }} — {{ $event->tanggal_selesai->format('d M Y') }}
            </p>
        </div>
        <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Aktif</span>
    </div>
    @empty
    <p class="text-gray-400 text-sm">Tidak ada event aktif saat ini.</p>
    @endforelse
</div>

{{-- Transaksi Terbaru --}}
<div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold text-gray-700 mb-3">Transaksi Terbaru Saya</h3>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-3 py-2 text-left">No. Transaksi</th>
                <th class="px-3 py-2 text-left">Penitip</th>
                <th class="px-3 py-2 text-left">Status</th>
                <th class="px-3 py-2 text-left">Waktu</th>
                <th class="px-3 py-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($transaksiTerbaru as $t)
            <tr class="hover:bg-gray-50">
                <td class="px-3 py-2 font-mono font-medium">{{ $t->nomor_transaksi }}</td>
                <td class="px-3 py-2">{{ $t->nama_penitip }}</td>
                <td class="px-3 py-2">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $t->status === 'dititip' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                        {{ $t->status === 'dititip' ? 'Dititip' : 'Sudah Diambil' }}
                    </span>
                </td>
                <td class="px-3 py-2 text-gray-500">{{ $t->waktu_penitipan->format('d M Y H:i') }}</td>
                <td class="px-3 py-2">
                    <a href="{{ route('kasir.transaksi.show', $t) }}" class="text-green-600 hover:underline">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-3 py-6 text-center text-gray-400">Belum ada transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection