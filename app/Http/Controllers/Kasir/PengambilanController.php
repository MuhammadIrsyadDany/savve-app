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
            'nama_penitip' => 'required|string',
        ]);

        $transaksis = Transaksi::with(['event', 'details.kategori', 'kasir'])
            ->where('nama_penitip', 'like', '%' . $request->nama_penitip . '%')
            ->where('status', 'dititip')
            ->get();

        if ($transaksis->isEmpty()) {
            return back()->with('error', 'Data penitip tidak ditemukan atau barang sudah diambil.');
        }

        return view('kasir.pengambilan.index', compact('transaksis'));
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
