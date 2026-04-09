@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Event Aktif</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $totalEventAktif }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $transaksiHariIni }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Belum Diambil</p>
        <p class="text-3xl font-bold text-orange-500 mt-1">{{ $belumDiambil }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <p class="text-sm text-gray-500">Sudah Diambil</p>
        <p class="text-3xl font-bold text-gray-600 mt-1">{{ $sudahDiambil }}</p>
    </div>
</div>

{{-- Transaksi Terbaru --}}
<div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold text-gray-700 mb-4">Transaksi Terbaru</h3>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left">No. Transaksi</th>
                <th class="px-4 py-3 text-left">Penitip</th>
                <th class="px-4 py-3 text-left">Event</th>
                <th class="px-4 py-3 text-left">Kasir</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse(App\Models\Transaksi::with(['event','kasir'])->latest()->take(10)->get() as $t)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-mono font-medium">{{ $t->nomor_transaksi }}</td>
                <td class="px-4 py-3">{{ $t->nama_penitip }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $t->event->nama_event }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $t->kasir->name }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $t->status === 'dititip' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                        {{ $t->status === 'dititip' ? 'Dititip' : 'Sudah Diambil' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $t->waktu_penitipan->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada transaksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection