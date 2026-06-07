@extends('layouts.kasir')
@section('title', 'Pengambilan Barang')

@section('content')

    {{-- Header --}}
    <div class="anim-fade-up delay-1 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #7c3aed">Transaksi</p>
            <h1 class="text-xl lg:text-2xl font-black text-gray-900">Pengambilan Barang</h1>
            <p class="text-gray-400 text-sm mt-1">Scan QR code nota atau cari nama penitip untuk konfirmasi pengambilan.</p>
        </div>
    </div>

    {{-- Tab Container --}}
    <div class="anim-fade-up delay-2 bg-white rounded-2xl border border-gray-100 overflow-hidden mb-5"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">

        {{-- Tab Header --}}
        <div class="flex" style="border-bottom: 2px solid #f5f3ff">
            <button onclick="switchMode('qr')" id="tab-qr"
                class="flex-1 flex items-center justify-center gap-2 py-4 text-sm font-bold transition pengambilan-tab active-pengambilan-tab">
                <span>📱</span> Scan QR Code
            </button>
            <button onclick="switchMode('nama')" id="tab-nama"
                class="flex-1 flex items-center justify-center gap-2 py-4 text-sm font-bold transition pengambilan-tab">
                <span>🔍</span> Cari Nama
            </button>
        </div>

        {{-- ── Panel QR ── --}}
        <div id="panel-qr" class="p-5 lg:p-6">

            <p class="text-sm text-center mb-5" style="color: #94a3b8">
                Arahkan kamera ke QR code pada nota penitipan
            </p>

            {{-- Scanner Area --}}
            <div class="flex justify-center mb-5">
                <div class="relative rounded-2xl overflow-hidden flex-shrink-0"
                    style="background: #0a0a14; width: 280px; height: 280px">

                    <video id="qr-video" autoplay playsinline
                        style="position:absolute;inset:0;width:100%;height:100%;
                           object-fit:cover;transform:scaleX(1);display:block"></video>

                    {{-- Vignette overlay --}}
                    <div class="absolute inset-0 pointer-events-none"
                        style="background: radial-gradient(ellipse at center, transparent 42%, rgba(0,0,0,0.7) 72%)"></div>

                    {{-- Scan frame --}}
                    <div class="absolute pointer-events-none"
                        style="top:50%;left:50%;transform:translate(-50%,-50%);width:58%;height:58%">
                        <div
                            style="position:absolute;top:0;left:0;width:30px;height:30px;
                        border-top:3px solid #a78bfa;border-left:3px solid #a78bfa;border-radius:6px 0 0 0">
                        </div>
                        <div
                            style="position:absolute;top:0;right:0;width:30px;height:30px;
                        border-top:3px solid #a78bfa;border-right:3px solid #a78bfa;border-radius:0 6px 0 0">
                        </div>
                        <div
                            style="position:absolute;bottom:0;left:0;width:30px;height:30px;
                        border-bottom:3px solid #a78bfa;border-left:3px solid #a78bfa;border-radius:0 0 0 6px">
                        </div>
                        <div
                            style="position:absolute;bottom:0;right:0;width:30px;height:30px;
                        border-bottom:3px solid #a78bfa;border-right:3px solid #a78bfa;border-radius:0 0 6px 0">
                        </div>
                        <div id="scan-line"
                            style="position:absolute;left:0;right:0;height:2px;
                        background:linear-gradient(90deg,transparent,#c4b5fd,#a78bfa,#c4b5fd,transparent);
                        box-shadow:0 0 10px 2px rgba(167,139,250,0.6);
                        animation:scanLine 1.8s ease-in-out infinite">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div id="qr-status" class="absolute bottom-3 left-0 right-0 flex justify-center pointer-events-none">
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold"
                            style="background:rgba(0,0,0,0.75);color:#c4b5fd;backdrop-filter:blur(6px)">
                            📷 Kamera belum aktif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Tombol Toggle --}}
            <div class="max-w-xs mx-auto">
                <button onclick="toggleKameraQr()" id="btn-toggle-kamera"
                    class="w-full py-3 rounded-xl font-bold text-sm transition text-white flex items-center justify-center gap-2"
                    style="background:linear-gradient(135deg,#5b21b6,#7c3aed);box-shadow:0 4px 12px rgba(91,33,182,0.25)">
                    📷 Aktifkan Kamera
                </button>
            </div>
        </div>

        {{-- ── Panel Nama ── --}}
        <div id="panel-nama" class="p-5 lg:p-6 hidden">
            <form action="{{ route('kasir.pengambilan.cari') }}" method="POST">
                @csrf
                <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">
                    Nama Penitip
                </label>
                <div class="flex gap-3">
                    <input type="text" name="nama_penitip" value="{{ old('nama_penitip') }}" autofocus
                        class="flex-1 rounded-xl px-4 py-3 text-sm transition"
                        style="background:#faf5ff;border:1.5px solid #ede9fe;color:#374151"
                        placeholder="Contoh: Budi Santoso"
                        onfocus="this.style.borderColor='#a78bfa';this.style.boxShadow='0 0 0 3px rgba(167,139,250,0.1)'"
                        onblur="this.style.borderColor='#ede9fe';this.style.boxShadow='none'">
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-3 rounded-xl text-white font-bold text-sm transition hover:opacity-90 flex-shrink-0"
                        style="background:linear-gradient(135deg,#5b21b6,#7c3aed);box-shadow:0 4px 12px rgba(91,33,182,0.2)">
                        🔍 Cari
                    </button>
                </div>
                @error('nama_penitip')
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">⚠ {{ $message }}</p>
                @enderror
            </form>

            {{-- Info cadangan --}}
            <div class="mt-4 flex items-start gap-2.5 px-4 py-3 rounded-xl"
                style="background:#faf5ff;border:1px solid #ede9fe">
                <span class="text-sm flex-shrink-0 mt-0.5">💡</span>
                <p class="text-xs" style="color:#7c3aed">
                    Gunakan fitur ini sebagai cadangan apabila nota QR code hilang atau tidak dapat dipindai.
                </p>
            </div>
        </div>
    </div>

    {{-- Hasil QR --}}
    <div id="hasil-qr" class="hidden"></div>

    {{-- Hasil Pencarian Nama --}}
    @isset($transaksis)
        @if ($transaksis->count() > 0)
            <div class="anim-fade-up delay-3 mb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background: #a78bfa"></span>
                <p class="text-sm font-bold" style="color: #7c3aed">
                    {{ $transaksis->count() }} transaksi ditemukan untuk "{{ old('nama_penitip') }}"
                </p>
            </div>

            <div class="space-y-4">
                @foreach ($transaksis as $transaksi)
                    <div class="anim-fade-up delay-4 bg-white rounded-2xl border border-gray-100 overflow-hidden"
                        style="box-shadow: 0 2px 16px rgba(0,0,0,0.06)">

                        {{-- Card Header --}}
                        <div class="flex justify-between items-center px-5 py-4"
                            style="background: linear-gradient(135deg, #1e1035, #2d1b69)">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-sm text-white flex-shrink-0"
                                    style="background: rgba(167,139,250,0.3)">
                                    {{ strtoupper(substr($transaksi->nama_penitip, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-white font-black text-sm leading-none">{{ $transaksi->nama_penitip }}</p>
                                    <p class="text-xs mt-0.5" style="color: #c4b5fd">{{ $transaksi->no_whatsapp }}</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold px-3 py-1.5 rounded-full font-mono flex-shrink-0"
                                style="background:rgba(167,139,250,0.2);color:#c4b5fd">
                                {{ $transaksi->nomor_transaksi }}
                            </span>
                        </div>

                        <div class="p-5">

                            {{-- Info Grid --}}
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                                <div class="rounded-xl p-3" style="background:#faf5ff;border:1px solid #ede9fe">
                                    <p class="text-xs font-semibold uppercase tracking-wider mb-1"
                                        style="color:#94a3b8;font-size:9px">Event</p>
                                    <p class="font-bold text-gray-800 text-sm leading-tight">
                                        {{ Str::limit($transaksi->event->nama_event, 20) }}</p>
                                </div>
                                <div class="rounded-xl p-3" style="background:#faf5ff;border:1px solid #ede9fe">
                                    <p class="text-xs font-semibold uppercase tracking-wider mb-1"
                                        style="color:#94a3b8;font-size:9px">Total Barang</p>
                                    <p class="font-black text-gray-800 text-lg">{{ $transaksi->details->sum('jumlah') }} <span
                                            class="text-xs font-normal text-gray-400">unit</span></p>
                                </div>
                                <div class="rounded-xl p-3" style="background:#faf5ff;border:1px solid #ede9fe">
                                    <p class="text-xs font-semibold uppercase tracking-wider mb-1"
                                        style="color:#94a3b8;font-size:9px">Waktu Titip</p>
                                    <p class="font-bold text-gray-700 text-xs">
                                        {{ $transaksi->waktu_penitipan->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $transaksi->waktu_penitipan->format('H:i') }} WIB</p>
                                </div>
                                <div class="rounded-xl p-3" style="background:#faf5ff;border:1px solid #ede9fe">
                                    <p class="text-xs font-semibold uppercase tracking-wider mb-1"
                                        style="color:#94a3b8;font-size:9px">Status</p>
                                    @if ($transaksi->status === 'terlambat')
                                        <p class="flex items-center gap-1.5 font-bold text-red-600 text-sm">
                                            <span class="w-2 h-2 rounded-full animate-pulse bg-red-500 flex-shrink-0"></span>
                                            Terlambat
                                        </p>
                                    @else
                                        <p class="flex items-center gap-1.5 font-bold text-sm" style="color:#7c3aed">
                                            <span class="w-2 h-2 rounded-full animate-pulse flex-shrink-0"
                                                style="background:#a78bfa"></span>
                                            Dititipkan
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Daftar Barang --}}
                            <div class="mb-4">
                                <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color:#94a3b8">Daftar Barang
                                </p>
                                <div class="space-y-2">
                                    @foreach ($transaksi->details as $d)
                                        <div class="flex items-center justify-between px-3 py-2 rounded-lg"
                                            style="background:#f8faff;border:1px solid #ede9fe">
                                            <span class="text-sm font-semibold text-gray-700">
                                                {{ $d->nama_barang_custom ?? $d->kategori->nama_kategori }}
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded-lg text-xs font-bold"
                                                    style="background:#ede9fe;color:#7c3aed">{{ $d->ukuran }}</span>
                                                <span class="text-xs text-gray-400">x{{ $d->jumlah }}</span>
                                                <span class="text-xs font-bold text-gray-700">
                                                    Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Foto Penitipan --}}
                            @if ($transaksi->foto_penitipan)
                                <div class="mb-4">
                                    <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color:#94a3b8">
                                        📷 Foto Barang Saat Penitipan
                                    </p>
                                    <img src="{{ asset('storage/' . $transaksi->foto_penitipan) }}" alt="Foto Barang"
                                        class="w-full max-h-48 object-cover rounded-xl cursor-pointer transition hover:opacity-90"
                                        style="border:1.5px solid #ede9fe" onclick="bukaFotoModal(this.src)">
                                </div>
                            @endif

                            {{-- Warning Terlambat --}}
                            @if ($transaksi->status === 'terlambat')
                                <div class="flex items-start gap-3 px-4 py-3 rounded-xl mb-4"
                                    style="background:#fff5f5;border:1.5px solid #fecaca">
                                    <span class="text-red-500 flex-shrink-0 mt-0.5">⚠️</span>
                                    <div>
                                        <p class="text-red-700 font-bold text-sm">Pengambilan Terlambat</p>
                                        <p class="text-red-400 text-xs mt-0.5">
                                            Event sudah berakhir. Konfirmasi tetap bisa dilakukan dengan catatan keterlambatan.
                                        </p>
                                    </div>
                                </div>
                            @endif

                            {{-- Upload Foto Pengambilan --}}
                            <div class="mb-5">
                                <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color:#64748b">
                                    Foto Pengambilan
                                    <span class="font-normal normal-case ml-1" style="color:#94a3b8">(opsional)</span>
                                </p>

                                <div id="foto-ambil-preview-{{ $transaksi->id }}" class="hidden mb-3">
                                    <div class="relative">
                                        <img id="foto-ambil-img-{{ $transaksi->id }}" src="" alt="Preview"
                                            class="w-full max-h-40 object-cover rounded-xl"
                                            style="border:1.5px solid #ddd6fe">
                                        <button type="button" onclick="hapusFotoAmbil({{ $transaksi->id }})"
                                            class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center rounded-full text-white text-xs font-bold"
                                            style="background:rgba(0,0,0,0.6)">✕</button>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <button type="button" onclick="bukaKameraAmbil({{ $transaksi->id }})"
                                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm transition hover:opacity-90"
                                        style="background:#faf5ff;color:#7c3aed;border:1.5px solid #ede9fe">
                                        📷 Ambil Foto
                                    </button>
                                    <label
                                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm cursor-pointer transition hover:opacity-90"
                                        style="background:#f8faff;color:#1a3a6b;border:1.5px solid #e2e8f0">
                                        🖼️ Galeri
                                        <input type="file" accept="image/*" class="hidden"
                                            onchange="pilihFotoAmbil(this, {{ $transaksi->id }})">
                                    </label>
                                </div>
                            </div>

                            {{-- Tombol Konfirmasi --}}
                            <form action="{{ route('kasir.pengambilan.konfirmasi', $transaksi) }}" method="POST"
                                onsubmit="return confirm('{{ $transaksi->status === 'terlambat' ? 'Barang ini terlambat diambil. Tetap konfirmasi pengambilan?' : 'Konfirmasi pengambilan barang atas nama ' . $transaksi->nama_penitip . '?' }}')">
                                @csrf
                                <input type="hidden" name="foto_pengambilan" id="foto-ambil-input-{{ $transaksi->id }}">
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl text-white font-black text-sm transition hover:opacity-90"
                                    style="background:{{ $transaksi->status === 'terlambat' ? 'linear-gradient(135deg,#dc2626,#ef4444)' : 'linear-gradient(135deg,#5b21b6,#7c3aed)' }};
                           box-shadow:{{ $transaksi->status === 'terlambat' ? '0 4px 12px rgba(220,38,38,0.25)' : '0 4px 12px rgba(91,33,182,0.25)' }}">
                                    {{ $transaksi->status === 'terlambat' ? '⚠️ Konfirmasi Pengambilan (Terlambat)' : '🛡️ Konfirmasi Pengambilan' }}
                                </button>
                            </form>
                        </div>

                        {{-- Modal Kamera Pengambilan --}}
                        <div id="modal-kamera-ambil-{{ $transaksi->id }}"
                            class="hidden fixed inset-0 z-50 flex items-center justify-center"
                            style="background:rgba(0,0,0,0.85)">
                            <div class="bg-white rounded-2xl overflow-hidden w-full max-w-sm mx-4">
                                <div class="flex justify-between items-center px-4 py-3"
                                    style="border-bottom:1px solid #f1f5f9">
                                    <p class="font-black text-gray-800 text-sm">📷 Foto Pengambilan Barang</p>
                                    <button onclick="tutupKameraAmbil({{ $transaksi->id }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg"
                                        style="background:#f1f5f9;color:#6b7280">✕</button>
                                </div>
                                <div class="p-4">
                                    <div class="rounded-xl overflow-hidden mb-3"
                                        style="background:#0f0f1a;width:100%;height:260px;position:relative">
                                        <video id="video-ambil-{{ $transaksi->id }}" autoplay playsinline
                                            style="width:100%;height:100%;object-fit:cover;transform:scaleX(1);display:block"></video>
                                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                                            <div style="width:75%;height:75%;position:relative">
                                                <div
                                                    style="position:absolute;top:0;left:0;width:24px;height:24px;border-top:2.5px solid #a78bfa;border-left:2.5px solid #a78bfa;border-radius:4px 0 0 0">
                                                </div>
                                                <div
                                                    style="position:absolute;top:0;right:0;width:24px;height:24px;border-top:2.5px solid #a78bfa;border-right:2.5px solid #a78bfa;border-radius:0 4px 0 0">
                                                </div>
                                                <div
                                                    style="position:absolute;bottom:0;left:0;width:24px;height:24px;border-bottom:2.5px solid #a78bfa;border-left:2.5px solid #a78bfa;border-radius:0 0 0 4px">
                                                </div>
                                                <div
                                                    style="position:absolute;bottom:0;right:0;width:24px;height:24px;border-bottom:2.5px solid #a78bfa;border-right:2.5px solid #a78bfa;border-radius:0 0 4px 0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <canvas id="canvas-ambil-{{ $transaksi->id }}" class="hidden"></canvas>
                                    <button type="button" onclick="jepretFotoAmbil({{ $transaksi->id }})"
                                        class="w-full py-3 rounded-xl text-white font-bold text-sm"
                                        style="background:linear-gradient(135deg,#5b21b6,#7c3aed);box-shadow:0 4px 12px rgba(91,33,182,0.2)">
                                        📸 Jepret Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <button onclick="window.location.href='{{ route('kasir.pengambilan.index') }}'"
                class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl font-bold text-sm transition mt-4"
                style="background:white;border:1.5px solid #ede9fe;color:#7c3aed">
                ← Cari Ulang
            </button>
        @elseif(session('error'))
            <div class="anim-fade-up delay-3 bg-white rounded-2xl border border-gray-100 p-10 text-center"
                style="box-shadow:0 2px 12px rgba(0,0,0,0.04)">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4"
                    style="background:#fff5f5">😕</div>
                <p class="font-black text-gray-700 text-lg mb-1">Tidak Ditemukan</p>
                <p class="text-gray-400 text-sm">{{ session('error') }}</p>
                <button onclick="switchMode('nama')"
                    class="mt-4 px-6 py-2.5 rounded-xl font-bold text-sm text-white transition hover:opacity-90"
                    style="background:linear-gradient(135deg,#5b21b6,#7c3aed)">
                    🔍 Cari Lagi
                </button>
            </div>
        @endif
    @endisset

    {{-- Modal Foto Fullscreen --}}
    <div id="foto-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background:rgba(0,0,0,0.92)" onclick="this.classList.add('hidden')">
        <div class="relative max-w-2xl w-full">
            <img id="foto-modal-img" src="" alt="Foto"
                class="w-full rounded-2xl object-contain max-h-screen">
            <p class="text-center text-xs text-white mt-3 opacity-60">Klik di mana saja untuk menutup</p>
        </div>
    </div>

    <style>
        @keyframes scanLine {
            0% {
                top: 0;
                opacity: 1;
            }

            50% {
                top: calc(100% - 2px);
                opacity: 0.8;
            }

            100% {
                top: 0;
                opacity: 1;
            }
        }

        .pengambilan-tab {
            color: #94a3b8;
            border-bottom: 2px solid transparent;
        }

        .active-pengambilan-tab {
            color: #5b21b6;
            border-bottom: 2px solid #7c3aed;
            background: #faf5ff;
        }

        /* Fix kamera tidak mirror di semua device */
        #qr-video,
        video[autoplay] {
            transform: scaleX(1) !important;
            -webkit-transform: scaleX(1) !important;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsQR/1.4.0/jsQR.min.js"></script>
    <script>
        // ── Tab Switch ──
        function switchMode(mode) {
            ['qr', 'nama'].forEach(m => {
                document.getElementById('panel-' + m).classList.add('hidden');
                document.getElementById('tab-' + m).classList.remove('active-pengambilan-tab');
            });
            document.getElementById('panel-' + mode).classList.remove('hidden');
            document.getElementById('tab-' + mode).classList.add('active-pengambilan-tab');
            if (mode !== 'qr') stopQrScanner();
        }

        // ── QR Scanner ──
        let qrStream = null;
        let qrInterval = null;
        let isScanning = false;

        function setQrStatus(text, color) {
            document.getElementById('qr-status').innerHTML =
                `<span class="px-3 py-1.5 rounded-full text-xs font-bold"
            style="background:rgba(0,0,0,0.75);color:${color};backdrop-filter:blur(6px)">${text}</span>`;
        }

        async function toggleKameraQr() {
            const btn = document.getElementById('btn-toggle-kamera');
            if (qrStream) {
                stopQrScanner();
                btn.innerHTML = '📷 Aktifkan Kamera';
                btn.style.background = 'linear-gradient(135deg,#5b21b6,#7c3aed)';
                setQrStatus('📷 Kamera belum aktif', '#c4b5fd');
            } else {
                btn.innerHTML = '⏳ Memuat kamera...';
                btn.disabled = true;
                await startQrScanner();
                btn.disabled = false;
                btn.innerHTML = '⏹ Hentikan Kamera';
                btn.style.background = '#dc2626';
            }
        }

        async function startQrScanner() {
            const constraints = [
                // Prioritas 1: kamera belakang HP
                {
                    video: {
                        facingMode: {
                            exact: 'environment'
                        },
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                },
                // Prioritas 2: kamera belakang ideal
                {
                    video: {
                        facingMode: 'environment',
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                },
                // Prioritas 3: kamera apapun resolusi tinggi
                {
                    video: {
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                },
                // Fallback: kamera apapun
                {
                    video: true
                }
            ];

            for (const constraint of constraints) {
                try {
                    qrStream = await navigator.mediaDevices.getUserMedia(constraint);
                    break;
                } catch (e) {
                    continue;
                }
            }

            if (!qrStream) {
                alert('Tidak bisa mengakses kamera. Pastikan izin kamera diaktifkan di browser.');
                return;
            }

            const video = document.getElementById('qr-video');
            video.srcObject = qrStream;
            video.style.cssText =
                'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:scaleX(1) !important;-webkit-transform:scaleX(1) !important;display:block';

            await new Promise(resolve => {
                video.onloadedmetadata = () => {
                    video.play();
                    resolve();
                };
            });

            isScanning = true;
            setQrStatus('🔍 Scanning...', '#c4b5fd');

            // Gunakan requestAnimationFrame untuk scan lebih smooth
            requestAnimationFrame(scanLoop);
        }

        function stopQrScanner() {
            isScanning = false;
            if (qrStream) {
                qrStream.getTracks().forEach(t => t.stop());
                qrStream = null;
            }
            clearInterval(qrInterval);
        }

        function scanLoop() {
            if (!isScanning) return;

            try {
                scanQrFrame();
            } catch (e) {
                // silent
            }

            // Scan setiap 100ms untuk lebih responsif
            setTimeout(() => requestAnimationFrame(scanLoop), 100);
        }

        function scanQrFrame() {
            const video = document.getElementById('qr-video');
            if (!video || video.readyState < 2 || !video.videoWidth || !video.videoHeight) return;

            const w = video.videoWidth;
            const h = video.videoHeight;

            // Canvas utama — full frame
            const canvas = document.createElement('canvas');
            canvas.width = w;
            canvas.height = h;
            const ctx = canvas.getContext('2d', {
                willReadFrequently: true
            });

            // PENTING: jangan flip, gambar apa adanya dari kamera
            ctx.drawImage(video, 0, 0, w, h);

            // Coba scan full frame dengan semua mode
            for (const attempt of ['dontInvert', 'onlyInvert', 'attemptBoth']) {
                const imageData = ctx.getImageData(0, 0, w, h);
                const code = jsQR(imageData.data, w, h, {
                    inversionAttempts: attempt
                });
                if (code?.data?.trim()) {
                    handleQrFound(code.data.trim());
                    return;
                }
            }

            // Coba scan crop area tengah (70%)
            const size = Math.min(w, h) * 0.7;
            const cx = (w - size) / 2;
            const cy = (h - size) / 2;

            const c2 = document.createElement('canvas');
            c2.width = size;
            c2.height = size;
            c2.getContext('2d', {
                    willReadFrequently: true
                })
                .drawImage(video, cx, cy, size, size, 0, 0, size, size);

            for (const attempt of ['dontInvert', 'onlyInvert', 'attemptBoth']) {
                const imageData = c2.getContext('2d').getImageData(0, 0, size, size);
                const code = jsQR(imageData.data, size, size, {
                    inversionAttempts: attempt
                });
                if (code?.data?.trim()) {
                    handleQrFound(code.data.trim());
                    return;
                }
            }

            // Coba dengan brightness boost (grayscale enhancement)
            const c3 = document.createElement('canvas');
            c3.width = w;
            c3.height = h;
            const ctx3 = c3.getContext('2d', {
                willReadFrequently: true
            });
            ctx3.filter = 'contrast(1.5) brightness(1.1)';
            ctx3.drawImage(video, 0, 0, w, h);
            ctx3.filter = 'none';

            const imageData3 = ctx3.getImageData(0, 0, w, h);
            const code3 = jsQR(imageData3.data, w, h, {
                inversionAttempts: 'attemptBoth'
            });
            if (code3?.data?.trim()) {
                handleQrFound(code3.data.trim());
            }
        }

        function handleQrFound(value) {
            console.log('✅ QR Found:', value);
            isScanning = false;
            stopQrScanner();
            setQrStatus('✅ QR Terdeteksi!', '#4ade80');
            prosesQr(value);
        }

        async function prosesQr(namaPenitip) {
            setQrStatus('⏳ Memproses...', '#fbbf24');

            try {
                const res = await fetch('{{ route('kasir.pengambilan.scan-qr') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nama_penitip: namaPenitip
                    })
                });

                if (!res.ok) throw new Error('Server error: ' + res.status);

                const data = await res.json();
                const hasil = document.getElementById('hasil-qr');

                if (!data.found) {
                    setQrStatus('❌ Tidak ditemukan', '#f87171');
                    hasil.innerHTML = `
                <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center mb-4"
                    style="box-shadow:0 2px 12px rgba(0,0,0,0.04)">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4"
                        style="background:#fff5f5">😕</div>
                    <p class="font-black text-gray-700 text-lg mb-1">Penitip Tidak Ditemukan</p>
                    <p class="text-gray-400 text-sm mb-1">
                        Nama: <span class="font-bold text-gray-600">${namaPenitip}</span>
                    </p>
                    <p class="text-gray-400 text-sm mb-4">
                        Mungkin sudah diambil atau tidak ada transaksi aktif.
                    </p>
                    <button onclick="resetQr()"
                        class="px-6 py-2.5 rounded-xl text-white font-bold text-sm"
                        style="background:linear-gradient(135deg,#5b21b6,#7c3aed)">
                        🔄 Scan Ulang
                    </button>
                </div>`;
                    hasil.classList.remove('hidden');
                    return;
                }

                const t = data.transaksi;
                const isTerlambat = t.status === 'terlambat';

                hasil.innerHTML = `
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden mb-4"
                style="box-shadow:0 2px 16px rgba(0,0,0,0.06)">

                <div class="flex justify-between items-center px-5 py-4"
                    style="background:linear-gradient(135deg,#1e1035,#2d1b69)">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-sm text-white"
                            style="background:rgba(167,139,250,0.3)">
                            ${t.nama_penitip.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <p class="text-white font-black text-sm leading-none">${t.nama_penitip}</p>
                            <p class="text-xs mt-0.5" style="color:#c4b5fd">✅ QR Terdeteksi</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full font-mono flex-shrink-0"
                        style="background:rgba(167,139,250,0.2);color:#c4b5fd">
                        ${t.nomor}
                    </span>
                </div>

                <div class="p-5">

                    ${t.total_transaksi_aktif > 1 ? `
                                    <div class="flex items-start gap-3 px-4 py-3 rounded-xl mb-4"
                                        style="background:#fffbeb;border:1.5px solid #fde68a">
                                        <span style="flex-shrink:0">⚠️</span>
                                        <div>
                                            <p class="font-bold text-sm" style="color:#92400e">
                                                ${t.total_transaksi_aktif} transaksi aktif ditemukan
                                            </p>
                                            <p class="text-xs mt-0.5" style="color:#b45309">
                                                Menampilkan transaksi terbaru. Gunakan Cari Nama untuk melihat semua.
                                            </p>
                                        </div>
                                    </div>` : ''}

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="rounded-xl p-3" style="background:#faf5ff;border:1px solid #ede9fe">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1"
                                style="color:#94a3b8;font-size:9px">Event</p>
                            <p class="font-bold text-gray-800 text-sm">${t.event}</p>
                        </div>
                        <div class="rounded-xl p-3" style="background:#faf5ff;border:1px solid #ede9fe">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1"
                                style="color:#94a3b8;font-size:9px">Total Barang</p>
                            <p class="font-black text-gray-800 text-lg">
                                ${t.total_barang}
                                <span style="font-size:11px;font-weight:400;color:#94a3b8">unit</span>
                            </p>
                        </div>
                        <div class="rounded-xl p-3" style="background:#faf5ff;border:1px solid #ede9fe">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1"
                                style="color:#94a3b8;font-size:9px">Waktu Titip</p>
                            <p class="font-bold text-gray-700 text-xs">${t.waktu_penitipan}</p>
                        </div>
                        <div class="rounded-xl p-3" style="background:#faf5ff;border:1px solid #ede9fe">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1"
                                style="color:#94a3b8;font-size:9px">Status</p>
                            <p class="font-bold text-sm flex items-center gap-1.5"
                                style="color:${isTerlambat ? '#dc2626' : '#7c3aed'}">
                                <span style="width:8px;height:8px;border-radius:50%;
                                    background:${isTerlambat ? '#ef4444' : '#a78bfa'};
                                    display:inline-block"></span>
                                ${isTerlambat ? 'Terlambat' : 'Dititipkan'}
                            </p>
                        </div>
                    </div>

                    ${t.details && t.details.length > 0 ? `
                                    <div class="mb-4">
                                        <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color:#94a3b8">
                                            Daftar Barang
                                        </p>
                                        <div class="space-y-2">
                                            ${t.details.map(d => `
                            <div class="flex items-center justify-between px-3 py-2 rounded-lg"
                                style="background:#f8faff;border:1px solid #ede9fe">
                                <span class="text-sm font-semibold text-gray-700">${d.nama}</span>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-lg text-xs font-bold"
                                        style="background:#ede9fe;color:#7c3aed">${d.ukuran}</span>
                                    <span class="text-xs text-gray-400">x${d.jumlah}</span>
                                    <span class="text-xs font-bold text-gray-700">${d.subtotal}</span>
                                </div>
                            </div>`).join('')}
                                        </div>
                                    </div>` : ''}

                    ${t.foto_penitipan ? `
                                    <div class="mb-4">
                                        <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color:#94a3b8">
                                            📷 Foto Barang Saat Penitipan
                                        </p>
                                        <img src="${t.foto_penitipan}" alt="Foto"
                                            class="w-full max-h-48 object-cover rounded-xl cursor-pointer"
                                            style="border:1.5px solid #ede9fe"
                                            onclick="bukaFotoModal(this.src)">
                                    </div>` : ''}

                    ${isTerlambat ? `
                                    <div class="flex items-start gap-3 px-4 py-3 rounded-xl mb-4"
                                        style="background:#fff5f5;border:1.5px solid #fecaca">
                                        <span style="color:#ef4444;flex-shrink:0">⚠️</span>
                                        <div>
                                            <p class="font-bold text-sm" style="color:#dc2626">Pengambilan Terlambat</p>
                                            <p class="text-xs mt-0.5" style="color:#f87171">Event sudah berakhir.</p>
                                        </div>
                                    </div>` : ''}

                    <div class="mb-5">
                        <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color:#64748b">
                            Foto Pengambilan
                            <span style="font-weight:400;text-transform:none;color:#94a3b8">(opsional)</span>
                        </p>
                        <div id="foto-qr-preview" class="hidden mb-3">
                            <div style="position:relative">
                                <img id="foto-qr-img" src=""
                                    class="w-full max-h-40 object-cover rounded-xl"
                                    style="border:1.5px solid #ddd6fe">
                                <button onclick="hapusFotoQr()" type="button"
                                    class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center rounded-full text-white text-xs font-bold"
                                    style="background:rgba(0,0,0,0.6)">✕</button>
                            </div>
                        </div>
                        <button onclick="bukaKameraQrAmbil()" type="button"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm"
                            style="background:#faf5ff;color:#7c3aed;border:1.5px solid #ede9fe">
                            📷 Ambil Foto
                        </button>
                        <input type="hidden" id="foto-qr-input">
                    </div>

                    <button onclick="konfirmasiQr(${t.id}, ${isTerlambat})"
                        class="w-full py-4 rounded-2xl text-white font-black text-sm transition hover:opacity-90 flex items-center justify-center gap-2 mb-2"
                        style="background:${isTerlambat
                            ? 'linear-gradient(135deg,#dc2626,#ef4444)'
                            : 'linear-gradient(135deg,#5b21b6,#7c3aed)'};
                            box-shadow:${isTerlambat
                            ? '0 4px 12px rgba(220,38,38,0.25)'
                            : '0 4px 12px rgba(91,33,182,0.25)'}">
                        ${isTerlambat ? '⚠️ Konfirmasi (Terlambat)' : '🛡️ Konfirmasi Pengambilan'}
                    </button>
                    <button onclick="resetQr()"
                        class="w-full py-3 rounded-xl font-bold text-sm transition"
                        style="background:white;border:1.5px solid #ede9fe;color:#7c3aed">
                        🔄 Scan Ulang
                    </button>
                </div>
            </div>`;

                hasil.classList.remove('hidden');
                hasil.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

            } catch (err) {
                console.error('Error prosesQr:', err);
                setQrStatus('❌ Error: ' + err.message, '#f87171');
            }
        }

        function resetQr() {
            isScanning = false;
            const hasil = document.getElementById('hasil-qr');
            hasil.innerHTML = '';
            hasil.classList.add('hidden');
            const btn = document.getElementById('btn-toggle-kamera');
            btn.innerHTML = '📷 Aktifkan Kamera';
            btn.style.background = 'linear-gradient(135deg,#5b21b6,#7c3aed)';
            setQrStatus('📷 Kamera belum aktif', '#c4b5fd');
        }

        async function konfirmasiQr(transaksiId, isTerlambat) {
            const msg = isTerlambat ?
                'Barang ini terlambat diambil. Tetap konfirmasi pengambilan?' :
                'Konfirmasi pengambilan barang ini?';
            if (!confirm(msg)) return;

            const fotoInput = document.getElementById('foto-qr-input');

            try {
                await fetch(`/kasir/pengambilan/konfirmasi/${transaksiId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: new URLSearchParams({
                        '_token': '{{ csrf_token() }}',
                        'foto_pengambilan': fotoInput?.value || ''
                    })
                });
                window.location.href = '{{ route('kasir.pengambilan.index') }}';
            } catch (err) {
                alert('Gagal konfirmasi: ' + err.message);
            }
        }

        // ── Kamera QR Ambil ──
        let streamQrAmbil = null;

        function bukaKameraQrAmbil() {
            const modal = document.createElement('div');
            modal.id = 'modal-qr-ambil';
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center';
            modal.style.background = 'rgba(0,0,0,0.85)';
            modal.innerHTML = `
        <div class="bg-white rounded-2xl overflow-hidden w-full max-w-sm mx-4">
            <div class="flex justify-between items-center px-4 py-3"
                style="border-bottom:1px solid #f1f5f9">
                <p class="font-black text-gray-800 text-sm">📷 Foto Pengambilan</p>
                <button onclick="tutupModalQrAmbil()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg"
                    style="background:#f1f5f9;color:#6b7280">✕</button>
            </div>
            <div class="p-4">
                <div class="rounded-xl overflow-hidden mb-3"
                    style="background:#0f0f1a;width:100%;height:260px;position:relative">
                    <video id="video-qr-ambil" autoplay playsinline
                        style="width:100%;height:100%;object-fit:cover;transform:scaleX(1);display:block"></video>
                    <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                        <div style="width:75%;height:75%;position:relative">
                            <div style="position:absolute;top:0;left:0;width:24px;height:24px;border-top:2.5px solid #a78bfa;border-left:2.5px solid #a78bfa;border-radius:4px 0 0 0"></div>
                            <div style="position:absolute;top:0;right:0;width:24px;height:24px;border-top:2.5px solid #a78bfa;border-right:2.5px solid #a78bfa;border-radius:0 4px 0 0"></div>
                            <div style="position:absolute;bottom:0;left:0;width:24px;height:24px;border-bottom:2.5px solid #a78bfa;border-left:2.5px solid #a78bfa;border-radius:0 0 0 4px"></div>
                            <div style="position:absolute;bottom:0;right:0;width:24px;height:24px;border-bottom:2.5px solid #a78bfa;border-right:2.5px solid #a78bfa;border-radius:0 0 4px 0"></div>
                        </div>
                    </div>
                </div>
                <canvas id="canvas-qr-ambil" class="hidden"></canvas>
                <button onclick="jepretQrAmbil()" type="button"
                    class="w-full py-3 rounded-xl text-white font-bold text-sm"
                    style="background:linear-gradient(135deg,#5b21b6,#7c3aed)">
                    📸 Jepret Foto
                </button>
            </div>
        </div>`;
            document.body.appendChild(modal);

            const tryCamera = async () => {
                for (const constraint of [{
                            video: {
                                facingMode: {
                                    ideal: 'environment'
                                }
                            }
                        },
                        {
                            video: true
                        }
                    ]) {
                    try {
                        streamQrAmbil = await navigator.mediaDevices.getUserMedia(constraint);
                        const video = document.getElementById('video-qr-ambil');
                        video.srcObject = streamQrAmbil;
                        video.style.transform = 'scaleX(1)';
                        return;
                    } catch (e) {
                        continue;
                    }
                }
                alert('Tidak bisa mengakses kamera.');
                tutupModalQrAmbil();
            };
            tryCamera();
        }

        function tutupModalQrAmbil() {
            streamQrAmbil?.getTracks().forEach(t => t.stop());
            streamQrAmbil = null;
            document.getElementById('modal-qr-ambil')?.remove();
        }

        function jepretQrAmbil() {
            const video = document.getElementById('video-qr-ambil');
            const canvas = document.getElementById('canvas-qr-ambil');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
            document.getElementById('foto-qr-input').value = dataUrl;
            document.getElementById('foto-qr-img').src = dataUrl;
            document.getElementById('foto-qr-preview').classList.remove('hidden');
            tutupModalQrAmbil();
        }

        function hapusFotoQr() {
            document.getElementById('foto-qr-input').value = '';
            document.getElementById('foto-qr-preview').classList.add('hidden');
        }

        // ── Kamera Nama Mode ──
        let streamsAmbil = {};

        async function bukaKameraAmbil(id) {
            document.getElementById('modal-kamera-ambil-' + id).classList.remove('hidden');
            for (const constraint of [{
                        video: {
                            facingMode: {
                                ideal: 'environment'
                            }
                        }
                    },
                    {
                        video: true
                    }
                ]) {
                try {
                    streamsAmbil[id] = await navigator.mediaDevices.getUserMedia(constraint);
                    break;
                } catch (e) {
                    continue;
                }
            }
            if (streamsAmbil[id]) {
                const video = document.getElementById('video-ambil-' + id);
                video.srcObject = streamsAmbil[id];
                video.style.transform = 'scaleX(1)';
            } else {
                alert('Tidak bisa mengakses kamera.');
                tutupKameraAmbil(id);
            }
        }

        function tutupKameraAmbil(id) {
            streamsAmbil[id]?.getTracks().forEach(t => t.stop());
            delete streamsAmbil[id];
            document.getElementById('modal-kamera-ambil-' + id).classList.add('hidden');
        }

        function jepretFotoAmbil(id) {
            const video = document.getElementById('video-ambil-' + id);
            const canvas = document.getElementById('canvas-ambil-' + id);
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
            document.getElementById('foto-ambil-input-' + id).value = dataUrl;
            document.getElementById('foto-ambil-img-' + id).src = dataUrl;
            document.getElementById('foto-ambil-preview-' + id).classList.remove('hidden');
            tutupKameraAmbil(id);
        }

        function pilihFotoAmbil(input, id) {
            const file = input.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('foto-ambil-input-' + id).value = e.target.result;
                document.getElementById('foto-ambil-img-' + id).src = e.target.result;
                document.getElementById('foto-ambil-preview-' + id).classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        function hapusFotoAmbil(id) {
            document.getElementById('foto-ambil-input-' + id).value = '';
            document.getElementById('foto-ambil-preview-' + id).classList.add('hidden');
        }

        // ── Foto Modal ──
        function bukaFotoModal(src) {
            document.getElementById('foto-modal-img').src = src;
            document.getElementById('foto-modal').classList.remove('hidden');
        }
    </script>

@endsection
