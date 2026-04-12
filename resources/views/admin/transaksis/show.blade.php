@extends('layouts.admin')
@section('title', 'Detail Transaksi')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Detail Transaksi</h1>
        <p class="text-gray-400 text-sm mt-1">Informasi lengkap transaksi penitipan barang.</p>
    </div>
    <a href="{{ route('admin.transaksis.index') }}"
        class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 shadow-sm">
        ← Kembali
    </a>
</div>

<div class="flex gap-5">

    {{-- Kiri --}}
    <div class="flex-1 space-y-4">

        {{-- Info Utama --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4" style="background: #1e293b">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Detail Transaksi</p>
                <span class="text-xs font-bold text-white bg-white/10 px-3 py-1 rounded-full font-mono">
                    {{ $transaksi->nomor_transaksi }}
                </span>
            </div>
            <div class="px-6 py-6">
                <div class="grid grid-cols-3 gap-6 mb-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Penitip</p>
                        <p class="text-lg font-black text-gray-800">{{ $transaksi->nama_penitip }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">No. WhatsApp</p>
                        <p class="text-lg font-black text-gray-800">{{ $transaksi->no_whatsapp }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Event</p>
                        <p class="text-lg font-black text-gray-800">{{ $transaksi->event->nama_event }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Kasir</p>
                        <p class="font-bold text-gray-700">{{ $transaksi->kasir->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Waktu Penitipan</p>
                        <p class="font-bold text-gray-700">{{ $transaksi->waktu_penitipan->format('d M Y, H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Status</p>
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $transaksi->status === 'dititip' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600' }}">
                            {{ $transaksi->status === 'dititip' ? 'DITITIPKAN' : 'SUDAH DIAMBIL' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Barang --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <p class="font-black text-gray-800">Daftar Barang</p>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Barang</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Ukuran</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Qty</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Harga</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($transaksi->details as $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 font-medium text-gray-700">
                            {{ $detail->nama_barang_custom ?? $detail->kategori->nama_kategori }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold">
                                {{ $detail->ukuran }}
                            </span>
                        </td>
                        <td class="px-5 py-4 font-semibold text-gray-700">{{ $detail->jumlah }}</td>
                        <td class="px-5 py-4 text-right text-gray-500">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-right font-bold text-gray-800">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-200">
                        <td colspan="4" class="px-5 py-4 text-right font-black text-gray-700">Total</td>
                        <td class="px-5 py-4 text-right font-black text-xl text-indigo-600">
                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Kanan --}}
    <div class="w-72 space-y-4">

        {{-- Nomor Transaksi --}}
        <div class="rounded-2xl p-6 text-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #3730a3, #6366f1)">
            <p class="text-xs font-semibold text-indigo-300 uppercase tracking-widest mb-2">Nomor Transaksi</p>
            <p class="text-2xl font-black tracking-tight leading-tight mb-4 font-mono">
                {{ $transaksi->nomor_transaksi }}
            </p>
            <div>
                <p class="text-xs text-indigo-300 uppercase tracking-wider">Status</p>
                <p class="font-bold text-white">
                    {{ $transaksi->status === 'dititip' ? 'DITITIPKAN' : 'SUDAH DIAMBIL' }}
                </p>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/10 rounded-full"></div>
        </div>

        {{-- Timeline --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Timeline</p>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-indigo-500 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600">Barang Dititipkan</p>
                        <p class="text-xs text-gray-400">{{ $transaksi->waktu_penitipan->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>
                @if($transaksi->waktu_pengambilan)
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5 flex-shrink-0"></div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600">Barang Diambil</p>
                        <p class="text-xs text-gray-400">{{ $transaksi->waktu_pengambilan->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>
                @else
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 bg-gray-200 rounded-full mt-1.5 flex-shrink-0 animate-pulse"></div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400">Menunggu Pengambilan...</p>
                        <p class="text-xs text-gray-300">Belum diambil</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Info Kasir --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Diproses Oleh</p>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-sm">
                    {{ strtoupper(substr($transaksi->kasir->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $transaksi->kasir->name }}</p>
                    <p class="text-xs text-gray-400">Kasir</p>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection