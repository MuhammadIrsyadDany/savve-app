<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session('kasir_event_id')) {
            return redirect()->route('kasir.event.select')
                ->with('info', 'Silakan pilih event terlebih dahulu.');
        }

        $eventId = session('kasir_event_id'); // ambil dari session

        $transaksiHariIni = Transaksi::where('kasir_id', auth()->id())
            ->where('event_id', $eventId)
            ->whereDate('created_at', today())
            ->count();

        $belumDiambil = Transaksi::where('kasir_id', auth()->id())
            ->where('event_id', $eventId)
            ->where('status', 'dititip')
            ->count();

        $sudahDiambil = Transaksi::where('kasir_id', auth()->id())
            ->where('event_id', $eventId)
            ->where('status', 'sudah_diambil')
            ->count();

        $eventAktif = collect([Event::find(session('kasir_event_id'))])->filter();

        // Cari
        $transaksiTerbaru = Transaksi::with(['event', 'details'])
            ->where('kasir_id', auth()->id())
            ->where('event_id', $eventId)
            ->latest()
            ->take(5)
            ->get();

        return view('kasir.dashboard', compact(
            'transaksiHariIni',
            'belumDiambil',
            'sudahDiambil',
            'eventAktif',
            'transaksiTerbaru'
        ));

        // Ganti dengan
        $transaksiTerbaru = Transaksi::with(['event', 'details'])
            ->where('kasir_id', auth()->id())
            ->where('event_id', $eventId)
            ->latest()
            ->take(5)
            ->get();

        // Dipindahkan dari view
        $totalTransaksiKasir = Transaksi::where('kasir_id', auth()->id())
            ->where('event_id', $eventId)
            ->count();

        $transaksiKemarinKasir = Transaksi::where('kasir_id', auth()->id())
            ->where('event_id', $eventId)
            ->whereDate('created_at', today()->subDay())
            ->count();

        return view('kasir.dashboard', compact(
            'transaksiHariIni',
            'belumDiambil',
            'sudahDiambil',
            'eventAktif',
            'transaksiTerbaru',
            'totalTransaksiKasir',
            'transaksiKemarinKasir'
        ));
    }
}
