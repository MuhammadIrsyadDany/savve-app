@extends('layouts.kasir')
@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-2xl space-y-4">
    {{-- Header Transaksi --}}
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-xs text-gray-400">Nomor Transaksi</p>
                <p class="text-2xl font-bold font-mono text-green-600">{{ $transaksi->nomor_transaksi }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-sm font-medium
                {{ $transaksi->status === 'dititip' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                {{ $transaksi->status === 'dititip' ? 'Dititip' : 'Sudah Diambil' }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
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
            <div>
                <p class="text-gray-400">Kasir</p>
                <p class="font-medium">{{ $transaksi->kasir->name }}</p>
            </div>
        </div>
    </div>

    {{-- Detail Barang --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-semibold text-gray-700 mb-3">Detail Barang</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-3 py-2 text-left">Barang</th>
                    <th class="px-3 py-2 text-left">Ukuran</th>
                    <th class="px-3 py-2 text-left">Qty</th>
                    <th class="px-3 py-2 text-right">Harga</th>
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
                    <td class="px-3 py-2 text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-3 py-2 text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-semibold">
                    <td colspan="4" class="px-3 py-2 text-right">Total</td>
                    <td class="px-3 py-2 text-right text-green-600">
                        Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex gap-3">
        <a href="{{ route('kasir.transaksi.create') }}"
            class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
            + Transaksi Baru
        </a>
        <a href="{{ route('kasir.transaksi.nota', $transaksi) }}" target="_blank"
            class="px-6 py-2 rounded-lg border hover:bg-gray-50">
                🖨️ Cetak Nota
        </a>
        <a href="{{ route('kasir.transaksi.index') }}"
            class="px-6 py-2 rounded-lg border hover:bg-gray-50">
            Riwayat
        </a>
    </div>
</div>
@endsection