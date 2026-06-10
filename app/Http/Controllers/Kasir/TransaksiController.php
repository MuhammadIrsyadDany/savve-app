<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\JenisBarang;
use App\Models\Tarif;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    private const MAX_FOTO_BASE64_LENGTH = 7_000_000;
    private const ALLOWED_MIME_TYPES     = ['image/jpeg', 'image/png', 'image/webp'];

    public function index(Request $request)
    {
        $eventId = session('kasir_event_id');
        $events  = Event::orderBy('nama_event')->get();

        // ← tambah ini
        $eventAktif = Event::find($eventId);

        $query = Transaksi::with(['event', 'details'])
            ->where('kasir_id', auth()->id())
            ->where('event_id', $eventId);

        // ... sisa filter yang sudah ada ...

        $transaksis = $query->latest('waktu_penitipan')->get();

        return view('kasir.transaksi.index', compact(
            'transaksis',
            'events',
            'eventAktif'  // ← tambah ini
        ));
    }

    public function create()
    {
        $event = Event::find(session('kasir_event_id'));

        if (!$event || $event->status !== 'aktif') {
            return redirect()->route('kasir.event.select')
                ->with('warning', 'Event tidak aktif. Pilih event terlebih dahulu.');
        }

        // Jenis barang dikelompokkan per ukuran
        $jenisBarangs = JenisBarang::where('is_active', true)
            ->orderBy('ukuran')
            ->orderBy('urutan')
            ->get()
            ->groupBy('ukuran');

        // Tarif per ukuran untuk event ini
        $tarifs = Tarif::where('event_id', $event->id)
            ->get()
            ->keyBy('ukuran');

        return view('kasir.transaksi.create', compact('event', 'jenisBarangs', 'tarifs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penitip'            => 'required|string|max:255',
            'no_whatsapp'             => 'required|string|max:20',
            'metode_bayar'            => 'required|in:QRIS,Cash,Web',
            'items'                   => 'required|array|min:1',
            'items.*.ukuran'          => 'required|in:S,M,L,XL',
            'items.*.jenis_barang'    => 'required|array|min:1',
            'items.*.jenis_barang.*'  => 'required|string|max:100',
        ]);

        $event = Event::find(session('kasir_event_id'));

        if (!$event || $event->status !== 'aktif') {
            return back()->withInput()
                ->with('error', 'Event tidak aktif.');
        }

        $tarifs = Tarif::where('event_id', $event->id)->get()->keyBy('ukuran');

        $transaksi = null;

        DB::transaction(function () use ($request, $event, $tarifs, &$transaksi) {
            // Generate nomor transaksi
            $nomor = Transaksi::generateNomor($event);

            // Simpan foto penitipan jika ada
            $fotoPath = null;
            if ($request->filled('foto_penitipan')) {
                $fotoPath = $this->simpanFotoBase64(
                    $request->foto_penitipan,
                    'foto-penitipan'
                );
            }

            $transaksi = Transaksi::create([
                'nomor_transaksi'  => $nomor,
                'event_id'         => $event->id,
                'kasir_id'         => auth()->id(),
                'nama_penitip'     => $request->nama_penitip,
                'no_whatsapp'      => $request->no_whatsapp,
                'metode_bayar'     => $request->metode_bayar,
                'status'           => 'dititip',
                'waktu_penitipan'  => now(),
                'foto_penitipan'   => $fotoPath,
            ]);

            // Simpan setiap item (per kategori/ukuran)
            foreach ($request->items as $item) {
                $ukuran      = $item['ukuran'];
                $jenisBarang = $item['jenis_barang'];
                $tarif       = $tarifs[$ukuran] ?? null;
                $harga       = $tarif ? $tarif->harga : 0;

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'ukuran'       => $ukuran,
                    'jenis_barang' => $jenisBarang,
                    'harga_satuan' => $harga,
                    'subtotal'     => $harga, // 1 item = 1 tarif
                ]);
            }
        });

        return redirect()->route('kasir.transaksi.show', $transaksi)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['event', 'kasir', 'details']);
        return view('kasir.transaksi.show', compact('transaksi'));
    }

    public function nota(Transaksi $transaksi)
    {
        $transaksi->load(['event', 'kasir', 'details']);
        return view('kasir.transaksi.nota', compact('transaksi'));
    }

    public function countToday()
    {
        $eventId = session('kasir_event_id');
        $count   = Transaksi::where('event_id', $eventId)->count();
        return response()->json(['count' => $count]);
    }

    private function simpanFotoBase64(string $base64, string $folder): ?string
    {
        if (strlen($base64) > self::MAX_FOTO_BASE64_LENGTH) return null;

        $raw        = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $binaryData = base64_decode($raw, strict: true);
        if (!$binaryData) return null;

        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($binaryData);
        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) return null;

        $ext      = match ($mimeType) {
            'image/png'  => 'png',
            'image/webp' => 'webp',
            default      => 'jpg',
        };

        $path = 'foto-transaksi/' . $folder . '/' . $folder . '-' . uniqid() . '.' . $ext;
        Storage::disk('public')->put($path, $binaryData);

        return $path;
    }
}
