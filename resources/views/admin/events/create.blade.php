@extends('layouts.admin')
@section('title', 'Tambah Event')

@section('content')

<div class="flex justify-between items-start mb-6">
    <div>
        <h1 class="text-2xl font-black text-gray-800">Tambah Event</h1>
        <p class="text-gray-400 text-sm mt-1">Buat event baru dan setting tarif penitipan.</p>
    </div>
    <a href="{{ route('admin.events.index') }}"
        class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 shadow-sm">
        ← Kembali
    </a>
</div>

<div class="flex gap-6">

    {{-- Form --}}
    <div class="flex-1">
        <form action="{{ route('admin.events.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
                <h3 class="font-black text-gray-700 mb-4">Informasi Event</h3>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Nama Event</label>
                    <input type="text" name="nama_event" value="{{ old('nama_event') }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="Contoh: Konser Dewa 19">
                    @error('nama_event') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        @error('tanggal_mulai') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        @error('tanggal_selesai') <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
                <h3 class="font-black text-gray-700 mb-1">Tarif per Ukuran</h3>
                <p class="text-xs text-gray-400 mb-4">Setting harga penitipan berdasarkan ukuran barang.</p>

                <div class="grid grid-cols-2 gap-4">
                    @foreach(['S' => 'Small', 'M' => 'Medium', 'L' => 'Large', 'XL' => 'Extra Large'] as $kode => $label)
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-black">{{ $kode }}</span>
                            <span class="text-xs text-gray-400">{{ $label }}</span>
                        </div>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-semibold">Rp</span>
                            <input type="number" name="tarif[{{ $kode }}]" value="{{ old('tarif.'.$kode) }}"
                                class="w-full bg-white border border-gray-200 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                placeholder="0" min="0">
                        </div>
                        @error('tarif.'.$kode) <p class="text-red-500 text-xs mt-1">⚠ {{ $message }}</p> @enderror
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 py-3.5 rounded-xl text-white font-bold text-sm transition hover:opacity-90"
                    style="background: linear-gradient(135deg, #3730a3, #4f46e5)">
                    💾 Simpan Event
                </button>
                <a href="{{ route('admin.events.index') }}"
                    class="px-6 py-3.5 rounded-xl bg-gray-100 text-gray-600 font-bold text-sm hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{-- Info Panel --}}
    <div class="w-72 space-y-4">
        <div class="rounded-2xl p-6 text-white"
            style="background: linear-gradient(135deg, #3730a3, #6366f1)">
            <div class="text-3xl mb-3">🎪</div>
            <p class="font-black text-lg mb-2">Tips Setting Event</p>
            <ul class="text-indigo-200 text-xs space-y-2 leading-relaxed">
                <li>• Pastikan tanggal mulai dan selesai sudah benar.</li>
                <li>• Tarif akan otomatis digunakan saat kasir input transaksi.</li>
                <li>• Event nonaktif tidak bisa digunakan kasir.</li>
                <li>• Tarif bisa diubah kapan saja melalui menu Edit.</li>
            </ul>
        </div>
    </div>

</div>

@endsection