{{-- Kategori (Ukuran) --}}
<div class="mb-3">
    <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">
        Kategori (Ukuran)
    </label>
    <div class="grid grid-cols-4 gap-2">
        @foreach (['S', 'M', 'L', 'XL'] as $u)
            <label class="cursor-pointer">
                <input type="radio" name="items[{{ $index }}][ukuran]" value="{{ $u }}"
                    class="hidden ukuran-radio" {{ $u === 'S' ? 'checked' : '' }}>
                <div class="ukuran-box border-2 rounded-xl py-2.5 text-center font-bold text-sm transition"
                    style="{{ $u === 'S'
                        ? 'border-color:#7c3aed;color:#7c3aed;background:white;'
                        : 'border-color:#ddd6fe;color:#94a3b8;background:white;' }}">
                    {{ $u }}
                    <div class="text-xs font-normal mt-0.5" style="color: {{ $u === 'S' ? '#a78bfa' : '#cbd5e1' }}">
                        Rp {{ number_format($tarifs[$u]->harga ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </label>
        @endforeach
    </div>
</div>

{{-- Jenis Barang --}}
<div>
    <label class="block text-xs font-bold uppercase tracking-wider mb-2" style="color: #64748b">
        Jenis Barang
        <span class="font-normal normal-case ml-1" style="color: #94a3b8">(pilih satu atau lebih)</span>
    </label>
    <div class="jenis-container grid grid-cols-2 gap-2">
        @foreach ($jenisBarangs['S'] ?? [] as $jenis)
            <label class="flex items-center gap-2 px-3 py-2.5 rounded-xl cursor-pointer transition jenis-item"
                style="background: white; border: 1.5px solid #ddd6fe">
                <input type="checkbox" name="items[{{ $index }}][jenis_barang][]" value="{{ $jenis['nama'] }}"
                    class="jenis-checkbox" style="accent-color: #7c3aed; width: 14px; height: 14px; flex-shrink: 0">
                <span class="text-xs font-semibold text-gray-700">{{ $jenis['nama'] }}</span>
            </label>
        @endforeach
    </div>
</div>
