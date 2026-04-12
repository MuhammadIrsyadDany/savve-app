@extends('layouts.admin')
@section('title', 'Kelola Event')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Kelola Event</h1>
        <p class="text-gray-400 text-sm mt-1">Kelola data event dan tarif penitipan barang.</p>
    </div>
    <a href="{{ route('admin.events.create') }}"
        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
        style="background: linear-gradient(135deg, #3730a3, #4f46e5)">
        ➕ Tambah Event
    </a>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Event</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tarif (S/M/L/XL)</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                <th class="px-5 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Transaksi</th>
                <th class="px-5 py-4"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($events as $event)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-4">
                    <p class="font-bold text-gray-800">{{ $event->nama_event }}</p>
                </td>
                <td class="px-5 py-4 text-gray-500 text-xs">
                    <p>{{ $event->tanggal_mulai->format('d M Y') }}</p>
                    <p class="text-gray-400">s/d {{ $event->tanggal_selesai->format('d M Y') }}</p>
                </td>
                <td class="px-5 py-4">
                    @php $tarifs = $event->tarifs->keyBy('ukuran'); @endphp
                    <div class="flex gap-1 flex-wrap">
                        @foreach(['S','M','L','XL'] as $u)
                        <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold">
                            {{ $u }}: Rp {{ number_format($tarifs[$u]->harga ?? 0, 0, ',', '.') }}
                        </span>
                        @endforeach
                    </div>
                </td>
                <td class="px-5 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $event->status === 'aktif' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500' }}">
                        {{ $event->status === 'aktif' ? '● Aktif' : '● Non Aktif' }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    <span class="font-bold text-gray-700">{{ $event->transaksis->count() }}</span>
                    <span class="text-xs text-gray-400 ml-1">transaksi</span>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.events.edit', $event) }}"
                            class="text-indigo-600 hover:underline text-xs font-semibold">Edit</a>
                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                            onsubmit="return confirm('Hapus event ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-xs font-semibold">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-16 text-center">
                    <p class="text-4xl mb-3">🎪</p>
                    <p class="text-gray-400 font-medium">Belum ada event.</p>
                    <a href="{{ route('admin.events.create') }}"
                        class="inline-block mt-3 text-sm text-indigo-600 font-semibold hover:underline">
                        Tambah event pertama →
                    </a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($events->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $events->links() }}
    </div>
    @endif
</div>

@endsection