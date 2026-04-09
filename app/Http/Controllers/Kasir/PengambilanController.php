<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PengambilanController extends Controller
{
    public function index()
    {
        return view('kasir.pengambilan.index');
    }

    public function cari(Request $request)
    {
        $request->validate([
            'nomor_transaksi' => 'required|string',
        ]);

        $transaksi = Transaksi::with(['event', 'details.kategori', 'kasir'])
            ->where('nomor_transaksi', $request->nomor_transaksi)
            ->first();

        if (!$transaksi) {
            return back()->with('error', 'Nomor transaksi tidak ditemukan.');
        }

        if ($transaksi->status === 'sudah_diambil') {
            return back()->with('error', 'Barang ini sudah diambil sebelumnya.');
        }

        return view('kasir.pengambilan.index', compact('transaksi'));
    }

    public function konfirmasi(Request $request, Transaksi $transaksi)
    {
        if ($transaksi->status === 'sudah_diambil') {
            return redirect()->route('kasir.pengambilan.index')
                ->with('error', 'Barang ini sudah diambil sebelumnya.');
        }

        $transaksi->update([
            'status'            => 'sudah_diambil',
            'waktu_pengambilan' => now(),
        ]);

        return redirect()->route('kasir.pengambilan.index')
            ->with('success', "Barang atas nama {$transaksi->nama_penitip} berhasil diambil.");
    }
}
