@extends('layouts.kasir')
@section('title', 'Pengambilan Barang')

@section('content')
<div class="max-w-2xl space-y-4">

    {{-- Form Cari --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-4">Cari Transaksi</h3>
        <form action="{{ route('kasir.pengambilan.cari') }}" method="POST">
            @csrf
            <div class="flex gap-3">
                <input type="text" name="nomor_transaksi"
                    value="{{ old('nomor_transaksi', $transaksi->nomor_transaksi ?? '') }}"
                    class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 font-mono"
                    placeholder="Contoh: SVV-20260409-0001">
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Cari
                </button>
            </div>
            @error('nomor_transaksi')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </form>
    </div>

    {{-- Hasil Pencarian --}}
    @isset($transaksi)
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-xs text-gray-400">Nomor Transaksi</p>
                <p class="text-xl font-bold font-mono text-green-600">{{ $transaksi->nomor_transaksi }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700">
                Dititip
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
            <div>
                <p class="text-gray-400">Penitip</p>
                <p class="font-medium">{{ $transaksi->nama_penitip }}</p>
            </div>
            <div>
                <p class="text-gray-400">No. WhatsApp</p>
                <p class="font-medium">{{ $transaksi->no_whatsapp }}</p>
            </div>
            <div>
                <p class="text-gray-400">Event</p>
                <p class="font-medium">{{ $transaksi->event->nama_event }}</p>
            </div>
            <div>
                <p class="text-gray-400">Waktu Penitipan</p>
                <p class="font-medium">{{ $transaksi->waktu_penitipan->format('d M Y H:i') }}</p>
            </div>
        </div>

        {{-- Detail Barang --}}
        <table class="w-full text-sm mb-4">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-3 py-2 text-left">Barang</th>
                    <th class="px-3 py-2 text-left">Ukuran</th>
                    <th class="px-3 py-2 text-left">Qty</th>
                    <th class="px-3 py-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($transaksi->details as $detail)
                <tr>
                    <td class="px-3 py-2">
                        {{ $detail->nama_barang_custom ?? $detail->kategori->nama_kategori }}
                    </td>
                    <td class="px-3 py-2">{{ $detail->ukuran }}</td>
                    <td class="px-3 py-2">{{ $detail->jumlah }}</td>
                    <td class="px-3 py-2 text-right">
                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-semibold">
                    <td colspan="3" class="px-3 py-2 text-right">Total</td>
                    <td class="px-3 py-2 text-right text-green-600">
                        Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        {{-- Tombol Konfirmasi --}}
        <form action="{{ route('kasir.pengambilan.konfirmasi', $transaksi) }}" method="POST"
            onsubmit="return confirm('Konfirmasi pengambilan barang atas nama {{ $transaksi->nama_penitip }}?')">
            @csrf
            <button type="submit"
                class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-medium">
                ✅ Konfirmasi Pengambilan
            </button>
        </form>
    </div>
    @endisset

</div>
@endsection