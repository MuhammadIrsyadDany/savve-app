@extends('layouts.kasir')
@section('title', 'Transaksi Penitipan Baru')

@section('content')

    {{-- Banner Notifikasi --}}
    @if ($events->count() > 0)
        <div id="banner"
            class="flex items-center justify-between bg-white border border-indigo-200 rounded-2xl px-5 py-3 mb-6 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm">✓</div>
                <div>
                    <p class="font-bold text-gray-800 text-sm">Sistem Siap</p>
                    <p class="text-xs text-gray-400">Input transaksi baru sekarang untuk Event:
                        {{ $events->first()->nama_event }}</p>
                </div>
            </div>
            <button onclick="document.getElementById('banner').remove()"
                class="text-gray-300 hover:text-gray-500 text-lg">✕</button>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">

        {{-- Panel Kanan Mobile (tampil di atas form) --}}
        <div class="w-full lg:hidden space-y-3">
            <div class="rounded-2xl p-5 text-white relative overflow-hidden"
                style="background: linear-gradient(135deg, #1e1035, #4c1d95)">
                <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #c4b5fd">Transaction Number
                    Preview</p>
                <p id="nomor-preview-mobile" class="text-2xl font-black tracking-tight leading-tight mb-3 font-mono">
                    SVV-{{ now()->format('ymd') }}-????
                </p>
                <div>
                    <p class="text-xs uppercase tracking-wider" style="color: rgba(255,255,255,0.5)">Status</p>
                    <p class="font-bold text-white text-sm" id="status-preview-mobile">READY TO SAVE</p>
                </div>
                <div class="absolute -bottom-4 -right-4 w-20 h-20 rounded-full" style="background: rgba(255,255,255,0.05)">
                </div>
            </div>
        </div>

        {{-- ═══ FORM KIRI ═══ --}}
        <div class="flex-1 min-w-0">
            <form action="{{ route('kasir.transaksi.store') }}" method="POST" id="form-transaksi">
                @csrf

                <div class="bg-white rounded-2xl border border-gray-100 p-5 lg:p-6"
                    style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">

                    {{-- Title --}}
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-lg lg:text-xl font-black text-gray-800">Transaksi Penitipan Baru</h1>
                        <span class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full"
                            style="background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse inline-block"></span>
                            LIVE
                        </span>
                    </div>

                    {{-- Pilih Event --}}
                    <div class="mb-5">
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Pilih
                            Event</label>
                        <select name="event_id" id="event_id" class="w-full rounded-xl px-4 py-3 text-sm transition"
                            style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #374151"
                            onchange="updatePreview()" onfocus="this.style.borderColor='#a78bfa'"
                            onblur="this.style.borderColor='#ede9fe'">
                            <option value="">-- Pilih Event --</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->nama_event }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Penitip & WhatsApp --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Nama
                                Penitip</label>
                            <input type="text" name="nama_penitip" value="{{ old('nama_penitip') }}"
                                class="w-full rounded-xl px-4 py-3 text-sm transition"
                                style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #374151"
                                placeholder="Contoh: Budi Santoso" oninput="updatePreview()"
                                onfocus="this.style.borderColor='#a78bfa'" onblur="this.style.borderColor='#ede9fe'">
                            @error('nama_penitip')
                                <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">No
                                WhatsApp</label>
                            <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                                class="w-full rounded-xl px-4 py-3 text-sm transition"
                                style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #374151"
                                placeholder="08*********" onfocus="this.style.borderColor='#a78bfa'"
                                onblur="this.style.borderColor='#ede9fe'">
                            @error('no_whatsapp')
                                <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Daftar Barang --}}
                    <div id="barang-container" class="space-y-4 mb-4">
                        <div class="barang-item rounded-xl p-4" style="background: #faf5ff; border: 1.5px solid #ede9fe">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                                        style="color: #64748b">Nama Barang</label>
                                    <select name="barang[0][kategori_id]"
                                        class="kategori-select w-full rounded-xl px-3 py-2.5 text-sm"
                                        style="background: white; border: 1.5px solid #ddd6fe; color: #374151">
                                        @foreach ($kategoris as $k)
                                            <option value="{{ $k->id }}" data-custom="{{ $k->is_custom }}">
                                                {{ $k->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                                        style="color: #64748b">Quantity</label>
                                    <div class="flex items-center gap-2 w-full">
                                        <button type="button" onclick="changeQty(this, -1)"
                                            class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center font-bold text-lg transition"
                                            style="background: white; border: 1.5px solid #ddd6fe; color: #7c3aed; min-width: 36px">−</button>
                                        <input type="number" name="barang[0][jumlah]" value="1" min="1"
                                            class="min-w-0 w-full text-center rounded-xl py-2.5 text-sm font-bold"
                                            style="background: white; border: 1.5px solid #ddd6fe; color: #374151">
                                        <button type="button" onclick="changeQty(this, 1)"
                                            class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center font-bold text-lg transition"
                                            style="background: white; border: 1.5px solid #ddd6fe; color: #7c3aed; min-width: 36px">+</button>
                                    </div>
                                </div>
                            </div>

                            <div class="nama-custom-wrapper mb-3" style="display:none">
                                <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                                    style="color: #64748b">Nama Barang (Lainnya)</label>
                                <input type="text" name="barang[0][nama_custom]"
                                    class="w-full rounded-xl px-4 py-3 text-sm"
                                    style="background: white; border: 1.5px solid #ddd6fe; color: #374151"
                                    placeholder="Tulis nama barang...">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider mb-2"
                                    style="color: #64748b">Ukuran Barang</label>
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach (['S', 'M', 'L', 'XL'] as $u)
                                        <label class="ukuran-label cursor-pointer">
                                            <input type="radio" name="barang[0][ukuran]" value="{{ $u }}"
                                                class="hidden ukuran-radio" {{ $u === 'S' ? 'checked' : '' }}>
                                            <div class="ukuran-box border-2 rounded-xl py-2.5 text-center font-bold text-sm transition"
                                                style="{{ $u === 'S' ? 'border-color: #7c3aed; color: #7c3aed; background: white;' : 'border-color: #ddd6fe; color: #94a3b8; background: white;' }}">
                                                {{ $u }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tambah Barang --}}
                    <button type="button" id="tambah-barang"
                        class="w-full py-2.5 rounded-xl text-sm font-semibold transition mb-5"
                        style="border: 2px dashed #ddd6fe; color: #7c3aed; background: transparent">
                        + Tambah Barang Lain
                    </button>

                    {{-- Upload Foto Barang --}}
                    <div class="mb-5">
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">
                            Foto Barang <span class="font-normal normal-case text-gray-400">(opsional)</span>
                        </label>

                        <div id="foto-preview-wrapper" class="hidden mb-3">
                            <div class="relative">
                                <img id="foto-preview" src="" alt="Preview"
                                    class="w-full max-h-48 object-cover rounded-xl" style="border: 1.5px solid #ddd6fe">
                                <button type="button" onclick="hapusFoto()"
                                    class="absolute top-2 right-2 w-7 h-7 flex items-center justify-center rounded-full text-white text-xs font-bold"
                                    style="background: rgba(0,0,0,0.6)">✕</button>
                            </div>
                        </div>

                        <input type="hidden" name="foto_penitipan" id="foto_penitipan_input">

                        <div id="foto-buttons" class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="bukaKamera('penitipan')"
                                class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-semibold text-sm transition hover:opacity-90"
                                style="background: linear-gradient(135deg, #faf5ff, #f3e8ff); color: #7c3aed; border: 1.5px solid #e9d5ff">
                                <span>📷</span> Ambil Foto
                            </button>
                            <label
                                class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl font-semibold text-sm cursor-pointer transition hover:opacity-90"
                                style="background: linear-gradient(135deg, #f8fbff, #eef4ff); color: #1d4ed8; border: 1.5px solid #dbeafe">
                                <span>🖼️</span> Pilih dari Galeri
                                <input type="file" accept="image/*" class="hidden" onchange="pilihDariGaleri(this)">
                            </label>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                            style="background: linear-gradient(135deg, #5b21b6, #7c3aed); box-shadow: 0 4px 12px rgba(91,33,182,0.25)">
                            💾 Simpan Transaksi
                        </button>
                    </div>

                    <div class="flex items-center justify-between mt-3">
                        <button type="reset" onclick="resetForm()" class="text-sm flex items-center gap-1 transition"
                            style="color: #94a3b8">
                            🔄 Reset
                        </button>
                        <a href="{{ route('kasir.dashboard') }}" id="btn-batal"
                            class="text-sm flex items-center gap-2 font-semibold px-4 py-2 rounded-xl transition"
                            style="background: #fff5f5; color: #ef4444; border: 1.5px solid #fecaca">
                            ✕ Batal & Kembali
                        </a>
                    </div>

                </div>
            </form>
        </div>

        {{-- ═══ PANEL KANAN DESKTOP ═══ --}}
        <div class="hidden lg:flex w-72 flex-shrink-0 flex-col space-y-4">

            {{-- Transaction Number Preview --}}
            <div class="rounded-2xl p-6 text-white relative overflow-hidden"
                style="background: linear-gradient(135deg, #1e1035, #4c1d95)">
                <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color: #c4b5fd">Transaction Number
                    Preview</p>
                <p id="nomor-preview" class="text-3xl font-black tracking-tight leading-tight mb-4 font-mono">
                    SVV-{{ now()->format('ymd') }}-????
                </p>
                <div>
                    <p class="text-xs uppercase tracking-wider" style="color: rgba(255,255,255,0.5)">Status</p>
                    <p class="font-bold text-white" id="status-preview">READY TO SAVE</p>
                </div>
                <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full"
                    style="background: rgba(255,255,255,0.05)"></div>
            </div>

            {{-- Warehouse Capacity --}}
            @php
                $totalDititip = \App\Models\DetailTransaksi::whereHas(
                    'transaksi',
                    fn($q) => $q->where('status', 'dititip'),
                )->sum('jumlah');
                $kapasitas = 500;
                $pct = min(round(($totalDititip / $kapasitas) * 100), 100);
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 p-5" style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
                <div class="flex justify-between items-center mb-2">
                    <p class="font-bold text-gray-800 text-sm">Warehouse Capacity</p>
                    <p class="text-sm font-bold" style="color: #7c3aed">{{ $pct }}%</p>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                    <div class="h-2 rounded-full"
                        style="width: {{ $pct }}%; background: linear-gradient(to right, #5b21b6, #a78bfa)">
                    </div>
                </div>
                <p class="text-xs text-gray-400">{{ $totalDititip }} items from {{ $kapasitas }} total slots used.</p>
            </div>

            {{-- Quick Guide --}}
            <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #1e293b, #334155)">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                        style="background: rgba(255,255,255,0.1)">❓</div>
                    <div>
                        <p class="font-bold text-sm mb-1">Quick Guide</p>
                        <p class="text-xs leading-relaxed" style="color: #94a3b8">
                            Pastikan nomor WhatsApp benar untuk pengiriman bukti digital via Savve Cloud.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Live Activity --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-5" style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
                <p class="text-xs font-bold uppercase tracking-wider mb-3" style="color: #94a3b8">Live Activity</p>
                <div class="space-y-2">
                    @foreach (\App\Models\Transaksi::with('kasir')->latest()->take(3)->get() as $lt)
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full flex-shrink-0"
                                style="background: {{ $lt->status === 'dititip' ? '#a78bfa' : '#d1d5db' }}"></span>
                            <p class="text-xs text-gray-500 truncate">
                                {{ $lt->status === 'dititip' ? 'Saved' : 'Taken' }}: {{ $lt->nomor_transaksi }}
                                ({{ $lt->nama_penitip }})
                            </p>
                        </div>
                    @endforeach
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-gray-200 flex-shrink-0 animate-pulse"></span>
                        <p class="text-xs text-gray-400">Drafting: SVV-{{ now()->format('ymd') }}-...</p>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- END flex container --}}

    {{-- Modal Kamera Penitipan --}}
    <div id="modal-kamera-penitipan" class="hidden fixed inset-0 z-50"
        style="background: rgba(0,0,0,0.85); display: none; align-items: center; justify-content: center;">

        <div
            style="background: white; border-radius: 16px; overflow: hidden; width: fit-content; max-width: 90vw; margin: 0 auto; position: relative;">

            {{-- Header --}}
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; border-bottom: 1px solid #f1f5f9;">
                <p style="font-weight: 900; color: #1f2937; font-size: 14px; margin: 0;">📷 Foto Barang Titipan</p>
                <button onclick="tutupKamera('penitipan')"
                    style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: #f1f5f9; color: #6b7280; border: none; cursor: pointer;">✕</button>
            </div>

            {{-- Video --}}
            <div style="position: relative; background: #0f0f1a;">
                <video id="video-penitipan" autoplay playsinline
                    style="display: block; max-height: 65vh; width: auto; max-width: 90vw; transform: scaleX(-1);"></video>

                {{-- Viewfinder overlay --}}
                <div
                    style="position: absolute; inset: 0; pointer-events: none; display: flex; align-items: center; justify-content: center;">
                    <div style="width: 75%; height: 75%; position: relative;">
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

            <canvas id="canvas-penitipan" class="hidden"></canvas>

            {{-- Tombol --}}
            <div style="padding: 12px 16px;">
                <button type="button" onclick="jepretFoto('penitipan')"
                    style="width: 100%; padding: 12px; border-radius: 12px; color: white; font-weight: 700; font-size: 14px; border: none; cursor: pointer; background: linear-gradient(135deg, #5b21b6, #7c3aed);">
                    📸 Jepret Foto
                </button>
            </div>
        </div>
    </div>

    <script>
        // ── Konfirmasi keluar ──
        let formDirty = false;

        document.getElementById('btn-batal').addEventListener('click', function(e) {
            if (formDirty) {
                e.preventDefault();
                if (confirm('Data yang sudah kamu isi akan hilang. Yakin ingin keluar?')) {
                    formDirty = false;
                    window.location.href = this.href;
                }
            }
        });

        document.getElementById('form-transaksi').addEventListener('change', () => formDirty = true);
        document.getElementById('form-transaksi').addEventListener('input', () => formDirty = true);
        document.getElementById('form-transaksi').addEventListener('submit', () => formDirty = false);
        document.querySelector('button[type="reset"]').addEventListener('click', () => formDirty = false);

        window.addEventListener('beforeunload', function(e) {
            if (formDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // ── Barang ──
        const kategoris = @json($kategoris);
        let index = 1;

        function bindUkuranChange(container) {
            container.querySelectorAll('.ukuran-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    const parentItem = this.closest('.barang-item');
                    parentItem.querySelectorAll('.ukuran-box').forEach(box => {
                        box.style.borderColor = '#ddd6fe';
                        box.style.color = '#94a3b8';
                        box.style.background = 'white';
                    });
                    this.nextElementSibling.style.borderColor = '#7c3aed';
                    this.nextElementSibling.style.color = '#7c3aed';
                    this.nextElementSibling.style.background = '#faf5ff';
                });
            });
        }

        document.querySelectorAll('.barang-item').forEach(item => bindUkuranChange(item));

        function changeQty(btn, delta) {
            const input = btn.parentElement.querySelector('input[type=number]');
            const val = parseInt(input.value) + delta;
            if (val >= 1) input.value = val;
        }

        function bindKategoriChange(select) {
            select.addEventListener('change', function() {
                const wrapper = this.closest('.barang-item').querySelector('.nama-custom-wrapper');
                wrapper.style.display = this.options[this.selectedIndex].dataset.custom == '1' ? 'block' : 'none';
            });
        }
        document.querySelectorAll('.kategori-select').forEach(bindKategoriChange);

        function updatePreview() {
            const nama = document.querySelector('[name=nama_penitip]').value;
            const el = document.getElementById('status-preview');
            if (el) el.textContent = nama ? 'READY TO SAVE' : 'FILL FORM FIRST';
        }

        document.getElementById('tambah-barang').addEventListener('click', function() {
            const container = document.getElementById('barang-container');
            const div = document.createElement('div');
            div.className = 'barang-item rounded-xl p-4 relative';
            div.style.cssText = 'background: #faf5ff; border: 1.5px solid #ede9fe;';
            div.innerHTML = `
            <button type="button" onclick="this.closest('.barang-item').remove()"
                class="absolute top-3 right-3 text-xs font-bold" style="color: #ef4444">✕ Hapus</button>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Nama Barang</label>
                    <select name="barang[${index}][kategori_id]"
                        class="kategori-select w-full rounded-xl px-3 py-2.5 text-sm"
                        style="background: white; border: 1.5px solid #ddd6fe; color: #374151">
                        ${kategoris.map(k => `<option value="${k.id}" data-custom="${k.is_custom}">${k.nama_kategori}</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Quantity</label>
                    <div class="flex items-center gap-2 w-full">
                        <button type="button" onclick="changeQty(this,-1)"
                            class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center font-bold text-lg"
                            style="background:white;border:1.5px solid #ddd6fe;color:#7c3aed;min-width:36px">−</button>
                        <input type="number" name="barang[${index}][jumlah]" value="1" min="1"
                            class="min-w-0 w-full text-center rounded-xl py-2.5 text-sm font-bold"
                            style="background:white;border:1.5px solid #ddd6fe;color:#374151">
                        <button type="button" onclick="changeQty(this,1)"
                            class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center font-bold text-lg"
                            style="background:white;border:1.5px solid #ddd6fe;color:#7c3aed;min-width:36px">+</button>
                    </div>
                </div>
            </div>
            <div class="nama-custom-wrapper mb-3" style="display:none">
                <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Nama Barang (Lainnya)</label>
                <input type="text" name="barang[${index}][nama_custom]"
                    class="w-full rounded-xl px-4 py-3 text-sm"
                    style="background:white;border:1.5px solid #ddd6fe;color:#374151"
                    placeholder="Tulis nama barang...">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Ukuran Barang</label>
                <div class="grid grid-cols-4 gap-2">
                    ${['S','M','L','XL'].map((u,i) => `
                                                                    <label class="ukuran-label cursor-pointer">
                                                                        <input type="radio" name="barang[${index}][ukuran]" value="${u}" class="hidden ukuran-radio" ${i===0?'checked':''}>
                                                                        <div class="ukuran-box border-2 rounded-xl py-2.5 text-center font-bold text-sm transition"
                                                                            style="${i===0?'border-color:#7c3aed;color:#7c3aed;background:white;':'border-color:#ddd6fe;color:#94a3b8;background:white;'}">
                                                                            ${u}
                                                                        </div>
                                                                    </label>`).join('')}
                </div>
            </div>`;
            container.appendChild(div);
            bindUkuranChange(div);
            bindKategoriChange(div.querySelector('.kategori-select'));
            index++;
        });

        function resetForm() {
            index = 1;
            document.getElementById('barang-container').innerHTML = '';
            document.getElementById('tambah-barang').click();
            hapusFoto();
            formDirty = false;
        }

        // ── Generate Nomor Preview ──
        async function generatePreviewNomor() {
            const today = new Date();
            const tanggal =
                `${today.getFullYear()}${String(today.getMonth()+1).padStart(2,'0')}${String(today.getDate()).padStart(2,'0')}`;
            try {
                const res = await fetch('{{ route('kasir.transaksi.count-today') }}');
                const data = await res.json();
                const urutan = String(data.count + 1).padStart(4, '0');
                const el = document.getElementById('nomor-preview');
                if (el) el.textContent = `SVV-${tanggal}-${urutan}`;
            } catch (e) {
                const el = document.getElementById('nomor-preview');
                if (el) el.textContent = `SVV-${new Date().toISOString().slice(0,10).replace(/-/g,'')}-????`;
            }
        }
        generatePreviewNomor();

        // ── Validasi Form ──
        document.getElementById('form-transaksi').addEventListener('submit', function(e) {
            document.querySelectorAll('.error-msg').forEach(el => el.remove());

            let valid = true;

            const eventId = document.getElementById('event_id');
            if (!eventId.value) {
                valid = false;
                showError(eventId, 'Pilih event terlebih dahulu.');
            }

            const namaPenitip = document.querySelector('[name=nama_penitip]');
            if (!namaPenitip.value.trim()) {
                valid = false;
                showError(namaPenitip, 'Nama penitip wajib diisi.');
            }

            const noWa = document.querySelector('[name=no_whatsapp]');
            if (!noWa.value.trim()) {
                valid = false;
                showError(noWa, 'Nomor WhatsApp wajib diisi.');
            } else if (!/^[0-9]{9,15}$/.test(noWa.value.trim())) {
                valid = false;
                showError(noWa, 'Nomor WhatsApp tidak valid (9-15 digit angka).');
            }

            document.querySelectorAll('.barang-item').forEach(function(item) {
                const jumlah = item.querySelector('input[name*="jumlah"]');
                const namaCustomWrapper = item.querySelector('.nama-custom-wrapper');
                const namaCustomInput = item.querySelector('input[name*="nama_custom"]');
                if (namaCustomWrapper?.style.display !== 'none' && !namaCustomInput?.value.trim()) {
                    valid = false;
                    showError(namaCustomInput, 'Nama barang wajib diisi.');
                }
                if (!jumlah?.value || jumlah.value < 1) {
                    valid = false;
                    showError(jumlah, 'Jumlah minimal 1.');
                }
            });

            if (!valid) {
                e.preventDefault();
                document.querySelector('.error-msg')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });

        function showError(input, message) {
            const msg = document.createElement('p');
            msg.className = 'error-msg text-red-500 text-xs mt-1';
            msg.innerHTML = `⚠ ${message}`;
            input.parentNode.insertBefore(msg, input.nextSibling);
        }

        document.addEventListener('input', e => {
            if (['INPUT', 'SELECT'].includes(e.target.tagName)) {
                e.target.parentNode.querySelector('.error-msg')?.remove();
            }
        });

        // ── Kamera Foto Penitipan ──
        let streamPenitipan = null;

        async function bukaKamera(type) {
            const modal = document.getElementById('modal-kamera-' + type);
            modal.style.display = 'flex'; // ← ganti dari classList.remove('hidden')
            try {
                streamPenitipan = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: {
                            ideal: 'environment'
                        },
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                });
            } catch (e) {
                streamPenitipan = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
            }
            const video = document.getElementById('video-' + type);
            video.srcObject = streamPenitipan;
        }

        function tutupKamera(type) {
            streamPenitipan?.getTracks().forEach(t => t.stop());
            streamPenitipan = null;
            document.getElementById('modal-kamera-' + type).style.display = 'none';
        }

        function tutupKamera(type) {
            if (streamPenitipan) {
                streamPenitipan.getTracks().forEach(function(track) {
                    track.stop();
                });
                streamPenitipan = null;
            }
            const modal = document.getElementById('modal-kamera-' + type);
            modal.style.display = 'none';
            modal.classList.add('hidden'); // ← double kill, pastikan tertutup
        }

        function jepretFoto(type) {
            const video = document.getElementById('video-' + type);
            const canvas = document.getElementById('canvas-' + type);

            canvas.width = video.videoWidth || 640;
            canvas.height = video.videoHeight || 480;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.8);

            document.getElementById('foto_penitipan_input').value = dataUrl;
            document.getElementById('foto-preview').src = dataUrl;
            document.getElementById('foto-preview-wrapper').classList.remove('hidden');
            document.getElementById('foto-buttons').classList.add('hidden');

            tutupKamera(type); // ← dipanggil terakhir setelah semua selesai
        }

        function pilihDariGaleri(input) {
            const file = input.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('foto_penitipan_input').value = e.target.result;
                document.getElementById('foto-preview').src = e.target.result;
                document.getElementById('foto-preview-wrapper').classList.remove('hidden');
                document.getElementById('foto-buttons').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        function hapusFoto() {
            document.getElementById('foto_penitipan_input').value = '';
            document.getElementById('foto-preview-wrapper').classList.add('hidden');
            document.getElementById('foto-buttons').classList.remove('hidden');
        }
    </script>

@endsection
