@extends('layouts.kasir')
@section('title', 'Riwayat Transaksi')

@section('content')

<div class="anim-fade-up delay-1 flex justify-between items-start mb-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #7c3aed">Transaksi</p>
        <h1 class="text-2xl font-black text-gray-900">Riwayat Transaksi</h1>
        <p class="text-gray-400 text-sm mt-1">Daftar seluruh transaksi penitipan yang kamu proses.</p>
    </div>
    <a href="{{ route('kasir.transaksi.create') }}"
        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90 flex-shrink-0"
        style="background: linear-gradient(135deg, #5b21b6, #7c3aed); box-shadow: 0 4px 12px rgba(91,33,182,0.25)">
        ➕ Transaksi Baru
    </a>
</div>

{{-- Filter --}}
<div class="anim-fade-up delay-2 bg-white rounded-2xl border border-gray-100 p-4 mb-5"
    style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
    <form method="GET" action="{{ route('kasir.transaksi.index') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">Cari Penitip / No. Transaksi</label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" style="font-size: 12px">🔍</span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="w-full rounded-xl pl-9 pr-4 py-2.5 text-sm transition"
                    style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #374151"
                    placeholder="Nama atau nomor transaksi..."
                    onfocus="this.style.borderColor='#a78bfa'; this.style.boxShadow='0 0 0 3px rgba(167,139,250,0.1)'"
                    onblur="this.style.borderColor='#ede9fe'; this.style.boxShadow='none'">
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">Status</label>
            <select name="status"
                class="rounded-xl px-4 py-2.5 text-sm transition"
                style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #374151">
                <option value="">Semua Status</option>
                <option value="dititip" {{ request('status') === 'dititip' ? 'selected' : '' }}>Dititipkan</option>
                <option value="sudah_diambil" {{ request('status') === 'sudah_diambil' ? 'selected' : '' }}>Sudah Diambil</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                class="rounded-xl px-4 py-2.5 text-sm transition"
                style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #374151">
        </div>
        <div class="flex gap-2">
            <button type="submit"
                class="px-5 py-2.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                style="background: linear-gradient(135deg, #5b21b6, #7c3aed)">
                Filter
            </button>
            <a href="{{ route('kasir.transaksi.index') }}"
                class="px-3.5 py-2.5 rounded-xl font-semibold text-sm transition"
                style="background: #faf5ff; color: #7c3aed; border: 1.5px solid #ede9fe">
                🔄
            </a>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="anim-fade-up delay-3 bg-white rounded-2xl border border-gray-100 overflow-hidden"
    style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background: #fdfbff; border-bottom: 2px solid #ede9fe">
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">ID Transaksi</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Customer</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Barang</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Event</th>
                    <th class="px-5 py-4 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Total</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Status</th>
                    <th class="px-5 py-4 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Waktu</th>
                    <th class="px-5 py-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr class="table-row" style="border-top: 1px solid #f5f3ff">
                    <td class="px-5 py-4 whitespace-nowrap">
                        <a href="{{ route('kasir.transaksi.show', $t) }}"
                            class="font-bold hover:underline" style="color: #7c3aed; font-family: monospace; font-size: 12px">
                            #{{ substr($t->nomor_transaksi, -4) }}
                        </a>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                style="background: linear-gradient(135deg, #5b21b6, #a78bfa); font-size: 11px">
                                {{ strtoupper(substr($t->nama_penitip, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $t->nama_penitip }}</p>
                                <p class="text-gray-400" style="font-size: 11px">{{ $t->no_whatsapp }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        @foreach($t->details->take(1) as $d)
                        <p class="font-medium text-gray-700 text-sm">{{ $d->nama_barang_custom ?? $d->kategori->nama_kategori }}</p>
                        <span class="inline-block px-2 py-0.5 rounded-md text-xs font-bold mt-0.5"
                            style="background: #faf5ff; color: #7c3aed">
                            Ukuran {{ $d->ukuran }}
                        </span>
                        @endforeach
                        @if($t->details->count() > 1)
                        <p class="text-xs mt-0.5" style="color: #7c3aed">+{{ $t->details->count() - 1 }} lainnya</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-gray-500 text-xs whitespace-nowrap">
                        {{ Str::limit($t->event->nama_event, 18) }}
                    </td>
                    <td class="px-5 py-4 text-right whitespace-nowrap">
                        <span class="font-black text-gray-900 text-sm">
                            Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-bold"
                            style="background: {{ $t->status === 'dititip' ? '#faf5ff' : '#f0fdf4' }};
                                   color: {{ $t->status === 'dititip' ? '#7c3aed' : '#15803d' }}">
                            {{ $t->status === 'dititip' ? 'DITITIPKAN' : 'DIAMBIL' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap text-gray-400 text-xs">
                        {{ $t->waktu_penitipan->format('d M Y') }}<br>
                        {{ $t->waktu_penitipan->format('H:i') }} WIB
                    </td>
                    <td class="px-5 py-4">
                        <a href="{{ route('kasir.transaksi.show', $t) }}"
                            class="text-gray-300 hover:text-purple-400 text-lg transition">⋯</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl"
                                style="background: #faf5ff">📋</div>
                            <p class="font-semibold text-gray-400">Belum ada transaksi.</p>
                            <a href="{{ route('kasir.transaksi.create') }}"
                                class="text-sm font-bold hover:underline" style="color: #7c3aed">
                                Buat transaksi pertama →
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transaksis->hasPages())
    <div class="px-5 py-4" style="border-top: 1px solid #f5f3ff">
        {{ $transaksis->links() }}
    </div>
    @endif
</div>

@endsection