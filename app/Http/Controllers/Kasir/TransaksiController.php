<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\KategoriBarang;
use App\Models\Tarif;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Helpers\NomorTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['event', 'details.kategori'])
            ->where('kasir_id', auth()->id());

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_penitip', 'like', '%' . $request->search . '%')
                    ->orWhere('nomor_transaksi', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        $transaksis = $query->latest()->paginate(10)->withQueryString();

        return view('kasir.transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        $events = Event::where('status', 'aktif')->get();
        $kategoris = KategoriBarang::all();
        return view('kasir.transaksi.create', compact('events', 'kategoris'));
    }

    public function store(Request $request)
    {
        $transaksi = DB::transaction(function () use ($request) {
            $nomor = NomorTransaksi::generate();

            $transaksi = Transaksi::create([
                'nomor_transaksi'  => $nomor,
                'event_id'         => $request->event_id,
                'kasir_id'         => auth()->id(),
                'nama_penitip'     => $request->nama_penitip,
                'no_whatsapp'      => $request->no_whatsapp,
                'status'           => 'dititip',
                'waktu_penitipan'  => now(),
            ]);

            $event = Event::findOrFail($request->event_id);
            if ($event->status !== 'aktif') {
                return back()->withInput()->with('error', 'Event ini sudah tidak aktif. Transaksi tidak dapat dilakukan.');
            }

            foreach ($request->barang as $item) {
                $tarif = Tarif::where('event_id', $request->event_id)
                    ->where('ukuran', $item['ukuran'])
                    ->first();

                $harga_satuan = $tarif ? $tarif->harga : 0;

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'kategori_id' => $item['kategori_id'],
                    'nama_barang_custom' => $item['nama_custom'] ?? null,
                    'ukuran' => $item['ukuran'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $harga_satuan,
                    'subtotal' => $harga_satuan * $item['jumlah'],
                ]);
            }

            return $transaksi;
        });

        return redirect()->route('kasir.transaksi.show', $transaksi->id);
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi = Transaksi::with(['event', 'details'])
            ->where('kasir_id', auth()->id())
            ->findOrFail($transaksi->id);

        return view('kasir.transaksi.show', compact('transaksi'));
    }

    public function countToday()
    {
        $count = Transaksi::whereDate('created_at', today())->count();
        return response()->json(['count' => $count]);
    }

    public function nota(Transaksi $transaksi)
    {
        $transaksi->load(['event', 'details.kategori', 'kasir']);
        return view('kasir.transaksi.nota', compact('transaksi'));
    }
}
