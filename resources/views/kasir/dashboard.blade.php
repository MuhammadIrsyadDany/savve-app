@extends('layouts.kasir')
@section('title', 'Dashboard')

@section('content')

{{-- Greeting --}}
<div class="anim-fade-up delay-1 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
    <div>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #7c3aed">
            {{ now()->format('l, d F Y') }}
        </p>
        <h1 class="text-xl lg:text-2xl font-black text-gray-900">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-gray-400 text-sm mt-1">Siap melayani penitipan barang hari ini.</p>
    </div>
    <div class="flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold self-start flex-shrink-0"
        style="background: #faf5ff; color: #7c3aed; border: 1px solid #ddd6fe">
        <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background: #a78bfa"></span>
        Shift Aktif
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">

    <div class="stat-card anim-fade-up delay-2 bg-white rounded-2xl p-4 lg:p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex justify-between items-start mb-3 lg:mb-4">
            <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl flex items-center justify-center"
                style="background: linear-gradient(135deg, #faf5ff, #ede9fe)">
                <span class="text-base lg:text-lg">📊</span>
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full"
                style="background: #faf5ff; color: #7c3aed">+12%</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1" style="font-size: 9px">Transaksi Hari Ini</p>
        <p class="text-2xl lg:text-3xl font-black text-gray-900">{{ $transaksiHariIni }}</p>
        <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full" style="width: 65%; background: linear-gradient(90deg, #5b21b6, #a78bfa)"></div>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-3 bg-white rounded-2xl p-4 lg:p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex justify-between items-start mb-3 lg:mb-4">
            <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl flex items-center justify-center"
                style="background: linear-gradient(135deg, #fff7ed, #fed7aa)">
                <span class="text-base lg:text-lg">🗃️</span>
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full"
                style="background: #fff7ed; color: #c2410c">Aktif</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1" style="font-size: 9px">Masih Dititipkan</p>
        <p class="text-2xl lg:text-3xl font-black text-gray-900">{{ $belumDiambil }}</p>
        <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full" style="width: 45%; background: linear-gradient(90deg, #ea580c, #fb923c)"></div>
        </div>
    </div>

    <div class="stat-card anim-fade-up delay-4 bg-white rounded-2xl p-4 lg:p-5 border border-gray-100"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
        <div class="flex justify-between items-start mb-3 lg:mb-4">
            <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl flex items-center justify-center"
                style="background: linear-gradient(135deg, #f0fdf4, #dcfce7)">
                <span class="text-base lg:text-lg">✅</span>
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full"
                style="background: #f0fdf4; color: #15803d">Selesai</span>
        </div>
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1" style="font-size: 9px">Sudah Diambil</p>
        <p class="text-2xl lg:text-3xl font-black text-gray-900">{{ $sudahDiambil }}</p>
        <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full rounded-full" style="width: 80%; background: linear-gradient(90deg, #16a34a, #4ade80)"></div>
        </div>
    </div>

    @php $firstEvent = $eventAktif->first(); @endphp
    <div class="stat-card anim-fade-up delay-5 rounded-2xl p-4 lg:p-5 text-white relative overflow-hidden"
        style="background: linear-gradient(135deg, #1e1035 0%, #2d1b69 60%, #4c1d95 100%); box-shadow: 0 8px 24px rgba(91,33,182,0.3)">
        <div class="flex justify-between items-start mb-3 lg:mb-4">
            <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl flex items-center justify-center"
                style="background: rgba(255,255,255,0.15)">
                <span class="text-base lg:text-lg">📅</span>
            </div>
            <span class="text-xs font-bold px-2 py-1 rounded-full"
                style="background: rgba(167,139,250,0.25); color: #c4b5fd">Live</span>
        </div>
        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: rgba(255,255,255,0.6); font-size: 9px">Event Aktif</p>
        <p class="text-sm font-black leading-tight">
            {{ $firstEvent ? Str::limit($firstEvent->nama_event, 20) : 'Tidak ada event' }}
        </p>
        <div class="absolute -bottom-4 -right-4 w-20 h-20 rounded-full" style="background: rgba(255,255,255,0.05)"></div>
    </div>

</div>

{{-- Quick Access --}}
<div class="anim-fade-up delay-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-base lg:text-lg font-black text-gray-800">Quick Access</h2>
        <span class="text-xs font-bold px-3 py-1 rounded-full"
            style="background: #faf5ff; color: #7c3aed; border: 1px solid #ddd6fe">
            ACTION REQUIRED
        </span>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <a href="{{ route('kasir.transaksi.create') }}"
            class="relative rounded-2xl p-6 lg:p-7 text-white overflow-hidden group transition hover:scale-[1.01]"
            style="background: linear-gradient(135deg, #1e1035, #2d1b69, #4c1d95); box-shadow: 0 8px 24px rgba(91,33,182,0.25)">
            <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-2xl flex items-center justify-center text-xl lg:text-2xl mb-3 lg:mb-4"
                style="background: rgba(255,255,255,0.15)">➕</div>
            <h3 class="text-lg lg:text-xl font-black mb-2">Transaksi Penitipan Baru</h3>
            <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.7)">
                Mulai penerimaan barang baru untuk pelanggan. Cepat, aman, dan tercatat otomatis.
            </p>
            <div class="absolute -bottom-6 -right-6 w-28 h-28 rounded-full" style="background: rgba(255,255,255,0.05)"></div>
        </a>

        <a href="{{ route('kasir.pengambilan.index') }}"
            class="relative rounded-2xl p-6 lg:p-7 overflow-hidden group transition hover:scale-[1.01]"
            style="background: linear-gradient(135deg, #faf5ff, #ede9fe); border: 1.5px solid #ddd6fe; box-shadow: 0 4px 16px rgba(91,33,182,0.08)">
            <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-2xl flex items-center justify-center text-xl lg:text-2xl mb-3 lg:mb-4"
                style="background: rgba(91,33,182,0.1)">📦</div>
            <h3 class="text-lg lg:text-xl font-black mb-2" style="color: #1e1035">Pengambilan Barang</h3>
            <p class="text-sm leading-relaxed" style="color: #5b21b6">
                Verifikasi data dan serahkan kembali barang titipan pelanggan dengan satu klik.
            </p>
        </a>
    </div>
</div>

{{-- Aktivitas Terakhir --}}
<div class="anim-fade-up delay-7 bg-white rounded-2xl border border-gray-100 overflow-hidden"
    style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
    <div class="flex justify-between items-center px-5 py-4"
        style="border-bottom: 1px solid #f5f3ff">
        <div>
            <p class="font-black text-gray-800">Aktivitas Terakhir</p>
            <p class="text-xs text-gray-400 mt-0.5">Transaksi terbaru yang kamu proses</p>
        </div>
        <a href="{{ route('kasir.transaksi.index') }}"
            class="text-xs font-bold hover:underline flex-shrink-0" style="color: #7c3aed">
            Lihat Semua →
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm" style="min-width: 500px">
            <thead>
                <tr style="background: #fdfbff; border-bottom: 1px solid #ede9fe">
                    <th class="px-5 py-3 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">ID</th>
                    <th class="px-5 py-3 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Customer</th>
                    <th class="px-5 py-3 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Barang</th>
                    <th class="px-5 py-3 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Waktu</th>
                    <th class="px-5 py-3 text-left whitespace-nowrap" style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiTerbaru as $t)
                <tr class="table-row" style="border-top: 1px solid #f5f3ff">
                    <td class="px-5 py-4 whitespace-nowrap">
                        <a href="{{ route('kasir.transaksi.show', $t) }}"
                            class="font-bold hover:underline" style="color: #7c3aed; font-family: monospace; font-size: 12px">
                            #{{ substr($t->nomor_transaksi, -4) }}
                        </a>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                style="background: linear-gradient(135deg, #5b21b6, #a78bfa); font-size: 10px">
                                {{ strtoupper(substr($t->nama_penitip, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-gray-700 whitespace-nowrap">{{ Str::limit($t->nama_penitip, 12) }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-500 text-sm whitespace-nowrap">
                        @foreach($t->details->take(1) as $d)
                            {{ Str::limit($d->nama_barang_custom ?? $d->kategori->nama_kategori, 12) }}
                            <span class="text-xs text-gray-400">({{ $d->ukuran }})</span>
                        @endforeach
                    </td>
                    <td class="px-5 py-4 text-gray-400 text-xs whitespace-nowrap">
                        {{ $t->waktu_penitipan->format('H:i') }} WIB
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-bold"
                            style="background: {{ $t->status === 'dititip' ? '#faf5ff' : '#f0fdf4' }};
                                   color: {{ $t->status === 'dititip' ? '#7c3aed' : '#15803d' }}">
                            {{ $t->status === 'dititip' ? 'DITITIPKAN' : 'DIAMBIL' }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <a href="{{ route('kasir.transaksi.show', $t) }}"
                            class="text-gray-300 hover:text-purple-400 text-lg transition">⋯</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl"
                                style="background: #faf5ff">📋</div>
                            <p class="text-gray-400 font-medium text-sm">Belum ada transaksi hari ini.</p>
                            <a href="{{ route('kasir.transaksi.create') }}"
                                class="text-xs font-bold hover:underline" style="color: #7c3aed">
                                Buat transaksi pertama →
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection