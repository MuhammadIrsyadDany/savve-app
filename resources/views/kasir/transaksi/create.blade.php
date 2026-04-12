@extends('layouts.kasir')
@section('title', 'Transaksi Penitipan Baru')

@section('content')

{{-- Banner Notifikasi --}}
@if($events->count() > 0)
<div id="banner" class="flex items-center justify-between bg-white border border-indigo-200 rounded-2xl px-5 py-3 mb-6 shadow-sm">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white text-sm">✓</div>
        <div>
            <p class="font-bold text-gray-800 text-sm">Sistem Siap</p>
            <p class="text-xs text-gray-400">Input transaksi baru sekarang untuk Event: {{ $events->first()->nama_event }}</p>
        </div>
    </div>
    <button onclick="document.getElementById('banner').remove()" class="text-gray-300 hover:text-gray-500 text-lg">✕</button>
</div>
@endif

<div class="flex gap-6">

    {{-- Form Kiri --}}
    <div class="flex-1">
        <form action="{{ route('kasir.transaksi.store') }}" method="POST" id="form-transaksi">
            @csrf

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

                {{-- Title --}}
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-xl font-black text-gray-800">Transaksi Penitipan Baru</h1>
                    <span class="flex items-center gap-1.5 text-xs font-semibold text-green-600 bg-green-50 px-3 py-1.5 rounded-full">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse inline-block"></span>
                        LIVE TERMINAL
                    </span>
                </div>

                {{-- Pilih Event --}}
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Pilih Event</label>
                    <select name="event_id" id="event_id"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 appearance-none"
                        onchange="updatePreview()">
                        <option value="">-- Pilih Event --</option>
                        @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                            {{ $event->nama_event }}
                        </option>
                        @endforeach
                    </select>
                    @error('event_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Nama Penitip & WhatsApp --}}
                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nama Penitip</label>
                        <input type="text" name="nama_penitip" value="{{ old('nama_penitip') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            placeholder="Contoh: Budi Santoso"
                            oninput="updatePreview()">
                        @error('nama_penitip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">No WhatsApp</label>
                        <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            placeholder="081234567890">
                        @error('no_whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Daftar Barang --}}
                <div id="barang-container" class="space-y-4 mb-5">
                    <div class="barang-item">
                        {{-- Nama Barang & Quantity --}}
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nama Barang</label>
                                <select name="barang[0][kategori_id]"
                                    class="kategori-select w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                    @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}" data-custom="{{ $k->is_custom }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Quantity</label>
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="changeQty(this, -1)"
                                        class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-200 font-bold text-lg flex-shrink-0">−</button>
                                    <input type="number" name="barang[0][jumlah]" value="1" min="1"
                                        class="flex-1 text-center bg-gray-50 border border-gray-200 rounded-xl py-2.5 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                    <button type="button" onclick="changeQty(this, 1)"
                                        class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-200 font-bold text-lg flex-shrink-0">+</button>
                                </div>
                            </div>
                        </div>

                        {{-- Nama Custom --}}
                        <div class="nama-custom-wrapper mb-3" style="display:none">
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nama Barang (Lainnya)</label>
                            <input type="text" name="barang[0][nama_custom]"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                placeholder="Tulis nama barang...">
                        </div>

                        {{-- Ukuran --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Ukuran Barang</label>
                            <div class="grid grid-cols-4 gap-3">
                                @foreach(['S','M','L','XL'] as $u)
                                <label class="ukuran-label cursor-pointer">
                                    <input type="radio" name="barang[0][ukuran]" value="{{ $u }}"
                                        class="hidden ukuran-radio" {{ $u === 'S' ? 'checked' : '' }}>
                                    <div class="ukuran-box border-2 border-gray-200 rounded-xl py-3 text-center font-bold text-sm text-gray-500 hover:border-indigo-400 hover:text-indigo-600 transition
                                        {{ $u === 'S' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : '' }}">
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
                    class="w-full py-2.5 border-2 border-dashed border-gray-200 rounded-xl text-sm text-gray-400 hover:border-indigo-300 hover:text-indigo-500 transition mb-6">
                    + Tambah Barang Lain
                </button>

                {{-- Tombol Aksi --}}
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #3730a3, #4f46e5)">
                        💾 Simpan Transaksi
                    </button>
                    <button type="button"
                        class="flex items-center justify-center gap-2 px-6 py-3.5 bg-gray-100 rounded-xl text-gray-600 font-bold text-sm hover:bg-gray-200 transition">
                        🖨️ Cetak Nota
                    </button>
                </div>

                {{-- Reset --}}
                <div class="text-center mt-3">
                    <button type="reset" onclick="resetForm()"
                        class="text-sm text-gray-400 hover:text-gray-600 flex items-center gap-1 mx-auto">
                        🔄 Reset
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- Panel Kanan --}}
    <div class="w-72 space-y-4">

        {{-- Transaction Number Preview --}}
        <div class="rounded-2xl p-6 text-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #3730a3, #6366f1)">
            <p class="text-xs font-semibold text-indigo-300 uppercase tracking-widest mb-2">Transaction Number Preview</p>
            <p id="nomor-preview" class="text-4xl font-black tracking-tight leading-tight mb-4">
                SVV-{{ now()->format('ymd') }}-????
            </p>
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs text-indigo-300 uppercase tracking-wider">Status</p>
                    <p class="font-bold text-white" id="status-preview">READY TO SAVE</p>
                </div>
                <div class="text-4xl opacity-20">⊞</div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/10 rounded-full"></div>
        </div>

        {{-- Warehouse Capacity --}}
        @php
            $totalDititip = \App\Models\DetailTransaksi::whereHas('transaksi', fn($q) => $q->where('status','dititip'))->sum('jumlah');
            $kapasitas = 500;
            $pct = min(round(($totalDititip / $kapasitas) * 100), 100);
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex justify-between items-center mb-2">
                <p class="font-bold text-gray-800 text-sm">Warehouse Capacity</p>
                <p class="text-sm font-bold text-indigo-600">{{ $pct }}% aull</p>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                <div class="h-2 rounded-full transition-all"
                    style="width: {{ $pct }}%; background: linear-gradient(to right, #4f46e5, #818cf8)"></div>
            </div>
            <p class="text-xs text-gray-400">{{ $totalDititip }} items from {{ $kapasitas }} total slots used.</p>
        </div>

        {{-- Quick Guide --}}
        <div class="rounded-2xl p-5 text-white"
            style="background: linear-gradient(135deg, #1e293b, #334155)">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">❓</div>
                <div>
                    <p class="font-bold text-sm mb-1">Quick Guide</p>
                    <p class="text-xs text-gray-400 leading-relaxed">Ensure No WhatsApp is correct for automatic digital receipt delivery via Savve Cloud.</p>
                    <button class="text-xs text-indigo-400 font-semibold mt-2 hover:text-indigo-300">View shortcuts →</button>
                </div>
            </div>
        </div>

        {{-- Live Activity --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Live Activity</p>
            <div class="space-y-2">
                @foreach(\App\Models\Transaksi::with('kasir')->latest()->take(3)->get() as $lt)
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full flex-shrink-0
                        {{ $lt->status === 'dititip' ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                    <p class="text-xs text-gray-500 truncate">
                        {{ $lt->status === 'dititip' ? 'Saved' : 'Taken' }}: {{ $lt->nomor_transaksi }} ({{ $lt->nama_penitip }})
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
</div>

<script>
    const kategoris = @json($kategoris);
    let index = 1;

    // Ukuran radio styling
    document.querySelectorAll('.ukuran-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const container = this.closest('.barang-item');
            container.querySelectorAll('.ukuran-box').forEach(box => {
                box.classList.remove('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
                box.classList.add('border-gray-200', 'text-gray-500');
            });
            this.nextElementSibling.classList.add('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
            this.nextElementSibling.classList.remove('border-gray-200', 'text-gray-500');
        });
    });

    // Quantity +/-
    function changeQty(btn, delta) {
        const input = btn.parentElement.querySelector('input[type=number]');
        const val = parseInt(input.value) + delta;
        if (val >= 1) input.value = val;
    }

    // Kategori change → show/hide custom
    function bindKategoriChange(select) {
        select.addEventListener('change', function() {
            const wrapper = this.closest('.barang-item').querySelector('.nama-custom-wrapper');
            const selected = this.options[this.selectedIndex];
            wrapper.style.display = selected.dataset.custom == '1' ? 'block' : 'none';
        });
    }
    document.querySelectorAll('.kategori-select').forEach(bindKategoriChange);

    // Preview update
    function updatePreview() {
        const nama = document.querySelector('[name=nama_penitip]').value;
        const statusEl = document.getElementById('status-preview');
        statusEl.textContent = nama ? 'READY TO SAVE' : 'FILL FORM FIRST';
    }

    // Tambah barang
    document.getElementById('tambah-barang').addEventListener('click', function() {
        const container = document.getElementById('barang-container');
        const div = document.createElement('div');
        div.className = 'barang-item border-t border-gray-100 pt-4 relative';
        div.innerHTML = `
            <button type="button" onclick="this.closest('.barang-item').remove()"
                class="absolute top-4 right-0 text-red-400 hover:text-red-600 text-xs font-semibold">✕ Hapus</button>
            <div class="grid grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nama Barang</label>
                    <select name="barang[${index}][kategori_id]"
                        class="kategori-select w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        ${kategoris.map(k => `<option value="${k.id}" data-custom="${k.is_custom}">${k.nama_kategori}</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Quantity</label>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="changeQty(this, -1)"
                            class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-200 font-bold text-lg flex-shrink-0">−</button>
                        <input type="number" name="barang[${index}][jumlah]" value="1" min="1"
                            class="flex-1 text-center bg-gray-50 border border-gray-200 rounded-xl py-2.5 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <button type="button" onclick="changeQty(this, 1)"
                            class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-500 hover:bg-gray-200 font-bold text-lg flex-shrink-0">+</button>
                    </div>
                </div>
            </div>
            <div class="nama-custom-wrapper mb-3" style="display:none">
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nama Barang (Lainnya)</label>
                <input type="text" name="barang[${index}][nama_custom]"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Tulis nama barang...">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Ukuran Barang</label>
                <div class="grid grid-cols-4 gap-3">
                    ${['S','M','L','XL'].map((u, i) => `
                    <label class="ukuran-label cursor-pointer">
                        <input type="radio" name="barang[${index}][ukuran]" value="${u}" class="hidden ukuran-radio" ${i===0?'checked':''}>
                        <div class="ukuran-box border-2 ${i===0?'border-indigo-500 text-indigo-600 bg-indigo-50':'border-gray-200 text-gray-500'} rounded-xl py-3 text-center font-bold text-sm hover:border-indigo-400 hover:text-indigo-600 transition">${u}</div>
                    </label>`).join('')}
                </div>
            </div>
        `;
        container.appendChild(div);
        bindKategoriChange(div.querySelector('.kategori-select'));
        div.querySelectorAll('.ukuran-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                const c = this.closest('.barang-item');
                c.querySelectorAll('.ukuran-box').forEach(box => {
                    box.classList.remove('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
                    box.classList.add('border-gray-200', 'text-gray-500');
                });
                this.nextElementSibling.classList.add('border-indigo-500', 'text-indigo-600', 'bg-indigo-50');
                this.nextElementSibling.classList.remove('border-gray-200', 'text-gray-500');
            });
        });
        index++;
    });

    function resetForm() {
        index = 1;
        document.getElementById('barang-container').innerHTML = '';
        document.getElementById('tambah-barang').click();
    }

    // Generate preview nomor transaksi
    async function generatePreviewNomor() {
        const today = new Date();
        const y = today.getFullYear();
        const m = String(today.getMonth() + 1).padStart(2, '0');
        const d = String(today.getDate()).padStart(2, '0');
        const tanggal = `${y}${m}${d}`;

        try {
            const res = await fetch('{{ route("kasir.transaksi.count-today") }}');
            const data = await res.json();
            const urutan = String(data.count + 1).padStart(4, '0');
            document.getElementById('nomor-preview').textContent = `SVV-${tanggal}-${urutan}`;
        } catch(e) {
            document.getElementById('nomor-preview').textContent = `SVV-${tanggal}-????`;
        }
    }
    generatePreviewNomor();
</script>

@endsection