@extends('layouts.admin')
@section('title', 'Tambah Event')

@section('content')
<div class="max-w-2xl bg-white rounded-xl shadow p-6">
    <form action="{{ route('admin.events.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Event</label>
            <input type="text" name="nama_event" value="{{ old('nama_event') }}"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Contoh: Konser Dewa 19">
            @error('nama_event') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('tanggal_mulai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('tanggal_selesai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tarif per Ukuran (Rp)</label>
            <div class="grid grid-cols-2 gap-4">
                @foreach(['S', 'M', 'L', 'XL'] as $ukuran)
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Ukuran {{ $ukuran }}</label>
                    <input type="number" name="tarif[{{ $ukuran }}]" value="{{ old('tarif.'.$ukuran) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="0" min="0">
                    @error('tarif.'.$ukuran) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Simpan Event
            </button>
            <a href="{{ route('admin.events.index') }}" class="px-6 py-2 rounded-lg border hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection