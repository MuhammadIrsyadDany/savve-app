<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Tarif;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_event'      => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tarif.S'         => 'required|integer|min:0',
            'tarif.M'         => 'required|integer|min:0',
            'tarif.L'         => 'required|integer|min:0',
            'tarif.XL'        => 'required|integer|min:0',
        ]);

        $event = Event::create([
            'nama_event'      => $request->nama_event,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status'          => 'aktif',
        ]);

        foreach (['S', 'M', 'L', 'XL'] as $ukuran) {
            Tarif::create([
                'event_id' => $event->id,
                'ukuran'   => $ukuran,
                'harga'    => $request->tarif[$ukuran],
            ]);
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    public function edit(Event $event)
    {
        $tarifs = $event->tarifs->keyBy('ukuran');
        return view('admin.events.edit', compact('event', 'tarifs'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'nama_event'      => 'required|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tarif.S'         => 'required|integer|min:0',
            'tarif.M'         => 'required|integer|min:0',
            'tarif.L'         => 'required|integer|min:0',
            'tarif.XL'        => 'required|integer|min:0',
        ]);

        $event->update([
            'nama_event'      => $request->nama_event,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status'          => $request->status,
        ]);

        foreach (['S', 'M', 'L', 'XL'] as $ukuran) {
            Tarif::updateOrCreate(
                ['event_id' => $event->id, 'ukuran' => $ukuran],
                ['harga' => $request->tarif[$ukuran]]
            );
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diupdate.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
