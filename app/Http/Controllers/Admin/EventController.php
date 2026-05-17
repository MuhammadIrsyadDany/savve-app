<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Tarif;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('transaksis')
            ->with(['transaksis.details', 'tarifs'])
            ->latest()
            ->paginate(10);

        // Summary keseluruhan
        $totalEventAktif    = Event::where('status', 'aktif')->count();
        $totalEventSelesai  = Event::where('status', 'nonaktif')->count();
        $totalPendapatan    = Transaksi::where('status', 'sudah_diambil')->get()->sum(fn($t) => $t->total_harga);
        $totalTransaksi     = Transaksi::count();

        return view('admin.events.index', compact(
            'events',
            'totalEventAktif',
            'totalEventSelesai',
            'totalPendapatan',
            'totalTransaksi'
        ));
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
            'status'          => 'required|in:aktif,nonaktif',
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

    public function rekap(Event $event)
    {
        $event->load(['transaksis.details.kategori', 'transaksis.kasir', 'tarifs']);

        $transaksis      = $event->transaksis;
        $totalTransaksi  = $transaksis->count();
        $totalDititip    = $transaksis->where('status', 'dititip')->count();
        $totalTerlambat  = $transaksis->where('status', 'terlambat')->count();
        $totalDiambil    = $transaksis->where('status', 'sudah_diambil')->count();
        $totalPendapatan = $transaksis->sum(fn($t) => $t->total_harga);
        $totalBarang     = $transaksis->sum(fn($t) => $t->details->sum('jumlah'));

        // Rekap per ukuran
        $rekapUkuran = [];
        foreach (['S', 'M', 'L', 'XL'] as $ukuran) {
            $jumlah     = $transaksis->sum(fn($t) => $t->details->where('ukuran', $ukuran)->sum('jumlah'));
            $pendapatan = $transaksis->sum(fn($t) => $t->details->where('ukuran', $ukuran)->sum('subtotal'));
            $rekapUkuran[$ukuran] = [
                'jumlah'     => $jumlah,
                'pendapatan' => $pendapatan,
                'tarif'      => $event->tarifs->where('ukuran', $ukuran)->first()?->harga ?? 0,
            ];
        }

        // Rekap per kasir
        $rekapKasir = $transaksis->groupBy('kasir_id')->map(function ($group) {
            return [
                'nama'             => $group->first()->kasir->name,
                'total_transaksi'  => $group->count(),
                'total_pendapatan' => $group->sum(fn($t) => $t->total_harga),
                'total_dititip'    => $group->where('status', 'dititip')->count(),
                'total_terlambat'  => $group->where('status', 'terlambat')->count(),
                'total_diambil'    => $group->where('status', 'sudah_diambil')->count(),
            ];
        })->values();

        // Rekap per kategori
        $rekapKategori = [];
        foreach ($transaksis as $t) {
            foreach ($t->details as $d) {
                $nama = $d->nama_barang_custom ?? $d->kategori->nama_kategori;
                if (!isset($rekapKategori[$nama])) {
                    $rekapKategori[$nama] = ['jumlah' => 0, 'pendapatan' => 0];
                }
                $rekapKategori[$nama]['jumlah']     += $d->jumlah;
                $rekapKategori[$nama]['pendapatan'] += $d->subtotal;
            }
        }
        arsort($rekapKategori);

        return view('admin.events.rekap', compact(
            'event',
            'totalTransaksi',
            'totalDititip',
            'totalTerlambat',
            'totalDiambil',
            'totalPendapatan',
            'totalBarang',
            'rekapUkuran',
            'rekapKasir',
            'rekapKategori'
        ));
    }
}
