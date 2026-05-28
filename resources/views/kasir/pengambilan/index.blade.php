@extends('layouts.kasir')
@section('title', 'Pengambilan Barang')

@section('content')

    <div class="anim-fade-up delay-1 mb-6">
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #7c3aed">Transaksi</p>
        <h1 class="text-xl lg:text-2xl font-black text-gray-900">Pengambilan Barang</h1>
        <p class="text-gray-400 text-sm mt-1">Scan QR code nota atau cari nama penitip.</p>
    </div>

    {{-- Tab QR vs Nama --}}
    <div class="anim-fade-up delay-2 bg-white rounded-2xl border border-gray-100 overflow-hidden mb-5"
        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">

        {{-- Tab Header --}}
        <div class="flex border-b border-gray-100">
            <button onclick="switchMode('qr')" id="tab-qr"
                class="flex-1 py-3.5 text-sm font-bold transition pengambilan-tab active-pengambilan-tab">
                📱 Scan QR Code
            </button>
            <button onclick="switchMode('nama')" id="tab-nama"
                class="flex-1 py-3.5 text-sm font-bold transition pengambilan-tab">
                🔍 Cari Nama
            </button>
        </div>

        {{-- Panel QR --}}
        <div id="panel-qr" class="p-5">
            <p class="text-sm text-center mb-4" style="color: #94a3b8">
                Arahkan kamera ke QR code pada nota penitipan
            </p>

            {{-- Scanner Area --}}
            <div class="relative mb-4 mx-auto overflow-hidden rounded-2xl"
                style="background: #0a0a14; width: 100%; max-width: 300px; height: 300px">

                {{-- Video --}}
                <video id="qr-video" autoplay playsinline
                    style="position: absolute; inset: 0; width: 100%; height: 100%;
                object-fit: cover; transform: scaleX(1) !important; display: block"></video>

                {{-- Dark overlay corners --}}
                <div class="absolute inset-0 pointer-events-none"
                    style="background: radial-gradient(ellipse at center, transparent 45%, rgba(0,0,0,0.65) 70%)"></div>

                {{-- Scan frame --}}
                <div class="absolute pointer-events-none"
                    style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 60%; height: 60%">
                    <div
                        style="position: absolute; top:0; left:0; width:28px; height:28px;
                    border-top: 3px solid #a78bfa; border-left: 3px solid #a78bfa; border-radius: 6px 0 0 0">
                    </div>
                    <div
                        style="position: absolute; top:0; right:0; width:28px; height:28px;
                    border-top: 3px solid #a78bfa; border-right: 3px solid #a78bfa; border-radius: 0 6px 0 0">
                    </div>
                    <div
                        style="position: absolute; bottom:0; left:0; width:28px; height:28px;
                    border-bottom: 3px solid #a78bfa; border-left: 3px solid #a78bfa; border-radius: 0 0 0 6px">
                    </div>
                    <div
                        style="position: absolute; bottom:0; right:0; width:28px; height:28px;
                    border-bottom: 3px solid #a78bfa; border-right: 3px solid #a78bfa; border-radius: 0 0 6px 0">
                    </div>

                    {{-- Scan line --}}
                    <div id="scan-line"
                        style="position: absolute; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, #c4b5fd, #a78bfa, #c4b5fd, transparent); box-shadow: 0 0 10px 2px rgba(167,139,250,0.6); animation: scanLine 1.8s ease-in-out infinite">
                    </div>
                </div>

                {{-- Status badge --}}
                <div id="qr-status" class="absolute bottom-3 left-0 right-0 flex justify-center pointer-events-none">
                    <span class="px-3 py-1.5 rounded-full text-xs font-bold"
                        style="background: rgba(0,0,0,0.75); color: #c4b5fd; backdrop-filter: blur(6px)">
                        📷 Kamera belum aktif
                    </span>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="max-w-xs mx-auto">
                <button onclick="toggleKameraQr()" id="btn-toggle-kamera"
                    class="w-full py-3 rounded-xl font-bold text-sm transition text-white"
                    style="background: linear-gradient(135deg, #5b21b6, #7c3aed); box-shadow: 0 4px 12px rgba(91,33,182,0.25)">
                    📷 Aktifkan Kamera
                </button>
            </div>
        </div>

        {{-- Panel Nama --}}
        <div id="panel-nama" class="p-5 hidden">
            <form action="{{ route('kasir.pengambilan.cari') }}" method="POST">
                @csrf
                <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Nama
                    Penitip</label>
                <div class="flex gap-3">
                    <input type="text" name="nama_penitip" value="{{ old('nama_penitip') }}"
                        class="flex-1 rounded-xl px-4 py-3 text-sm transition"
                        style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #374151"
                        placeholder="Contoh: Budi Santoso" onfocus="this.style.borderColor='#a78bfa'"
                        onblur="this.style.borderColor='#ede9fe'">
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-3 rounded-xl text-white font-bold text-sm transition hover:opacity-90 flex-shrink-0"
                        style="background: linear-gradient(135deg, #5b21b6, #7c3aed)">
                        🔍 Cari
                    </button>
                </div>
                @error('nama_penitip')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </form>
        </div>
    </div>

    {{-- Hasil QR (muncul setelah scan) --}}
    <div id="hasil-qr" class="hidden"></div>

    {{-- Hasil Pencarian Nama --}}
    @isset($transaksis)
        @if ($transaksis->count() > 0)
            <div class="space-y-4">
                @foreach ($transaksis as $transaksi)
                    <div class="anim-fade-up delay-4 rounded-2xl overflow-hidden border border-gray-100"
                        style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">

                        <div class="flex justify-between items-center px-6 py-4"
                            style="background: linear-gradient(135deg, #1e1035, #2d1b69)">
                            <p class="text-xs font-bold uppercase tracking-widest" style="color: rgba(255,255,255,0.5)">Detail
                                Transaksi</p>
                            <span class="text-xs font-bold px-3 py-1 rounded-full font-mono"
                                style="background: rgba(167,139,250,0.2); color: #c4b5fd">
                                {{ $transaksi->nomor_transaksi }}
                            </span>
                        </div>

                        <div class="bg-white px-6 py-5">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Nama
                                        Penitip</p>
                                    <p class="text-lg font-black text-gray-800">{{ $transaksi->nama_penitip }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Barang
                                    </p>
                                    @foreach ($transaksi->details as $d)
                                        <p class="font-bold text-gray-800 text-sm">
                                            {{ $d->nama_barang_custom ?? $d->kategori->nama_kategori }}</p>
                                    @endforeach
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Status
                                    </p>
                                    @if ($transaksi->status === 'terlambat')
                                        <p class="flex items-center gap-2 font-bold text-red-600">
                                            <span class="w-2 h-2 rounded-full animate-pulse bg-red-500 inline-block"></span>
                                            Terlambat
                                        </p>
                                    @else
                                        <p class="flex items-center gap-2 font-bold" style="color: #7c3aed">
                                            <span class="w-2 h-2 rounded-full animate-pulse inline-block"
                                                style="background: #a78bfa"></span>
                                            Dititipkan
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Foto Penitipan --}}
                            @if ($transaksi->foto_penitipan)
                                <div class="mb-4">
                                    <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color: #94a3b8">Foto
                                        Barang Saat Penitipan</p>
                                    <img src="{{ asset('storage/' . $transaksi->foto_penitipan) }}" alt="Foto Barang"
                                        class="w-full max-h-48 object-cover rounded-xl cursor-pointer"
                                        style="border: 1.5px solid #ede9fe" onclick="bukaFotoModal(this.src)">
                                </div>
                            @endif

                            {{-- Warning Terlambat --}}
                            @if ($transaksi->status === 'terlambat')
                                <div class="flex items-start gap-3 px-4 py-3 rounded-xl mb-4"
                                    style="background: #fff5f5; border: 1.5px solid #fecaca">
                                    <span class="text-red-500 flex-shrink-0">⚠️</span>
                                    <div>
                                        <p class="text-red-700 font-bold text-sm">Pengambilan Terlambat</p>
                                        <p class="text-red-400 text-xs mt-0.5">Event sudah berakhir. Konfirmasi tetap bisa
                                            dilakukan dengan catatan keterlambatan.</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Upload Foto Pengambilan --}}
                            <div class="mb-4">
                                <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">
                                    Foto Pengambilan <span class="font-normal normal-case text-gray-400">(opsional)</span>
                                </label>
                                <div id="foto-ambil-preview-{{ $transaksi->id }}" class="hidden mb-2">
                                    <div class="relative inline-block w-full">
                                        <img id="foto-ambil-img-{{ $transaksi->id }}" src="" alt="Preview"
                                            class="w-full max-h-40 object-cover rounded-xl"
                                            style="border: 1.5px solid #ddd6fe">
                                        <button type="button" onclick="hapusFotoAmbil({{ $transaksi->id }})"
                                            class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center rounded-full text-white text-xs font-bold"
                                            style="background: rgba(0,0,0,0.6)">✕</button>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" onclick="bukaKameraAmbil({{ $transaksi->id }})"
                                        class="flex items-center gap-2 px-3 py-2 rounded-xl font-bold text-xs transition"
                                        style="background: #faf5ff; color: #7c3aed; border: 1.5px solid #ede9fe">
                                        📷 Foto
                                    </button>
                                    <label
                                        class="flex items-center gap-2 px-3 py-2 rounded-xl font-bold text-xs cursor-pointer transition"
                                        style="background: #f8faff; color: #1a3a6b; border: 1.5px solid #e2e8f0">
                                        🖼️ Galeri
                                        <input type="file" accept="image/*" class="hidden"
                                            onchange="pilihFotoAmbil(this, {{ $transaksi->id }})">
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Konfirmasi --}}
                        <div class="px-6 pb-6 bg-white">
                            <form action="{{ route('kasir.pengambilan.konfirmasi', $transaksi) }}" method="POST"
                                onsubmit="return confirm('{{ $transaksi->status === 'terlambat' ? 'Barang ini terlambat diambil. Tetap konfirmasi?' : 'Konfirmasi pengambilan?' }}')">
                                @csrf
                                <input type="hidden" name="foto_pengambilan" id="foto-ambil-input-{{ $transaksi->id }}">
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-3 py-4 rounded-2xl text-white font-black text-base transition hover:opacity-90"
                                    style="background: {{ $transaksi->status === 'terlambat' ? 'linear-gradient(135deg, #dc2626, #ef4444)' : 'linear-gradient(135deg, #5b21b6, #7c3aed)' }}">
                                    {{ $transaksi->status === 'terlambat' ? '⚠️ Konfirmasi (Terlambat)' : '🛡️ Konfirmasi Pengambilan' }}
                                </button>
                            </form>
                        </div>

                        {{-- Modal Kamera Pengambilan --}}
                        <div id="modal-kamera-ambil-{{ $transaksi->id }}"
                            class="hidden fixed inset-0 z-50 flex items-center justify-center"
                            style="background: rgba(0,0,0,0.8)">
                            <div class="bg-white rounded-2xl overflow-hidden w-full max-w-sm mx-4">
                                <div class="flex justify-between items-center px-4 py-3"
                                    style="border-bottom: 1px solid #f1f5f9">
                                    <p class="font-black text-gray-800">📷 Foto Pengambilan</p>
                                    <button onclick="tutupKameraAmbil({{ $transaksi->id }})"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg"
                                        style="background: #f1f5f9; color: #6b7280">✕</button>
                                </div>
                                <div class="p-4">
                                    <video id="video-ambil-{{ $transaksi->id }}" autoplay playsinline
                                        style="width: 100%; height: 240px; object-fit: cover; transform: scaleX(1) !important; display: block"
                                        class="w-full rounded-xl" style="background: #000; max-height: 280px"></video>
                                    <canvas id="canvas-ambil-{{ $transaksi->id }}" class="hidden"></canvas>
                                    <button type="button" onclick="jepretFotoAmbil({{ $transaksi->id }})"
                                        class="w-full mt-3 py-3 rounded-xl text-white font-bold text-sm"
                                        style="background: linear-gradient(135deg, #5b21b6, #7c3aed)">
                                        📸 Jepret
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <a href="{{ route('kasir.pengambilan.index') }}"
                class="w-full flex items-center justify-center py-4 rounded-2xl font-bold text-sm transition mt-4"
                style="background: white; border: 1.5px solid #ede9fe; color: #7c3aed">
                BATALKAN & KEMBALI
            </a>
        @endif
    @endisset

    {{-- Modal Foto Fullscreen --}}
    <div id="foto-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.9)" onclick="this.classList.add('hidden')">
        <img id="foto-modal-img" src="" alt="Foto" class="max-w-full max-h-full rounded-xl object-contain">
    </div>

    <style>
        @keyframes scanLine {
            0% {
                top: 0;
                opacity: 1;
            }

            49% {
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

        @keyframes pulse-border {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .pengambilan-tab {
            color: #94a3b8;
            border-bottom: 2px solid transparent;
        }

        .active-pengambilan-tab {
            color: #5b21b6;
            border-bottom: 2px solid #7c3aed;
        }

        /* Kamera tidak mirror */
        #qr-video {
            transform: scaleX(1) !important;
        }

        /* Modal kamera tidak mirror juga */
        video.kamera-preview {
            transform: scaleX(1) !important;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsQR/1.4.0/jsQR.min.js"></script>

    <script>
        // ── Tab switch ──
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
        let qrScanning = false;
        let qrInterval = null;

        async function toggleKameraQr() {
            const btn = document.getElementById('btn-toggle-kamera');
            if (qrStream) {
                stopQrScanner();
                btn.textContent = '📷 Aktifkan Kamera';
                btn.style.background = 'linear-gradient(135deg, #5b21b6, #7c3aed)';
            } else {
                await startQrScanner();
                btn.textContent = '⏹ Hentikan Kamera';
                btn.style.background = '#dc2626';
            }
        }

        async function startQrScanner() {
            try {
                // Coba kamera belakang dulu
                qrStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: {
                            ideal: 'environment'
                        },
                        width: {
                            ideal: 1920,
                            min: 640
                        },
                        height: {
                            ideal: 1080,
                            min: 480
                        },
                    }
                });
            } catch (e) {
                try {
                    // Fallback — kamera apapun yang tersedia
                    qrStream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            width: {
                                ideal: 1280
                            },
                            height: {
                                ideal: 720
                            }
                        }
                    });
                } catch (err) {
                    alert('Tidak bisa mengakses kamera: ' + err.message);
                    return;
                }
            }

            const video = document.getElementById('qr-video');
            video.srcObject = qrStream;
            video.style.cssText =
                'width:100%; height:100%; object-fit:cover; transform:scaleX(1)!important; display:block';

            video.onloadedmetadata = () => {
                video.play();
                qrScanning = true;
                setQrStatus('🔍 Scanning...', '#c4b5fd');

                // Interval lebih cepat untuk scan lebih responsive
                qrInterval = setInterval(scanQrFrame, 150);
            };
        }

        function setQrStatus(text, color) {
            const el = document.getElementById('qr-status');
            el.innerHTML = `<span class="px-3 py-1.5 rounded-full text-xs font-bold"
        style="background: rgba(0,0,0,0.7); color: ${color}; backdrop-filter: blur(4px)">${text}</span>`;
        }

        function setQrStatus(text, color) {
            const el = document.getElementById('qr-status');
            el.innerHTML = `<span class="px-3 py-1.5 rounded-full text-xs font-bold"
        style="background: rgba(0,0,0,0.7); color: ${color}; backdrop-filter: blur(4px)">${text}</span>`;
        }

        function stopQrScanner() {
            if (qrStream) {
                qrStream.getTracks().forEach(t => t.stop());
                qrStream = null;
            }
            qrScanning = false;
            clearInterval(qrInterval);
        }

        function scanQrFrame() {
            const video = document.getElementById('qr-video');
            if (!video || video.readyState < 2 || video.videoWidth === 0) return;

            const canvas = document.createElement('canvas');
            const w = video.videoWidth;
            const h = video.videoHeight;

            canvas.width = w;
            canvas.height = h;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, w, h);

            // Coba berbagai metode inversion
            const attempts = ['dontInvert', 'onlyInvert', 'attemptBoth'];
            for (const attempt of attempts) {
                const imageData = ctx.getImageData(0, 0, w, h);
                const code = jsQR(imageData.data, w, h, {
                    inversionAttempts: attempt
                });
                if (code && code.data && code.data.trim() !== '') {
                    clearInterval(qrInterval);
                    stopQrScanner();
                    setQrStatus('✅ QR Terdeteksi!', '#4ade80');
                    console.log('QR found:', code.data);
                    prosesQr(code.data.trim());
                    return;
                }
            }
        }

        async function prosesQr(nomorTransaksi) {
            document.getElementById('qr-status').textContent = '⏳ Memproses...';

            const res = await fetch('{{ route('kasir.pengambilan.scan-qr') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    nomor_transaksi: nomorTransaksi
                })
            });

            const data = await res.json();

            if (!data.found) {
                document.getElementById('qr-status').textContent = '❌ Tidak ditemukan';
                document.getElementById('hasil-qr').innerHTML = `
            <div class="bg-white rounded-2xl border border-gray-100 p-6 text-center mb-4"
                style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
                <div class="text-4xl mb-3">😕</div>
                <p class="font-black text-gray-700">Transaksi tidak ditemukan</p>
                <p class="text-gray-400 text-sm mt-1">Nomor: <span class="font-mono font-bold">${nomorTransaksi}</span></p>
                <p class="text-gray-400 text-sm">Mungkin sudah diambil atau nomor tidak valid.</p>
                <button onclick="resetQr()" class="mt-4 px-5 py-2.5 rounded-xl text-white font-bold text-sm"
                    style="background: linear-gradient(135deg, #5b21b6, #7c3aed)">
                    🔄 Scan Ulang
                </button>
            </div>`;
                document.getElementById('hasil-qr').classList.remove('hidden');
                return;
            }

            const t = data.transaksi;
            const isTerlambat = t.status === 'terlambat';

            document.getElementById('hasil-qr').innerHTML = `
        <div class="rounded-2xl overflow-hidden border border-gray-100 mb-4"
            style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">

            <div class="flex justify-between items-center px-6 py-4"
                style="background: linear-gradient(135deg, #1e1035, #2d1b69)">
                <p class="text-xs font-bold uppercase tracking-widest" style="color: rgba(255,255,255,0.5)">QR Terdeteksi ✅</p>
                <span class="text-xs font-bold px-3 py-1 rounded-full font-mono"
                    style="background: rgba(167,139,250,0.2); color: #c4b5fd">${t.nomor}</span>
            </div>

            <div class="bg-white px-6 py-5">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Nama Penitip</p>
                        <p class="text-xl font-black text-gray-800">${t.nama_penitip}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Event</p>
                        <p class="font-bold text-gray-800">${t.event}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Total Barang</p>
                        <p class="text-xl font-black text-gray-800">${t.total_barang} unit</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color: #94a3b8">Waktu Titip</p>
                        <p class="font-bold text-gray-700 text-sm">${t.waktu_penitipan}</p>
                    </div>
                </div>

                ${t.foto_penitipan ? `
                                                                                                                <div class="mb-4">
                                                                                                                    <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color: #94a3b8">Foto Barang Saat Penitipan</p>
                                                                                                                    <img src="${t.foto_penitipan}" alt="Foto Barang"
                                                                                                                        class="w-full max-h-48 object-cover rounded-xl cursor-pointer"
                                                                                                                        style="border: 1.5px solid #ede9fe"
                                                                                                                        onclick="bukaFotoModal(this.src)">
                                                                                                                </div>` : ''}

                ${isTerlambat ? `
                                                                                                                <div class="flex items-start gap-3 px-4 py-3 rounded-xl mb-4"
                                                                                                                    style="background: #fff5f5; border: 1.5px solid #fecaca">
                                                                                                                    <span style="color: #ef4444">⚠️</span>
                                                                                                                    <div>
                                                                                                                        <p class="font-bold text-sm" style="color: #dc2626">Pengambilan Terlambat</p>
                                                                                                                        <p class="text-xs mt-0.5" style="color: #f87171">Event sudah berakhir.</p>
                                                                                                                    </div>
                                                                                                                </div>` : ''}

                {{-- Foto Pengambilan --}}
                <div class="mb-4">
                    <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">
                        Foto Pengambilan <span class="font-normal normal-case" style="color: #94a3b8">(opsional)</span>
                    </p>
                    <div id="foto-qr-preview" class="hidden mb-2">
                        <div class="relative">
                            <img id="foto-qr-img" src="" class="w-full max-h-40 object-cover rounded-xl"
                                style="border: 1.5px solid #ddd6fe">
                            <button type="button" onclick="hapusFotoQr()"
                                class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center rounded-full text-white text-xs font-bold"
                                style="background: rgba(0,0,0,0.6)">✕</button>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="bukaKameraQrAmbil()" type="button"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl font-bold text-xs"
                            style="background: #faf5ff; color: #7c3aed; border: 1.5px solid #ede9fe">
                            📷 Foto
                        </button>
                    </div>
                    <input type="hidden" id="foto-qr-input">
                </div>
            </div>

            <div class="px-6 pb-6 bg-white">
                <button onclick="konfirmasiQr(${t.id}, ${isTerlambat})"
                    class="w-full py-4 rounded-2xl text-white font-black text-base transition hover:opacity-90"
                    style="background: ${isTerlambat ? 'linear-gradient(135deg, #dc2626, #ef4444)' : 'linear-gradient(135deg, #5b21b6, #7c3aed)'}">
                    ${isTerlambat ? '⚠️ Konfirmasi (Terlambat)' : '🛡️ Konfirmasi Pengambilan'}
                </button>
                <button onclick="resetQr()" class="w-full mt-2 py-3 rounded-2xl font-bold text-sm transition"
                    style="background: white; border: 1.5px solid #ede9fe; color: #7c3aed">
                    🔄 Scan Ulang
                </button>
            </div>
        </div>`;

            document.getElementById('hasil-qr').classList.remove('hidden');

            // Scroll ke hasil
            document.getElementById('hasil-qr').scrollIntoView({
                behavior: 'smooth'
            });
        }

        function resetQr() {
            document.getElementById('hasil-qr').innerHTML = '';
            document.getElementById('hasil-qr').classList.add('hidden');
            document.getElementById('btn-toggle-kamera').textContent = '📷 Aktifkan Kamera';
            document.getElementById('btn-toggle-kamera').style.background = 'linear-gradient(135deg, #5b21b6, #7c3aed)';
            document.getElementById('qr-status').textContent = '🔍 Menunggu QR...';
        }

        async function konfirmasiQr(transaksiId, isTerlambat) {
            if (!confirm(isTerlambat ? 'Barang ini terlambat diambil. Tetap konfirmasi?' :
                    'Konfirmasi pengambilan barang ini?')) return;

            const fotoInput = document.getElementById('foto-qr-input');
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'POST');
            if (fotoInput && fotoInput.value) {
                formData.append('foto_pengambilan', fotoInput.value);
            }

            const res = await fetch(`/kasir/pengambilan/konfirmasi/${transaksiId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: new URLSearchParams({
                    '_token': '{{ csrf_token() }}',
                    'foto_pengambilan': fotoInput?.value || ''
                })
            });

            window.location.href = '{{ route('kasir.pengambilan.index') }}?success=1';
        }

        // ── Kamera Foto Pengambilan (QR mode) ──
        let streamQrAmbil = null;

        function bukaKameraQrAmbil() {
            const modal = document.createElement('div');
            modal.id = 'modal-qr-ambil';
            modal.className = 'fixed inset-0 z-50 flex items-center justify-center';
            modal.style.background = 'rgba(0,0,0,0.85)';
            modal.innerHTML = `
        <div class="bg-white rounded-2xl overflow-hidden w-full max-w-sm mx-4">
            <div class="flex justify-between items-center px-4 py-3" style="border-bottom: 1px solid #f1f5f9">
                <p class="font-black text-gray-800">📷 Foto Pengambilan</p>
                <button onclick="tutupModalQrAmbil()" class="w-8 h-8 flex items-center justify-center rounded-lg" style="background: #f1f5f9; color: #6b7280">✕</button>
            </div>
            <div class="p-4">
                <div class="rounded-xl overflow-hidden mb-3" style="background: #000; aspect-ratio: 4/3">
                    <video id="video-qr-ambil" autoplay playsinline
                        class="w-full h-full object-cover"
                        style="transform: scaleX(1)"></video>
                </div>
                <canvas id="canvas-qr-ambil" class="hidden"></canvas>
                <button type="button" onclick="jepretQrAmbil()"
                    class="w-full py-3 rounded-xl text-white font-bold text-sm"
                    style="background: linear-gradient(135deg, #5b21b6, #7c3aed); box-shadow: 0 4px 12px rgba(91,33,182,0.2)">
                    📸 Jepret Foto
                </button>
            </div>
        </div>`;
            document.body.appendChild(modal);

            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: {
                        ideal: 'environment'
                    }
                }
            }).then(stream => {
                streamQrAmbil = stream;
                const video = document.getElementById('video-qr-ambil');
                video.srcObject = stream;
                video.style.transform = 'scaleX(1)';
            }).catch(() => {
                navigator.mediaDevices.getUserMedia({
                    video: true
                }).then(stream => {
                    streamQrAmbil = stream;
                    const video = document.getElementById('video-qr-ambil');
                    video.srcObject = stream;
                    video.style.transform = 'scaleX(1)';
                });
            });
        }

        function tutupModalQrAmbil() {
            if (streamQrAmbil) {
                streamQrAmbil.getTracks().forEach(t => t.stop());
                streamQrAmbil = null;
            }
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

        // ── Kamera Foto Pengambilan (Nama mode) ──
        let streamsAmbil = {};

        async function bukaKameraAmbil(id) {
            document.getElementById('modal-kamera-ambil-' + id).classList.remove('hidden');
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: {
                            ideal: 'environment'
                        }
                    }
                });
                streamsAmbil[id] = stream;
                const video = document.getElementById('video-ambil-' + id);
                video.srcObject = stream;
                video.style.transform = 'scaleX(1)'; // tidak mirror
            } catch (e) {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: true
                    });
                    streamsAmbil[id] = stream;
                    const video = document.getElementById('video-ambil-' + id);
                    video.srcObject = stream;
                    video.style.transform = 'scaleX(1)';
                } catch (err) {
                    alert('Tidak bisa mengakses kamera.');
                    tutupKameraAmbil(id);
                }
            }
        }

        function tutupKameraAmbil(id) {
            if (streamsAmbil[id]) {
                streamsAmbil[id].getTracks().forEach(t => t.stop());
                delete streamsAmbil[id];
            }
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

        // ── Foto Modal Fullscreen ──
        function bukaFotoModal(src) {
            document.getElementById('foto-modal-img').src = src;
            document.getElementById('foto-modal').classList.remove('hidden');
        }
    </script>

@endsection
