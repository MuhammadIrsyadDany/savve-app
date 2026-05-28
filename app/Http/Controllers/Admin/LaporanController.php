<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaksi;
use App\Exports\TransaksiExport;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $events          = Event::orderBy('nama_event')->get();
        $transaksis      = collect();
        $totalPendapatan = 0;
        $totalDititip    = 0;
        $totalTerlambat  = 0;
        $totalDiambil    = 0;
        $selectedEvent   = null;

        $hasFilter = $request->filled('event_id')
            || $request->filled('tanggal')
            || $request->filled('status')
            || $request->filled('show');

        if ($hasFilter) {
            // FIX #4: Pisahkan query summary dan query pagination agar tidak
            // ada double-query pada builder yang sama (sebelumnya ->get() lalu
            // ->paginate() pada instance yang sama mengeksekusi 2x ke DB).
            // Gunakan clone() agar kondisi WHERE tidak berulang secara manual.

            $baseQuery = Transaksi::with(['event', 'kasir', 'details.kategori']);

            if ($request->filled('event_id')) {
                $baseQuery->where('event_id', $request->event_id);
                $selectedEvent = Event::find($request->event_id);
            }

            if ($request->filled('tanggal')) {
                $baseQuery->whereDate('created_at', $request->tanggal);
            }

            if ($request->filled('status')) {
                $baseQuery->where('status', $request->status);
            }

            // Query 1: ambil semua untuk menghitung summary cards (tanpa pagination)
            $allTransaksis   = (clone $baseQuery)->get();
            $totalPendapatan = $allTransaksis->sum(fn($t) => $t->total_harga);
            $totalDititip    = $allTransaksis->where('status', 'dititip')->count();
            $totalTerlambat  = $allTransaksis->where('status', 'terlambat')->count();
            $totalDiambil    = $allTransaksis->where('status', 'sudah_diambil')->count();

            // Query 2: instance terpisah untuk pagination tabel
            $transaksis = $query->latest()->get();
        }

        return view('admin.laporan.index', compact(
            'events',
            'transaksis',
            'totalPendapatan',
            'totalDititip',
            'totalTerlambat',
            'totalDiambil',
            'selectedEvent'
        ));
    }

    public function export(Request $request)
    {
        $namaFile = 'laporan-savve-' . now()->format('Ymd-His') . '.xlsx';

        $export = new TransaksiExport(
            $request->event_id,
            $request->tanggal,
            $request->status
        );

        return $export->download($namaFile);
    }
}
