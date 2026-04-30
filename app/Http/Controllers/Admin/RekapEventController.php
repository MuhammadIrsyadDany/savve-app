<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;

class RekapEventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('transaksis')
            ->with(['transaksis.details'])
            ->latest()
            ->get()
            ->map(function ($event) {
                $transaksis = $event->transaksis;

                $event->total_transaksi   = $transaksis->count();
                $event->total_dititip     = $transaksis->where('status', 'dititip')->count();
                $event->total_diambil     = $transaksis->where('status', 'sudah_diambil')->count();
                $event->total_pendapatan  = $transaksis->sum(fn($t) => $t->total_harga);
                $event->total_barang      = $transaksis->sum(fn($t) => $t->details->sum('jumlah'));

                return $event;
            });

        $totalSemuaEvent     = $events->sum('total_transaksi');
        $totalSemuaPendapatan = $events->sum('total_pendapatan');
        $totalSemuaBarang    = $events->sum('total_barang');

        return view('admin.rekap.index', compact(
            'events',
            'totalSemuaEvent',
            'totalSemuaPendapatan',
            'totalSemuaBarang'
        ));
    }

    public function show(Event $event)
    {
        $event->load(['transaksis.details.kategori', 'transaksis.kasir', 'tarifs']);

        $transaksis      = $event->transaksis;
        $totalTransaksi  = $transaksis->count();
        $totalDititip    = $transaksis->where('status', 'dititip')->count();
        $totalDiambil    = $transaksis->where('status', 'sudah_diambil')->count();
        $totalPendapatan = $transaksis->sum(fn($t) => $t->total_harga);
        $totalBarang     = $transaksis->sum(fn($t) => $t->details->sum('jumlah'));

        // Rekap per ukuran
        $rekapUkuran = [];
        foreach (['S', 'M', 'L', 'XL'] as $ukuran) {
            $jumlah = $transaksis->sum(function ($t) use ($ukuran) {
                return $t->details->where('ukuran', $ukuran)->sum('jumlah');
            });
            $pendapatan = $transaksis->sum(function ($t) use ($ukuran) {
                return $t->details->where('ukuran', $ukuran)->sum('subtotal');
            });
            $rekapUkuran[$ukuran] = [
                'jumlah'     => $jumlah,
                'pendapatan' => $pendapatan,
                'tarif'      => $event->tarifs->where('ukuran', $ukuran)->first()?->harga ?? 0,
            ];
        }

        // Rekap per kasir
        $rekapKasir = $transaksis->groupBy('kasir_id')->map(function ($group) {
            return [
                'nama'            => $group->first()->kasir->name,
                'total_transaksi' => $group->count(),
                'total_pendapatan' => $group->sum(fn($t) => $t->total_harga),
                'total_dititip'   => $group->where('status', 'dititip')->count(),
                'total_diambil'   => $group->where('status', 'sudah_diambil')->count(),
            ];
        })->values();

        // Rekap per kategori barang
        $rekapKategori = [];
        foreach ($transaksis as $t) {
            foreach ($t->details as $d) {
                $nama = $d->nama_barang_custom ?? $d->kategori->nama_kategori;
                if (!isset($rekapKategori[$nama])) {
                    $rekapKategori[$nama] = ['jumlah' => 0, 'pendapatan' => 0];
                }
                $rekapKategori[$nama]['jumlah']     += $d->jumlah;
                $rekapKategori[$nama]['pendapatan'] += $d->subtotal;
            }
        }
        arsort($rekapKategori);

        return view('admin.rekap.show', compact(
            'event',
            'totalTransaksi',
            'totalDititip',
            'totalDiambil',
            'totalPendapatan',
            'totalBarang',
            'rekapUkuran',
            'rekapKasir',
            'rekapKategori'
        ));
    }
}
