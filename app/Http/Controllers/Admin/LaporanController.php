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

            // Summary cards
            $allTransaksis   = (clone $baseQuery)->get();
            $totalPendapatan = $allTransaksis->sum(fn($t) => $t->total_harga);
            $totalDititip    = $allTransaksis->where('status', 'dititip')->count();
            $totalTerlambat  = $allTransaksis->where('status', 'terlambat')->count();
            $totalDiambil    = $allTransaksis->where('status', 'sudah_diambil')->count();

            // Data tabel
            $transaksis = (clone $baseQuery)->latest()->get();
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
