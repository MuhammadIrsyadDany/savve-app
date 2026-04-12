@extends('layouts.kasir')
@section('title', 'Pengambilan Barang')

@section('content')

{{-- Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-black text-gray-800">Pengambilan Barang</h1>
    <p class="text-gray-400 text-sm mt-1">Validasi dan konfirmasi pengambilan barang titipan untuk menyelesaikan transaksi pelanggan.</p>
</div>

{{-- Pencarian --}}
<div class="flex gap-4 mb-5">

    {{-- Form Cari --}}
    <div class="flex-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-4">Pencarian Cepat</p>
        <form action="{{ route('kasir.pengambilan.cari') }}" method="POST">
            @csrf
            <div class="flex gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nama Penitip</label>
                    <input type="text" name="nama_penitip"
                        value="{{ old('nama_penitip') }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="Contoh: Budi Santoso">
                    @error('nama_penitip')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-3 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #3730a3, #4f46e5)">
                        🔍 Cari Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Status Panel --}}
    <div class="w-72 rounded-2xl flex flex-col items-center justify-center p-6 text-white"
        style="background: linear-gradient(135deg, #3730a3, #6366f1)">
        @if(isset($transaksis) && $transaksis->count() > 0)
            <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center text-3xl mb-3">✓</div>
            <p class="font-black text-lg">Data Ditemukan</p>
            <p class="text-indigo-200 text-xs text-center mt-1">{{ $transaksis->count() }} transaksi ditemukan. Pilih transaksi untuk konfirmasi.</p>
        @elseif(session('error'))
            <div class="text-4xl mb-3">✕</div>
            <p class="font-black text-lg">Tidak Ditemukan</p>
            <p class="text-indigo-200 text-xs text-center mt-1">Nama penitip tidak ditemukan atau barang sudah diambil.</p>
        @else
            <div class="text-5xl mb-3 opacity-50">🔍</div>
            <p class="font-black text-lg">Cari Penitip</p>
            <p class="text-indigo-200 text-xs text-center mt-1">Masukkan nama penitip untuk mencari data barang.</p>
        @endif
    </div>

</div>

{{-- Hasil Pencarian --}}
@isset($transaksis)
@if($transaksis->count() > 0)
<div class="space-y-4">
    @foreach($transaksis as $transaksi)
    <div class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm">

        {{-- Header Detail --}}
        <div class="flex justify-between items-center px-6 py-4"
            style="background: #1e293b">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Detail Transaksi</p>
            <span class="text-xs font-bold text-white bg-white/10 px-3 py-1 rounded-full font-mono">
                {{ $transaksi->nomor_transaksi }}
            </span>
        </div>

        {{-- Body Detail --}}
        <div class="bg-white px-6 py-6">
            <div class="grid grid-cols-3 gap-6 mb-6">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Penitip</p>
                    <p class="text-xl font-black text-gray-800">{{ $transaksi->nama_penitip }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Barang</p>
                    @foreach($transaksi->details as $d)
                    <p class="font-bold text-gray-800">
                        {{ $d->nama_barang_custom ?? $d->kategori->nama_kategori }}
                    </p>
                    @endforeach
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Quantity</p>
                    <p class="text-xl font-black text-gray-800">{{ $transaksi->details->sum('jumlah') }} Unit</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 mb-6">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Ukuran</p>
                    @foreach($transaksi->details->take(1) as $d)
                    <span class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-lg text-sm font-bold">
                        {{ $d->ukuran === 'S' ? 'Small' : ($d->ukuran === 'M' ? 'Medium' : ($d->ukuran === 'L' ? 'Large' : 'Extra Large')) }}
                    </span>
                    @endforeach
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Event</p>
                    <p class="font-bold text-gray-800">{{ $transaksi->event->nama_event }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Status</p>
                    <p class="flex items-center gap-2 font-bold text-indigo-600">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse inline-block"></span>
                        Dititipkan
                    </p>
                </div>
            </div>

            {{-- Durasi --}}
            @php
                $durasi = $transaksi->waktu_penitipan->diffInHours(now());
                $maxJam = 12;
                $pctDurasi = min(round(($durasi / $maxJam) * 100), 100);
            @endphp
            <div>
                <div class="flex justify-between items-center mb-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Durasi Penitipan</p>
                    <p class="text-xs font-bold text-indigo-600">{{ $durasi }} Jam / {{ $maxJam }} Jam</p>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full transition-all"
                        style="width: {{ $pctDurasi }}%; background: linear-gradient(to right, #4f46e5, #818cf8)"></div>
                </div>
            </div>
        </div>

        {{-- Tombol Konfirmasi --}}
        <div class="px-6 pb-6 bg-white">
            <form action="{{ route('kasir.pengambilan.konfirmasi', $transaksi) }}" method="POST"
                onsubmit="return confirm('Konfirmasi pengambilan barang atas nama {{ $transaksi->nama_penitip }}?')">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-3 py-3.5 rounded-2xl text-white font-black text-base transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #3730a3, #4f46e5)">
                    🛡️ Konfirmasi Pengambilan
                </button>
            </form>
        </div>

    </div>
    @endforeach
</div>
@endif
@endisset

{{-- Batalkan --}}
@isset($transaksis)
<a href="{{ route('kasir.pengambilan.index') }}"
    class="w-full flex items-center justify-center py-4 rounded-2xl bg-white border border-gray-200 text-gray-500 font-bold text-sm hover:bg-gray-50 transition mt-4">
    BATALKAN & KEMBALI
</a>
@endisset

@endsection