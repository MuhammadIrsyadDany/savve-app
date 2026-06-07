@extends('layouts.kasir')
@section('title', 'Pilih Event')

@section('content')

    <div class="anim-fade-up delay-1 mb-6">
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #7c3aed">Setup</p>
        <h1 class="text-xl lg:text-2xl font-black text-gray-900">Pilih Event</h1>
        <p class="text-gray-400 text-sm mt-1">Pilih event aktif sebelum memulai transaksi penitipan.</p>
    </div>

    {{-- Event Aktif Saat Ini --}}
    @if ($eventAktif)
        <div class="anim-fade-up delay-2 rounded-2xl p-5 mb-5 text-white"
            style="background: linear-gradient(135deg, #1e1035, #2d1b69); box-shadow: 0 8px 24px rgba(91,33,182,0.2)">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #c4b5fd">
                        Event Aktif Saat Ini
                    </p>
                    <p class="text-xl font-black">{{ $eventAktif->nama_event }}</p>
                    <p class="text-sm mt-1" style="color: #c4b5fd">
                        Kode: <span class="font-mono font-bold">{{ $eventAktif->kode_event }}</span>
                        · {{ $eventAktif->tanggal_mulai->format('d M') }} –
                        {{ $eventAktif->tanggal_selesai->format('d M Y') }}
                    </p>
                </div>
                <form action="{{ route('kasir.event.ganti') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 rounded-xl font-bold text-sm transition"
                        style="background: rgba(255,255,255,0.15); color: white"
                        onclick="return confirm('Ganti event? Kamu perlu memilih event baru.')">
                        🔄 Ganti Event
                    </button>
                </form>
            </div>
        </div>

        <div class="anim-fade-up delay-3 flex gap-3 mb-6">
            <a href="{{ route('kasir.dashboard') }}"
                class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                style="background: linear-gradient(135deg, #5b21b6, #7c3aed)">
                ✅ Lanjutkan dengan Event Ini
            </a>
        </div>

        <div class="flex items-center gap-3 mb-5">
            <div class="flex-1 h-px bg-gray-200"></div>
            <p class="text-xs font-semibold text-gray-400">atau pilih event lain</p>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>
    @endif

    {{-- Daftar Event Aktif --}}
    @if ($events->count() > 0)
        <div class="anim-fade-up delay-3 space-y-3">
            @foreach ($events as $event)
                <form action="{{ route('kasir.event.pilih') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <button type="submit"
                        class="w-full text-left rounded-2xl border transition hover:shadow-md {{ session('kasir_event_id') == $event->id ? 'ring-2' : '' }}"
                        style="background: white; border-color: {{ session('kasir_event_id') == $event->id ? '#7c3aed' : '#e2e8f0' }};
                    box-shadow: 0 2px 8px rgba(0,0,0,0.04)">
                        <div class="flex items-center justify-between p-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                                    style="background: {{ session('kasir_event_id') == $event->id ? 'linear-gradient(135deg, #5b21b6, #7c3aed)' : '#faf5ff' }}">
                                    <span class="text-lg">🎪</span>
                                </div>
                                <div>
                                    <p class="font-black text-gray-800">{{ $event->nama_event }}</p>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-xs font-bold font-mono px-2 py-0.5 rounded-lg"
                                            style="background: #eff6ff; color: #1d4ed8">
                                            {{ $event->kode_event ?? 'NO-CODE' }}
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            {{ $event->tanggal_mulai->format('d M') }} –
                                            {{ $event->tanggal_selesai->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if (session('kasir_event_id') == $event->id)
                                    <span class="text-xs font-bold px-3 py-1 rounded-full"
                                        style="background: #faf5ff; color: #7c3aed">✓ Aktif</span>
                                @else
                                    <span class="text-xs font-bold px-3 py-1 rounded-full text-white"
                                        style="background: linear-gradient(135deg, #5b21b6, #7c3aed)">Pilih →</span>
                                @endif
                            </div>
                        </div>

                        {{-- Tarif --}}
                        @if ($event->tarifs->count() > 0)
                            <div class="px-5 pb-4 flex gap-2 flex-wrap">
                                @foreach ($event->tarifs->sortBy('ukuran') as $tarif)
                                    <span class="text-xs px-2 py-1 rounded-lg font-semibold"
                                        style="background: #f8faff; color: #64748b; border: 1px solid #e2e8f0">
                                        {{ $tarif->ukuran }}: Rp {{ number_format($tarif->harga, 0, ',', '.') }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </button>
                </form>
            @endforeach
        </div>
    @else
        <div class="anim-fade-up delay-3 bg-white rounded-2xl border border-gray-100 p-12 text-center"
            style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4"
                style="background: #faf5ff">🎪</div>
            <p class="font-black text-gray-700 text-lg mb-1">Tidak Ada Event Aktif</p>
            <p class="text-gray-400 text-sm">Hubungi admin untuk mengaktifkan event.</p>
        </div>
    @endif

@endsection
