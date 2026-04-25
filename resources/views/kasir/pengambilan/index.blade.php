@extends('layouts.kasir')
@section('title', 'Pengambilan Barang')

@section('content')

<div class="anim-fade-up delay-1 mb-6">
    <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #7c3aed">Transaksi</p>
    <h1 class="text-2xl font-black text-gray-900">Pengambilan Barang</h1>
    <p class="text-gray-400 text-sm mt-1">Validasi dan konfirmasi pengambilan barang titipan pelanggan.</p>
</div>

{{-- Pencarian --}}
<div class="flex flex-col sm:flex-row gap-4 mb-5">

    <div class="flex-1 anim-fade-up delay-2 bg-white rounded-2xl border border-gray-100 p-6"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <p class="text-xs font-bold uppercase tracking-widest mb-4" style="color: #7c3aed">Pencarian Cepat</p>
        <form action="{{ route('kasir.pengambilan.cari') }}" method="POST">
            @csrf
            <div class="flex gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">Nama Penitip</label>
                    <input type="text" name="nama_penitip"
                        value="{{ old('nama_penitip') }}"
                        class="w-full rounded-xl px-4 py-3 text-sm transition"
                        style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #374151"
                        placeholder="Contoh: Budi Santoso"
                        onfocus="this.style.borderColor='#a78bfa'; this.style.boxShadow='0 0 0 3px rgba(167,139,250,0.1)'"
                        onblur="this.style.borderColor='#ede9fe'; this.style.boxShadow='none'">
                    @error('nama_penitip')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="flex items-center gap-2 px-6 py-3 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #5b21b6, #7c3aed); box-shadow: 0 4px 12px rgba(91,33,182,0.25)">
                        🔍 Cari Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Status Panel --}}
    <div class="anim-fade-up delay-3 w-full sm:w-64 rounded-2xl flex flex-col items-center justify-center p-6 text-white flex-shrink-0"
        style="background: linear-gradient(135deg, #1e1035, #2d1b69, #4c1d95); box-shadow: 0 8px 24px rgba(91,33,182,0.2)">
        @if(isset($transaksis) && $transaksis->count() > 0)
            <div class="w-14 h-14 rounded-full flex items-center justify-center text-3xl mb-3"
                style="background: rgba(167,139,250,0.2)">✓</div>
            <p class="font-black text-lg">Data Ditemukan</p>
            <p class="text-xs text-center mt-1" style="color: #c4b5fd">
                {{ $transaksis->count() }} transaksi ditemukan.
            </p>
        @elseif(session('error'))
            <div class="text-4xl mb-3">✕</div>
            <p class="font-black text-lg">Tidak Ditemukan</p>
            <p class="text-xs text-center mt-1" style="color: #c4b5fd">Nama tidak ditemukan atau sudah diambil.</p>
        @else
            <div class="text-5xl mb-3 opacity-40">🔍</div>
            <p class="font-black text-lg">Cari Penitip</p>
            <p class="text-xs text-center mt-1" style="color: #c4b5fd">Masukkan nama penitip untuk mencari data barang.</p>
        @endif
    </div>

</div>

{{-- Hasil Pencarian --}}
@isset($transaksis)
@if($transaksis->count() > 0)
<div class="space-y-4">
    @foreach($transaksis as $transaksi)
    <div class="anim-fade-up delay-4 rounded-2xl overflow-hidden border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">

        <div class="flex justify-between items-center px-6 py-4"
            style="background: linear-gradient(135deg, #1e1035, #2d1b69)">
            <p class="text-xs font-bold uppercase tracking-widest" style="color: rgba(255,255,255,0.5)">Detail Transaksi</p>
            <span class="text-xs font-bold px-3 py-1 rounded-full font-mono"
                style="background: rgba(167,139,250,0.2); color: #c4b5fd">
                {{ $transaksi->nomor_transaksi }}
            </span>
        </div>

        <div class="bg-white px-6 py-6">
            <div class="grid grid-cols-3 gap-6 mb-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Nama Penitip</p>
                    <p class="text-xl font-black text-gray-800">{{ $transaksi->nama_penitip }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Barang</p>
                    @foreach($transaksi->details as $d)
                    <p class="font-bold text-gray-800">
                        {{ $d->nama_barang_custom ?? $d->kategori->nama_kategori }}
                    </p>
                    @endforeach
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Quantity</p>
                    <p class="text-xl font-black text-gray-800">{{ $transaksi->details->sum('jumlah') }} Unit</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6 mb-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color: #94a3b8">Ukuran</p>
                    @foreach($transaksi->details->take(1) as $d)
                    <span class="px-4 py-1.5 rounded-lg text-sm font-bold"
                        style="background: #faf5ff; color: #7c3aed">
                        {{ $d->ukuran === 'S' ? 'Small' : ($d->ukuran === 'M' ? 'Medium' : ($d->ukuran === 'L' ? 'Large' : 'Extra Large')) }}
                    </span>
                    @endforeach
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Event</p>
                    <p class="font-bold text-gray-800">{{ $transaksi->event->nama_event }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Status</p>
                    <p class="flex items-center gap-2 font-bold" style="color: #7c3aed">
                        <span class="w-2 h-2 rounded-full animate-pulse inline-block" style="background: #a78bfa"></span>
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
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color: #94a3b8">Durasi Penitipan</p>
                    <p class="text-xs font-bold" style="color: #7c3aed">{{ $durasi }} Jam / {{ $maxJam }} Jam</p>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all"
                        style="width: {{ $pctDurasi }}%; background: linear-gradient(to right, #5b21b6, #a78bfa)"></div>
                </div>
            </div>
        </div>

        {{-- Konfirmasi --}}
        <div class="px-6 pb-6 bg-white">
            <form action="{{ route('kasir.pengambilan.konfirmasi', $transaksi) }}" method="POST"
                onsubmit="return confirm('Konfirmasi pengambilan barang atas nama {{ $transaksi->nama_penitip }}?')">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-3 py-4 rounded-2xl text-white font-black text-base transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #5b21b6, #7c3aed); box-shadow: 0 4px 16px rgba(91,33,182,0.25)">
                    🛡️ Konfirmasi Pengambilan
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif

<a href="{{ route('kasir.pengambilan.index') }}"
    class="w-full flex items-center justify-center py-4 rounded-2xl font-bold text-sm transition mt-4"
    style="background: white; border: 1.5px solid #ede9fe; color: #7c3aed">
    BATALKAN & KEMBALI
</a>
@endisset

@endsection