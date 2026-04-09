@extends('layouts.admin')
@section('title', 'Kelola Event')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Daftar Event</h2>
    <a href="{{ route('admin.events.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        + Tambah Event
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left">Nama Event</th>
                <th class="px-4 py-3 text-left">Tanggal</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($events as $event)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $event->nama_event }}</td>
                <td class="px-4 py-3 text-gray-500">
                    {{ $event->tanggal_mulai->format('d M Y') }} — {{ $event->tanggal_selesai->format('d M Y') }}
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $event->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 flex gap-2">
                    <a href="{{ route('admin.events.edit', $event) }}"
                        class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                        onsubmit="return confirm('Hapus event ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada event.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $events->links() }}</div>
</div>
@endsection