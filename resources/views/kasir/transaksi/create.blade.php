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

<div class="flex flex-col lg:flex-row gap-6">

    {{-- Panel Kanan — tampil di atas form saat mobile --}}
    <div class="w-full lg:hidden space-y-3">
        {{-- Transaction Number Preview (mobile only) --}}
        <div class="rounded-2xl p-5 text-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #1e1035, #4c1d95)">
            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #c4b5fd">Transaction Number Preview</p>
            <p id="nomor-preview-mobile" class="text-2xl font-black tracking-tight leading-tight mb-3 font-mono">
                SVV-{{ now()->format('ymd') }}-????
            </p>
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs uppercase tracking-wider" style="color: rgba(255,255,255,0.5)">Status</p>
                    <p class="font-bold text-white text-sm" id="status-preview-mobile">READY TO SAVE</p>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-20 h-20 rounded-full" style="background: rgba(255,255,255,0.05)"></div>
        </div>
    </div>

    {{-- Form Kiri --}}
    <div class="flex-1">
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
                    <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Pilih Event</label>
                    <select name="event_id" id="event_id"
                        class="w-full rounded-xl px-4 py-3 text-sm transition"
                        style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #374151"
                        onchange="updatePreview()"
                        onfocus="this.style.borderColor='#a78bfa'" onblur="this.style.borderColor='#ede9fe'">
                        <option value="">-- Pilih Event --</option>
                        @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                            {{ $event->nama_event }}
                        </option>
                        @endforeach
                    </select>
                    @error('event_id') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                {{-- Nama Penitip & WhatsApp --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Nama Penitip</label>
                        <input type="text" name="nama_penitip" value="{{ old('nama_penitip') }}"
                            class="w-full rounded-xl px-4 py-3 text-sm transition"
                            style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #374151"
                            placeholder="Contoh: Budi Santoso"
                            oninput="updatePreview()"
                            onfocus="this.style.borderColor='#a78bfa'" onblur="this.style.borderColor='#ede9fe'">
                        @error('nama_penitip') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">No WhatsApp</label>
                        <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                            class="w-full rounded-xl px-4 py-3 text-sm transition"
                            style="background: #faf5ff; border: 1.5px solid #ede9fe; color: #374151"
                            placeholder="081234567890"
                            onfocus="this.style.borderColor='#a78bfa'" onblur="this.style.borderColor='#ede9fe'">
                        @error('no_whatsapp') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Daftar Barang --}}
                <div id="barang-container" class="space-y-4 mb-4">
                    <div class="barang-item rounded-xl p-4" style="background: #faf5ff; border: 1.5px solid #ede9fe">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Nama Barang</label>
                                <select name="barang[0][kategori_id]"
                                    class="kategori-select w-full rounded-xl px-3 py-2.5 text-sm"
                                    style="background: white; border: 1.5px solid #ddd6fe; color: #374151">
                                    @foreach($kategoris as $k)
                                    <option value="{{ $k->id }}" data-custom="{{ $k->is_custom }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Quantity</label>
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
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Nama Barang (Lainnya)</label>
                            <input type="text" name="barang[0][nama_custom]"
                                class="w-full rounded-xl px-4 py-3 text-sm"
                                style="background: white; border: 1.5px solid #ddd6fe; color: #374151"
                                placeholder="Tulis nama barang...">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">Ukuran Barang</label>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach(['S','M','L','XL'] as $u)
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

                {{-- Tombol Aksi --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #5b21b6, #7c3aed); box-shadow: 0 4px 12px rgba(91,33,182,0.25)">
                        💾 Simpan Transaksi
                    </button>
                    <button type="button"
                        class="flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl font-bold text-sm transition"
                        style="background: #faf5ff; color: #7c3aed; border: 1.5px solid #ede9fe">
                    🖨️ Cetak Nota
                    </button>
                </div>

                <div class="flex items-center justify-between mt-3">
                    <button type="reset" onclick="resetForm()"
                        class="text-sm flex items-center gap-1 transition"
                        style="color: #94a3b8">
                        🔄 Reset
                    </button>
                <a href="{{ route('kasir.dashboard') }}"
                    id="btn-batal"
                    class="text-sm flex items-center gap-1 font-semibold transition"
                    style="color: #ef4444">
                    ✕ Batal & Kembali
                </a>
                </div>

            </div>
        </form>
    </div>

    {{-- Panel Kanan — hanya tampil di desktop --}}
    <div class="hidden lg:flex w-72 flex-shrink-0 flex-col space-y-4">

        {{-- Transaction Number Preview --}}
        <div class="rounded-2xl p-6 text-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #1e1035, #4c1d95)">
            <p class="text-xs font-semibold uppercase tracking-widest mb-2" style="color: #c4b5fd">Transaction Number Preview</p>
            <p id="nomor-preview" class="text-3xl font-black tracking-tight leading-tight mb-4 font-mono">
                SVV-{{ now()->format('ymd') }}-????
            </p>
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs uppercase tracking-wider" style="color: rgba(255,255,255,0.5)">Status</p>
                    <p class="font-bold text-white" id="status-preview">READY TO SAVE</p>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full" style="background: rgba(255,255,255,0.05)"></div>
        </div>

        {{-- Warehouse Capacity --}}
        @php
            $totalDititip = \App\Models\DetailTransaksi::whereHas('transaksi', fn($q) => $q->where('status','dititip'))->sum('jumlah');
            $kapasitas = 500;
            $pct = min(round(($totalDititip / $kapasitas) * 100), 100);
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 p-5"
            style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
            <div class="flex justify-between items-center mb-2">
                <p class="font-bold text-gray-800 text-sm">Warehouse Capacity</p>
                <p class="text-sm font-bold" style="color: #7c3aed">{{ $pct }}%</p>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                <div class="h-2 rounded-full" style="width: {{ $pct }}%; background: linear-gradient(to right, #5b21b6, #a78bfa)"></div>
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
                    <p class="text-xs leading-relaxed" style="color: #94a3b8">Ensure No WhatsApp is correct for automatic digital receipt delivery via Savve Cloud.</p>
                </div>
            </div>
        </div>

        {{-- Live Activity --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5"
            style="box-shadow: 0 2px 12px rgba(0,0,0,0.04)">
            <p class="text-xs font-bold uppercase tracking-wider mb-3" style="color: #94a3b8">Live Activity</p>
            <div class="space-y-2">
                @foreach(\App\Models\Transaksi::with('kasir')->latest()->take(3)->get() as $lt)
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full flex-shrink-0"
                        style="background: {{ $lt->status === 'dititip' ? '#a78bfa' : '#d1d5db' }}"></span>
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
    // ── Konfirmasi sebelum keluar form ──
let formDirty = false;
function konfirmasiKeluar(e, url) {
    if (formDirty) {
        e.preventDefault();
        if (confirm('Data yang sudah kamu isi akan hilang. Yakin ingin keluar?')) {
            formDirty = false;
            window.location.href = url;
        }
        return false;
    }
    return true;
}

// Bind ke tombol Batal
document.getElementById('btn-batal').addEventListener('click', function(e) {
    konfirmasiKeluar(e, this.href);
});

// Tandai form sudah diisi jika ada perubahan
document.getElementById('form-transaksi').addEventListener('change', function() {
    formDirty = true;
});
document.getElementById('form-transaksi').addEventListener('input', function() {
    formDirty = true;
});

// Tampilkan konfirmasi saat refresh/close tab
window.addEventListener('beforeunload', function(e) {
    if (formDirty) {
        e.preventDefault();
        e.returnValue = 'Data yang sudah kamu isi akan hilang. Yakin ingin keluar?';
        return e.returnValue;
    }
});

// Reset flag saat form di-submit (supaya tidak muncul saat simpan)
document.getElementById('form-transaksi').addEventListener('submit', function() {
    formDirty = false;
});

// Reset flag saat klik tombol Reset
document.querySelector('button[type="reset"]').addEventListener('click', function() {
    formDirty = false;
});
    
    const kategoris = @json($kategoris);
    let index = 1;

    // Ukuran radio styling — fix scope per barang-item
function bindUkuranChange(container) {
    const radios = container.querySelectorAll('.ukuran-radio');
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Reset semua ukuran di dalam barang-item ini saja
            const parentItem = this.closest('.barang-item');
            parentItem.querySelectorAll('.ukuran-box').forEach(box => {
                box.classList.remove('border-purple-500', 'text-purple-600', 'bg-purple-50');
                box.classList.add('border-gray-200', 'text-gray-500');
                box.style.borderColor = '#e5e7eb';
                box.style.color = '#6b7280';
                box.style.background = '';
            });
            // Aktifkan hanya yang dipilih
            const selectedBox = this.nextElementSibling;
            selectedBox.style.borderColor = '#7c3aed';
            selectedBox.style.color = '#7c3aed';
            selectedBox.style.background = '#faf5ff';
        });
    });
}

// Bind ke semua barang-item yang sudah ada
document.querySelectorAll('.barang-item').forEach(item => {
    bindUkuranChange(item);
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
                    <label class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #64748b">Quantity</label>
                    <div class="flex items-center gap-2 w-full">
                        <button type="button" onclick="changeQty(this, -1)"
                            class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center font-bold text-lg transition"
                            style="background: #faf5ff; border: 1.5px solid #ddd6fe; color: #7c3aed; min-width: 36px">−</button>
                        <input type="number" name="barang[${index}][jumlah]" value="1" min="1"
                            class="min-w-0 w-full text-center rounded-xl py-2 text-sm font-bold"
                            style="background: white; border: 1.5px solid #ddd6fe; color: #374151">
                        <button type="button" onclick="changeQty(this, 1)"
                            class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center font-bold text-lg transition"
                            style="background: #faf5ff; border: 1.5px solid #ddd6fe; color: #7c3aed; min-width: 36px">+</button>
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
                    <div class="ukuran-box border-2 rounded-xl py-3 text-center font-bold text-sm transition"
                        style="${i===0 ? 'border-color: #7c3aed; color: #7c3aed; background: #faf5ff;' : 'border-color: #e5e7eb; color: #6b7280;'}">
                ${u}
            </div>
        </label>`).join('')}
                </div>
            </div>
        `;
        container.appendChild(div);
        bindUkuranChange(div);
        bindKategoriChange(div.querySelector('.kategori-select'));
        div.querySelectorAll('.ukuran-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                const c = this.closest('.barang-item');
                c.querySelectorAll('.ukuran-box').forEach(box => {
                    box.classList.remove('border-purple-500', 'text-purple-600', 'bg-purple-50');
                    box.classList.add('border-purple-500', 'text-purple-600', 'bg-purple-50');
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
    // Validasi form sebelum submit
document.getElementById('form-transaksi').addEventListener('submit', function(e) {
    let valid = true;
    let pesanError = [];

    // Reset semua error sebelumnya
    document.querySelectorAll('.error-msg').forEach(el => el.remove());
    document.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500');
    });

    // Validasi event
    const eventId = document.getElementById('event_id');
    if (!eventId.value) {
        valid = false;
        showError(eventId, 'Pilih event terlebih dahulu.');
    }

    // Validasi nama penitip
    const namaPenitip = document.querySelector('[name=nama_penitip]');
    if (!namaPenitip.value.trim()) {
        valid = false;
        showError(namaPenitip, 'Nama penitip wajib diisi.');
    }

    // Validasi no whatsapp
    const noWa = document.querySelector('[name=no_whatsapp]');
    if (!noWa.value.trim()) {
        valid = false;
        showError(noWa, 'Nomor WhatsApp wajib diisi.');
    } else if (!/^[0-9]{9,15}$/.test(noWa.value.trim())) {
        valid = false;
        showError(noWa, 'Nomor WhatsApp tidak valid (9-15 digit angka).');
    }

    // Validasi setiap barang
    document.querySelectorAll('.barang-item').forEach(function(item, i) {
        const kategori = item.querySelector('select[name*="kategori_id"]');
        const jumlah = item.querySelector('input[name*="jumlah"]');
        const namaCustomWrapper = item.querySelector('.nama-custom-wrapper');
        const namaCustomInput = item.querySelector('input[name*="nama_custom"]');
        const ukuranChecked = item.querySelector('input[name*="ukuran"]:checked');

        // Cek nama custom kalau kategori Lainnya
        if (namaCustomWrapper && namaCustomWrapper.style.display !== 'none') {
            if (!namaCustomInput.value.trim()) {
                valid = false;
                showError(namaCustomInput, 'Nama barang wajib diisi.');
            }
        }

        // Cek jumlah
        if (!jumlah.value || jumlah.value < 1) {
            valid = false;
            showError(jumlah, 'Jumlah minimal 1.');
        }

        // Cek ukuran
        if (!ukuranChecked) {
            valid = false;
            const ukuranContainer = item.querySelector('.grid.grid-cols-4');
            showErrorDiv(ukuranContainer, 'Pilih ukuran barang.');
        }
    });

    if (!valid) {
        e.preventDefault();

        // Scroll ke error pertama
        const firstError = document.querySelector('.error-msg');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
});

function showError(input, message) {
    input.classList.add('border-red-500', 'focus:ring-red-400');
    input.classList.remove('border-gray-200');

    const msg = document.createElement('p');
    msg.className = 'error-msg text-red-500 text-xs mt-1 flex items-center gap-1';
    msg.innerHTML = `⚠ ${message}`;
    input.parentNode.insertBefore(msg, input.nextSibling);
}

function showErrorDiv(container, message) {
    const msg = document.createElement('p');
    msg.className = 'error-msg text-red-500 text-xs mt-1 flex items-center gap-1';
    msg.innerHTML = `⚠ ${message}`;
    container.parentNode.insertBefore(msg, container.nextSibling);
}

// Real-time validation — hilangkan error saat user mulai mengisi
document.addEventListener('input', function(e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT') {
        e.target.classList.remove('border-red-500');
        const errorMsg = e.target.parentNode.querySelector('.error-msg');
        if (errorMsg) errorMsg.remove();
    }
});

document.addEventListener('change', function(e) {
    if (e.target.tagName === 'SELECT') {
        e.target.classList.remove('border-red-500');
        const errorMsg = e.target.parentNode.querySelector('.error-msg');
        if (errorMsg) errorMsg.remove();
    }
});
</script>

@endsection