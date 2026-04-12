<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['event', 'kasir', 'details.kategori']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_penitip', 'like', '%' . $request->search . '%')
                    ->orWhere('nomor_transaksi', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $transaksis = $query->latest()->paginate(15)->withQueryString();
        $events = Event::all();

        return view('admin.transaksis.index', compact('transaksis', 'events'));
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['event', 'details.kategori', 'kasir']);
        return view('admin.transaksis.show', compact('transaksi'));
    }
}
