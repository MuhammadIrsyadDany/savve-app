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
        $eventId    = session('kasir_event_id');
        $events     = Event::orderBy('nama_event')->get();
        $eventAktif = Event::find($eventId);

        $query = Transaksi::with(['event', 'details'])
            ->where('kasir_id', auth()->id())
            ->where('event_id', $eventId);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_penitip', 'like', "%{$search}%")
                    ->orWhere('nomor_transaksi', 'like', "%{$search}%")
                    ->orWhere('no_whatsapp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('waktu_penitipan', [
                $request->tanggal_mulai . ' 00:00:00',
                $request->tanggal_selesai . ' 23:59:59',
            ]);
        } elseif ($request->filled('tanggal_mulai')) {
            $query->whereDate('waktu_penitipan', '>=', $request->tanggal_mulai);
        } elseif ($request->filled('tanggal_selesai')) {
            $query->whereDate('waktu_penitipan', '<=', $request->tanggal_selesai);
        }

        $transaksis = $query->latest('waktu_penitipan')->get();

        return view('kasir.transaksi.index', compact(
            'transaksis',
            'events',
            'eventAktif'
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
            $nomor = \App\Helpers\NomorTransaksi::generate($event);

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
        if ($transaksi->kasir_id !== auth()->id()) {
            abort(403);
        }

        $transaksi->load(['event', 'kasir', 'details']);
        return view('kasir.transaksi.show', compact('transaksi'));
    }

    public function nota(Transaksi $transaksi)
    {
        if ($transaksi->kasir_id !== auth()->id()) {
            abort(403);
        }

        $transaksi->load(['event', 'kasir', 'details']);
        return view('kasir.transaksi.nota', compact('transaksi'));
    }

    public function tambahBarang(Transaksi $transaksi)
    {
        // Hanya kasir pemilik transaksi yang boleh akses
        if ($transaksi->kasir_id !== auth()->id()) {
            abort(403);
        }

        // Hanya transaksi yang masih aktif (bukan sudah_diambil)
        if ($transaksi->status === 'sudah_diambil') {
            return redirect()->route('kasir.transaksi.show', $transaksi)
                ->with('error', 'Tidak bisa menambah barang ke transaksi yang sudah diambil.');
        }

        $transaksi->load(['event', 'details']);

        $kategoris = \App\Models\KategoriBarang::orderBy('nama_kategori')->get();

        $tarifs = Tarif::where('event_id', $transaksi->event_id)
            ->get()
            ->keyBy('ukuran');

        return view('kasir.transaksi.tambah-barang', compact('transaksi', 'kategoris', 'tarifs'));
    }

    public function simpanBarang(Request $request, Transaksi $transaksi)
    {
        // Hanya kasir pemilik transaksi yang boleh akses
        if ($transaksi->kasir_id !== auth()->id()) {
            abort(403);
        }

        if ($transaksi->status === 'sudah_diambil') {
            return redirect()->route('kasir.transaksi.show', $transaksi)
                ->with('error', 'Tidak bisa menambah barang ke transaksi yang sudah diambil.');
        }

        $request->validate([
            'items'                  => 'required|array|min:1',
            'items.*.ukuran'         => 'required|in:S,M,L,XL',
            'items.*.jenis_barang_id' => 'required|exists:kategori_barangs,id',
            'items.*.nama_custom'    => 'nullable|string|max:100',
        ]);

        $tarifs = Tarif::where('event_id', $transaksi->event_id)
            ->get()
            ->keyBy('ukuran');

        DB::transaction(function () use ($request, $transaksi, $tarifs) {
            foreach ($request->items as $item) {
                $ukuran  = $item['ukuran'];
                $tarif   = $tarifs[$ukuran] ?? null;
                $harga   = $tarif ? $tarif->harga : 0;

                // Resolusi nama barang: pakai nama_custom jika kategori is_custom, else nama_kategori
                $kategori    = \App\Models\KategoriBarang::find($item['jenis_barang_id']);
                $namaBarang  = ($kategori->is_custom && !empty($item['nama_custom']))
                    ? $item['nama_custom']
                    : $kategori->nama_kategori;

                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'ukuran'       => $ukuran,
                    'jenis_barang' => [$namaBarang],
                    'harga_satuan' => $harga,
                    'subtotal'     => $harga,
                ]);
            }
        });

        return redirect()->route('kasir.transaksi.show', $transaksi)
            ->with('success', 'Barang berhasil ditambahkan ke transaksi.');
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
