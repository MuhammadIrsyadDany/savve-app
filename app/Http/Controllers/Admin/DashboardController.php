<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        // Auto nonaktifkan event expired & menandai transaksi terlambat    
        \App\Models\Event::where('status', 'aktif')
            ->where('tanggal_selesai', '<', today())
            ->each(function ($event) {
                $event->update(['status' => 'nonaktif']);
                \App\Models\Transaksi::where('event_id', $event->id)
                    ->where('status', 'dititip')
                    ->update(['status' => 'terlambat']);
            });

        $totalEventAktif = Event::where('status', 'aktif')->count();
        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();
        $belumDiambil = Transaksi::where('status', 'dititip')->count();
        $sudahDiambil = Transaksi::where('status', 'sudah_diambil')->count();
        $transaksiTerbaru = Transaksi::with('details')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalEventAktif',
            'transaksiHariIni',
            'belumDiambil',
            'sudahDiambil',
            'transaksiTerbaru'
        ));
    }
}
