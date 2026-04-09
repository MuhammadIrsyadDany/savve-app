@extends('layouts.kasir')
@section('title', 'Titip Barang')

@section('content')
<div class="max-w-2xl bg-white rounded-xl shadow p-6">
    <form action="{{ route('kasir.transaksi.store') }}" method="POST" id="form-transaksi">
        @csrf

        {{-- Pilih Event --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
            <select name="event_id" id="event_id"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">-- Pilih Event --</option>
                @foreach($events as $event)
                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                    {{ $event->nama_event }}
                </option>
                @endforeach
            </select>
            @error('event_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Data Penitip --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penitip</label>
            <input type="text" name="nama_penitip" value="{{ old('nama_penitip') }}"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder="Nama lengkap penitip">
            @error('nama_penitip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
            <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                placeholder="08xxxxxxxxxx">
            @error('no_whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Daftar Barang --}}
        <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-medium text-gray-700">Daftar Barang</label>
                <button type="button" id="tambah-barang"
                    class="text-sm bg-green-600 text-white px-3 py-1 rounded-lg hover:bg-green-700">
                    + Tambah Barang
                </button>
            </div>

            <div id="barang-container" class="space-y-3">
                {{-- Item barang pertama --}}
                <div class="barang-item border rounded-lg p-4 bg-gray-50">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Kategori</label>
                            <select name="barang[0][kategori_id]"
                                class="kategori-select w-full border rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                @foreach($kategoris as $k)
                                <option value="{{ $k->id }}" data-custom="{{ $k->is_custom }}">
                                    {{ $k->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="nama-custom-wrapper" style="display:none">
                            <label class="block text-xs text-gray-500 mb-1">Nama Barang</label>
                            <input type="text" name="barang[0][nama_custom]"
                                class="w-full border rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"
                                placeholder="Tulis nama barang">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Ukuran</label>
                            <select name="barang[0][ukuran]"
                                class="w-full border rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Jumlah</label>
                            <input type="number" name="barang[0][jumlah]" value="1" min="1"
                                class="w-full border rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                Simpan Transaksi
            </button>
            <a href="{{ route('kasir.dashboard') }}" class="px-6 py-2 rounded-lg border hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    let index = 1;
    const kategoris = @json($kategoris);

    document.getElementById('tambah-barang').addEventListener('click', function () {
        const container = document.getElementById('barang-container');
        const div = document.createElement('div');
        div.className = 'barang-item border rounded-lg p-4 bg-gray-50 relative';
        div.innerHTML = `
            <button type="button" class="hapus-barang absolute top-2 right-2 text-red-500 hover:text-red-700 text-xs">✕ Hapus</button>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Kategori</label>
                    <select name="barang[${index}][kategori_id]" class="kategori-select w-full border rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        ${kategoris.map(k => `<option value="${k.id}" data-custom="${k.is_custom}">${k.nama_kategori}</option>`).join('')}
                    </select>
                </div>
                <div class="nama-custom-wrapper" style="display:none">
                    <label class="block text-xs text-gray-500 mb-1">Nama Barang</label>
                    <input type="text" name="barang[${index}][nama_custom]" class="w-full border rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="Tulis nama barang">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Ukuran</label>
                    <select name="barang[${index}][ukuran]" class="w-full border rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Jumlah</label>
                    <input type="number" name="barang[${index}][jumlah]" value="1" min="1" class="w-full border rounded-lg px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>
        `;
        container.appendChild(div);

        div.querySelector('.hapus-barang').addEventListener('click', function () {
            div.remove();
        });

        bindKategoriChange(div.querySelector('.kategori-select'));
        index++;
    });

    function bindKategoriChange(select) {
        select.addEventListener('change', function () {
            const wrapper = this.closest('.barang-item').querySelector('.nama-custom-wrapper');
            const selected = this.options[this.selectedIndex];
            wrapper.style.display = selected.dataset.custom == '1' ? 'block' : 'none';
        });
    }

    document.querySelectorAll('.kategori-select').forEach(bindKategoriChange);
</script>
@endsection