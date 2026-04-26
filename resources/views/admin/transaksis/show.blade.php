@extends('layouts.admin')
@section('title', 'Detail Transaksi')

@section('content')

<div class="anim-fade-up delay-1 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #1a3a6b">Laporan</p>
        <h1 class="text-xl lg:text-2xl font-black text-gray-900">Detail Transaksi</h1>
        <p class="text-gray-400 text-sm mt-1">Informasi lengkap transaksi penitipan barang.</p>
    </div>
    <a href="{{ route('admin.transaksis.index') }}"
        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition self-start flex-shrink-0"
        style="background: white; border: 1.5px solid #e2e8f0; color: #374151">
        ← Kembali
    </a>
</div>

<div class="flex flex-col lg:flex-row gap-5">

    {{-- Kiri --}}
    <div class="flex-1 space-y-4">

        {{-- Info Utama --}}
        <div class="anim-fade-up delay-2 bg-white rounded-2xl border border-gray-100 overflow-hidden"
            style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
            <div class="flex justify-between items-center px-5 lg:px-6 py-4"
                style="background: linear-gradient(135deg, #1e293b, #0f2044)">
                <p class="text-xs font-bold uppercase tracking-widest" style="color: rgba(255,255,255,0.5)">Detail Transaksi</p>
                <span class="text-xs font-bold px-3 py-1 rounded-full font-mono"
                    style="background: rgba(74,158,255,0.2); color: #93c5fd">
                    {{ $transaksi->nomor_transaksi }}
                </span>
            </div>
            <div class="px-5 lg:px-6 py-5 lg:py-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-5 lg:mb-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Nama Penitip</p>
                        <p class="text-base lg:text-lg font-black text-gray-800">{{ $transaksi->nama_penitip }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">No. WhatsApp</p>
                        <p class="text-base lg:text-lg font-black text-gray-800">{{ $transaksi->no_whatsapp }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Event</p>
                        <p class="text-base lg:text-lg font-black text-gray-800">{{ $transaksi->event->nama_event }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Kasir</p>
                        <p class="font-bold text-gray-700">{{ $transaksi->kasir->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Waktu Penitipan</p>
                        <p class="font-bold text-gray-700">{{ $transaksi->waktu_penitipan->format('d M Y, H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Status</p>
                        <span class="px-3 py-1 rounded-full text-xs font-bold"
                            style="background: {{ $transaksi->status === 'dititip' ? '#eff6ff' : '#f0fdf4' }};
                                   color: {{ $transaksi->status === 'dititip' ? '#1d4ed8' : '#15803d' }}">
                            {{ $transaksi->status === 'dititip' ? 'DITITIPKAN' : 'SUDAH DIAMBIL' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Barang --}}
        <div class="anim-fade-up delay-3 bg-white rounded-2xl border border-gray-100 overflow-hidden"
            style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
            <div class="px-5 lg:px-6 py-4" style="border-bottom: 1px solid #f1f5f9">
                <p class="font-black text-gray-800">Daftar Barang</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm" style="min-width: 400px">
                    <thead>
                        <tr style="background: #f8faff; border-bottom: 1px solid #e2e8f0">
                            <th class="px-5 py-3 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Barang</th>
                            <th class="px-5 py-3 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Ukuran</th>
                            <th class="px-5 py-3 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Qty</th>
                            <th class="px-5 py-3 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Harga</th>
                            <th class="px-5 py-3 text-right whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->details as $detail)
                        <tr class="table-row" style="border-top: 1px solid #f1f5f9">
                            <td class="px-5 py-4 font-medium text-gray-700 whitespace-nowrap">
                                {{ $detail->nama_barang_custom ?? $detail->kategori->nama_kategori }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-3 py-1 rounded-lg text-xs font-bold"
                                    style="background: #eff6ff; color: #1d4ed8">{{ $detail->ukuran }}</span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-700">{{ $detail->jumlah }}</td>
                            <td class="px-5 py-4 text-right text-gray-500 whitespace-nowrap">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right font-bold text-gray-800 whitespace-nowrap">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="border-top: 2px solid #e2e8f0">
                            <td colspan="4" class="px-5 py-4 text-right font-black text-gray-700">Total</td>
                            <td class="px-5 py-4 text-right font-black text-xl whitespace-nowrap" style="color: #1a3a6b">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Kanan --}}
    <div class="w-full lg:w-72 flex-shrink-0 space-y-4">

        {{-- Nomor Transaksi --}}
        <div class="anim-fade-up delay-2 rounded-2xl p-5 lg:p-6 text-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #0f2044, #1a3a6b, #1e4d8c); box-shadow: 0 8px 24px rgba(15,32,68,0.2)">
            <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color: #93c5fd">Nomor Transaksi</p>
            <p class="text-xl lg:text-2xl font-black tracking-tight leading-tight mb-4 font-mono break-all">
                {{ $transaksi->nomor_transaksi }}
            </p>
            <div>
                <p class="text-xs uppercase tracking-wider" style="color: rgba(255,255,255,0.5)">Status</p>
                <p class="font-bold text-white">
                    {{ $transaksi->status === 'dititip' ? 'DITITIPKAN' : 'SUDAH DIAMBIL' }}
                </p>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full" style="background: rgba(255,255,255,0.05)"></div>
        </div>

        {{-- Timeline --}}
        <div class="anim-fade-up delay-3 bg-white rounded-2xl border border-gray-100 p-5"
            style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
            <p class="text-xs font-bold uppercase tracking-wider mb-3" style="color: #94a3b8">Timeline</p>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0" style="background: #4a9eff"></div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600">Barang Dititipkan</p>
                        <p class="text-xs text-gray-400">{{ $transaksi->waktu_penitipan->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>
                @if($transaksi->waktu_pengambilan)
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0" style="background: #22c55e"></div>
                    <div>
                        <p class="text-xs font-semibold text-gray-600">Barang Diambil</p>
                        <p class="text-xs text-gray-400">{{ $transaksi->waktu_pengambilan->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>
                @else
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0 animate-pulse" style="background: #e2e8f0"></div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400">Menunggu Pengambilan...</p>
                        <p class="text-xs text-gray-300">Belum diambil</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Info Kasir --}}
        <div class="anim-fade-up delay-4 bg-white rounded-2xl border border-gray-100 p-5"
            style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
            <p class="text-xs font-bold uppercase tracking-wider mb-3" style="color: #94a3b8">Diproses Oleh</p>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-sm text-white flex-shrink-0"
                    style="background: linear-gradient(135deg, #0f2044, #4a9eff)">
                    {{ strtoupper(substr($transaksi->kasir->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $transaksi->kasir->name }}</p>
                    <p class="text-xs text-gray-400">Kasir</p>
                </div>
            </div>
        </div>

        {{-- Hapus Transaksi --}}
        @if($transaksi->status === 'dititip')
        <div class="anim-fade-up delay-5">
            <form action="{{ route('admin.transaksis.destroy', $transaksi) }}" method="POST"
                onsubmit="return confirm('Hapus transaksi ini? Semua data barang akan ikut terhapus.')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-bold text-sm transition"
                    style="background: #fff5f5; color: #dc2626; border: 1.5px solid #fecaca">
                    🗑️ Hapus Transaksi
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

@endsection