@extends('layouts.kasir')
@section('title', 'Dashboard')

@section('content')

{{-- Greeting --}}
<div class="mb-6">
    <h1 class="text-2xl font-black text-gray-800">Selamat Datang, Cashier! 👋</h1>
    <p class="text-gray-400 text-sm mt-1">Siap melayani penitipan barang hari ini. Pantau performa shift Anda di bawah.</p>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-4 gap-4 mb-8">

    {{-- Jumlah Transaksi --}}
    <div class="bg-white rounded-2xl p-5 border-l-4 border-indigo-500 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-lg">📊</div>
            <span class="text-xs font-semibold text-green-500 bg-green-50 px-2 py-0.5 rounded-full">+12%</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Jumlah Transaksi</p>
        <p class="text-3xl font-black text-gray-800">{{ $transaksiHariIni }}</p>
    </div>

    {{-- Masih Dititipkan --}}
    <div class="bg-white rounded-2xl p-5 border-l-4 border-orange-400 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-lg">🗃️</div>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Masih Dititipkan</p>
        <p class="text-3xl font-black text-gray-800">{{ $belumDiambil }}</p>
    </div>

    {{-- Sudah Diambil --}}
    <div class="bg-white rounded-2xl p-5 border-l-4 border-green-400 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-lg">✅</div>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Sudah Diambil</p>
        <p class="text-3xl font-black text-gray-800">{{ $sudahDiambil }}</p>
    </div>

    {{-- Event Aktif --}}
    @php $firstEvent = $eventAktif->first(); @endphp
    <div class="rounded-2xl p-5 shadow-sm text-white relative overflow-hidden"
        style="background: linear-gradient(135deg, #1e3a8a, #3730a3)">
        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-lg mb-4">📅</div>
        <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider mb-1">Event Aktif</p>
        <p class="text-lg font-black leading-tight">
            {{ $firstEvent ? $firstEvent->nama_event : 'Tidak ada event aktif' }}
        </p>
        {{-- Decorative circle --}}
        <div class="absolute -bottom-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
        <div class="absolute -bottom-8 -right-8 w-28 h-28 bg-white/5 rounded-full"></div>
    </div>
</div>

{{-- Quick Access --}}
<div class="mb-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-black text-gray-800">Quick Access</h2>
        <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">ACTION REQUIRED</span>
    </div>
    <div class="grid grid-cols-2 gap-4">

        {{-- Transaksi Penitipan --}}
        <a href="{{ route('kasir.transaksi.create') }}"
            class="relative rounded-2xl p-7 text-white overflow-hidden group transition hover:scale-[1.01]"
            style="background: linear-gradient(135deg, #3730a3, #4f46e5)">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl mb-4">➕</div>
            <h3 class="text-xl font-black mb-2">Transaksi Penitipan Baru</h3>
            <p class="text-blue-200 text-sm">Mulai penerimaan barang baru untuk pelanggan. Cepat, aman, dan tercatat otomatis.</p>
            {{-- Decorative --}}
            <div class="absolute -bottom-6 -right-6 w-28 h-28 bg-white/10 rounded-full"></div>
            <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/5 rounded-full"></div>
        </a>

        {{-- Pengambilan Barang --}}
        <a href="{{ route('kasir.pengambilan.index') }}"
            class="relative rounded-2xl p-7 overflow-hidden group transition hover:scale-[1.01]"
            style="background: linear-gradient(135deg, #e0e7ff, #c7d2fe)">
            <div class="w-12 h-12 bg-indigo-200 rounded-2xl flex items-center justify-center text-2xl mb-4">📦</div>
            <h3 class="text-xl font-black text-indigo-900 mb-2">Pengambilan Barang</h3>
            <p class="text-indigo-500 text-sm">Verifikasi data dan serahkan kembali barang titipan pelanggan dengan satu klik.</p>
            {{-- Decorative --}}
            <div class="absolute -bottom-6 -right-6 w-28 h-28 bg-indigo-300/30 rounded-full"></div>
            <div class="absolute -top-4 -right-4 w-20 h-20 bg-indigo-300/20 rounded-full"></div>
        </a>
    </div>
</div>

{{-- Aktivitas Terakhir --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
    <div class="flex justify-between items-center px-5 py-4 border-b border-gray-100">
        <h2 class="font-black text-gray-800">Aktivitas Terakhir</h2>
        <a href="{{ route('kasir.transaksi.index') }}"
            class="text-sm font-semibold text-indigo-600 hover:underline">Lihat Semua</a>
    </div>

    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-50">
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">ID Transaksi</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Customer</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Jenis Barang</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Waktu</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($transaksiTerbaru as $t)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-4">
                    <a href="{{ route('kasir.transaksi.show', $t) }}"
                        class="font-bold text-indigo-600 hover:underline">
                        #{{ substr($t->nomor_transaksi, -4) }}
                    </a>
                </td>
                <td class="px-5 py-4 font-semibold text-gray-700">{{ $t->nama_penitip }}</td>
                <td class="px-5 py-4 text-gray-500">
                    @foreach($t->details->take(1) as $d)
                        {{ $d->nama_barang_custom ?? $d->kategori->nama_kategori }}
                        ({{ ucfirst(strtolower($d->ukuran)) }})
                    @endforeach
                    @if($t->details->count() > 1)
                        <span class="text-xs text-indigo-400">+{{ $t->details->count() - 1 }}</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-gray-400">{{ $t->waktu_penitipan->format('H:i') }} WIB</td>
                <td class="px-5 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $t->status === 'dititip' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600' }}">
                        {{ $t->status === 'dititip' ? 'DITITIPKAN' : 'DIAMBIL' }}
                    </span>
                </td>
                <td class="px-5 py-4">
                    <a href="{{ route('kasir.transaksi.show', $t) }}"
                        class="text-gray-300 hover:text-gray-500 text-lg">⋯</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-300">
                    <p class="text-4xl mb-2">📋</p>
                    <p>Belum ada transaksi hari ini.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection