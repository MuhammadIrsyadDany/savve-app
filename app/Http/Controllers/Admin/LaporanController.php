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
        $events          = Event::all();
        $transaksis      = collect();
        $totalPendapatan = 0;
        $totalDititip    = 0;
        $totalDiambil    = 0;
        $selectedEvent   = null;
        $hasFilter       = $request->filled('event_id')
            || $request->filled('tanggal')
            || $request->filled('status')
            || $request->filled('show');

        if ($hasFilter) {
            $query = Transaksi::with(['event', 'kasir', 'details.kategori']);

            if ($request->filled('event_id')) {
                $query->where('event_id', $request->event_id);
                $selectedEvent = Event::find($request->event_id);
            }

            if ($request->filled('tanggal')) {
                $query->whereDate('created_at', $request->tanggal);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Untuk summary cards — ambil semua dulu tanpa pagination
            $allTransaksis   = $query->get();
            $totalPendapatan = $allTransaksis->sum(fn($t) => $t->total_harga);
            $totalDititip    = $allTransaksis->where('status', 'dititip')->count();
            $totalDiambil    = $allTransaksis->where('status', 'sudah_diambil')->count();

            // Untuk tabel — pakai pagination
            $transaksis = $query->latest()->paginate(15)->withQueryString();
        }

        return view('admin.laporan.index', compact(
            'events',
            'transaksis',
            'totalPendapatan',
            'totalDititip',
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
