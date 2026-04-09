<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEventAktif = Event::where('status', 'aktif')->count();
        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();
        $belumDiambil = Transaksi::where('status', 'dititip')->count();
        $sudahDiambil = Transaksi::where('status', 'sudah_diambil')->count();

        return view('admin.dashboard', compact(
            'totalEventAktif',
            'transaksiHariIni',
            'belumDiambil',
            'sudahDiambil'
        ));
    }
}
