<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaksi;
use App\Exports\TransaksiExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::all();
        $transaksis = collect();
        $totalPendapatan = 0;
        $totalDititip = 0;
        $totalDiambil = 0;

        if ($request->filled('event_id') || $request->filled('tanggal')) {
            $query = Transaksi::with(['event', 'kasir', 'details']);

            if ($request->filled('event_id')) {
                $query->where('event_id', $request->event_id);
            }

            if ($request->filled('tanggal')) {
                $query->whereDate('created_at', $request->tanggal);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $transaksis = $query->latest()->get();

            $totalPendapatan = $transaksis->sum(fn($t) => $t->total_harga);
            $totalDititip    = $transaksis->where('status', 'dititip')->count();
            $totalDiambil    = $transaksis->where('status', 'sudah_diambil')->count();
        }

        return view('admin.laporan.index', compact(
            'events',
            'transaksis',
            'totalPendapatan',
            'totalDititip',
            'totalDiambil'
        ));
    }

    public function export(Request $request)
    {
        $namaFile = 'laporan-transaksi-' . now()->format('Ymd-His') . '.xlsx';

        $export = new TransaksiExport(
            $request->event_id,
            $request->tanggal,
            $request->status
        );

        return $export->download($namaFile);
    }
}
