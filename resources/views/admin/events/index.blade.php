@extends('layouts.admin')
@section('title', 'Kelola Event')

@section('content')

<div class="anim-fade-up delay-1 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #1a3a6b">Management</p>
        <h1 class="text-xl lg:text-2xl font-black text-gray-900">Kelola Event</h1>
        <p class="text-gray-400 text-sm mt-1">Kelola data event dan setting tarif penitipan barang.</p>
    </div>
    <a href="{{ route('admin.events.create') }}"
        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90 self-start flex-shrink-0"
        style="background: linear-gradient(135deg, #0f2044, #1e4d8c); box-shadow: 0 4px 12px rgba(15,32,68,0.2)">
        ＋ Tambah Event
    </a>
</div>

<div class="anim-fade-up delay-2 bg-white rounded-2xl border border-gray-100 overflow-hidden"
    style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
    <div class="overflow-x-auto">
        <table class="w-full text-sm" style="min-width: 600px">
            <thead>
                <tr style="background: #f8faff; border-bottom: 2px solid #e2e8f0">
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Nama Event</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Tanggal</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Tarif S/M/L/XL</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Status</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Transaksi</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                <tr class="table-row" style="border-top: 1px solid #f1f5f9">
                    <td class="px-5 py-4 whitespace-nowrap">
                        <p class="font-bold text-gray-800">{{ $event->nama_event }}</p>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <p class="text-sm text-gray-600">{{ $event->tanggal_mulai->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">s/d {{ $event->tanggal_selesai->format('d M Y') }}</p>
                    </td>
                    <td class="px-5 py-4">
                        @php $tarifs = $event->tarifs->keyBy('ukuran'); @endphp
                        <div class="flex gap-1 flex-wrap">
                            @foreach(['S','M','L','XL'] as $u)
                            <span class="px-2 py-0.5 rounded-lg text-xs font-bold whitespace-nowrap"
                                style="background: #eff6ff; color: #1d4ed8">
                                {{ $u }}: Rp {{ number_format($tarifs[$u]->harga ?? 0, 0, ',', '.') }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold"
                            style="background: {{ $event->status === 'aktif' ? '#f0fdf4' : '#fff5f5' }};
                                   color: {{ $event->status === 'aktif' ? '#15803d' : '#dc2626' }}">
                            {{ $event->status === 'aktif' ? '● Aktif' : '● Non Aktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span class="font-black text-gray-800">{{ $event->transaksis->count() }}</span>
                        <span class="text-xs text-gray-400 ml-1">transaksi</span>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.events.edit', $event) }}"
                                class="text-xs font-bold hover:underline" style="color: #1a3a6b">Edit</a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                                onsubmit="return confirm('Hapus event ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-bold hover:underline"
                                    style="color: #dc2626">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl"
                                style="background: #f8faff">🎪</div>
                            <p class="font-semibold text-gray-400">Belum ada event.</p>
                            <a href="{{ route('admin.events.create') }}"
                                class="text-sm font-bold hover:underline" style="color: #1a3a6b">
                                Tambah event pertama →
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($events->hasPages())
    <div class="px-5 py-4" style="border-top: 1px solid #f1f5f9">
        {{ $events->links() }}
    </div>
    @endif
</div>

@endsection