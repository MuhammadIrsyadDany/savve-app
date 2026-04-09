<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $transaksiHariIni = Transaksi::where('kasir_id', auth()->id())
            ->whereDate('created_at', today())
            ->count();

        $belumDiambil = Transaksi::where('kasir_id', auth()->id())
            ->where('status', 'dititip')
            ->count();

        $eventAktif = Event::where('status', 'aktif')->get();

        $transaksiTerbaru = Transaksi::with(['event', 'details'])
            ->where('kasir_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact(
            'transaksiHariIni',
            'belumDiambil',
            'eventAktif',
            'transaksiTerbaru'
        ));
    }
}
