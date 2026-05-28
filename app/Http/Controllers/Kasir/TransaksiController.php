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
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    // Ukuran maksimum data base64 foto (~5 MB setelah decode)
    private const MAX_FOTO_BASE64_LENGTH = 7_000_000;

    // Tipe MIME yang diizinkan untuk foto
    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

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

        $transaksis = $query->latest()->get();

        return view('kasir.transaksi.index', compact('transaksis'));
    }

    public function create()
    {
        // FIX #10: Hapus logika auto-deaktivasi event dari sini.
        // Logika ini sudah ditangani oleh Artisan Command 'event:update-status'
        // yang dijadwalkan harian. Menjalankannya di setiap page load create()
        // menyebabkan overhead tidak perlu dan duplikasi logika bisnis.

        $events    = Event::where('status', 'aktif')->get();
        $kategoris = KategoriBarang::all();

        return view('kasir.transaksi.create', compact('events', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id'             => 'required|exists:events,id',
            'nama_penitip'         => 'required|string|max:255',
            'no_whatsapp'          => 'required|string|max:20',
            'barang'               => 'required|array|min:1',
            'barang.*.kategori_id' => 'required|exists:kategori_barangs,id',
            'barang.*.nama_custom' => 'nullable|string|max:255',
            'barang.*.ukuran'      => 'required|in:S,M,L,XL',
            'barang.*.jumlah'      => 'required|integer|min:1',
            'foto_penitipan'       => 'nullable|string',
        ]);

        // Cek event aktif
        $event = Event::findOrFail($request->event_id);

        if ($event->status !== 'aktif') {
            return back()->withInput()
                ->with('error', 'Event ini sudah tidak aktif.');
        }

        // FIX #7: Validasi tambahan — event belum boleh menerima penitipan
        // sebelum tanggal mulai event tiba.
        if ($event->tanggal_mulai->isAfter(today())) {
            return back()->withInput()
                ->with('error', "Event '{$event->nama_event}' belum dimulai. Tanggal mulai: " . $event->tanggal_mulai->format('d M Y') . '.');
        }

        $transaksi = DB::transaction(function () use ($request, $event) {
            // FIX #3: NomorTransaksi::generate() dipanggil di dalam DB::transaction()
            // sehingga lockForUpdate() di dalamnya benar-benar menahan lock
            // dan mencegah race condition duplikat nomor transaksi.
            $nomor = NomorTransaksi::generate();

            // Simpan foto penitipan jika ada
            $fotoPath = null;
            if ($request->filled('foto_penitipan')) {
                $fotoPath = $this->simpanFotoBase64(
                    $request->foto_penitipan,
                    'foto-penitipan'
                );

                if ($fotoPath === false) {
                    throw new \RuntimeException('Foto tidak valid atau ukuran terlalu besar (maks 5 MB).');
                }
            }

            $transaksi = Transaksi::create([
                'nomor_transaksi' => $nomor,
                'event_id'        => $request->event_id,
                'kasir_id'        => auth()->id(),
                'nama_penitip'    => $request->nama_penitip,
                'no_whatsapp'     => $request->no_whatsapp,
                'status'          => 'dititip',
                'waktu_penitipan' => now(),
                'foto_penitipan'  => $fotoPath,
            ]);

            // FIX #9 (PERFORMA): Load semua tarif event sebelum loop untuk
            // menghindari N+1 query (sebelumnya 1 query per item barang).
            $tarifs = Tarif::where('event_id', $request->event_id)
                ->get()
                ->keyBy('ukuran');

            foreach ($request->barang as $item) {
                $harga_satuan = $tarifs->get($item['ukuran'])?->harga ?? 0;
                $subtotal     = $harga_satuan * $item['jumlah'];

                DetailTransaksi::create([
                    'transaksi_id'      => $transaksi->id,
                    'kategori_id'       => $item['kategori_id'],
                    'nama_barang_custom' => $item['nama_custom'] ?? null,
                    'ukuran'            => $item['ukuran'],
                    'jumlah'            => $item['jumlah'],
                    'harga_satuan'      => $harga_satuan,
                    'subtotal'          => $subtotal,
                ]);
            }

            return $transaksi;
        });

        return redirect()->route('kasir.transaksi.show', $transaksi->id)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi = Transaksi::with(['event', 'details.kategori'])
            ->where('kasir_id', auth()->id())
            ->findOrFail($transaksi->id);

        return view('kasir.transaksi.show', compact('transaksi'));
    }

    public function tambahBarang(Transaksi $transaksi)
    {
        if ($transaksi->kasir_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if ($transaksi->status !== 'dititip') {
            return redirect()->route('kasir.transaksi.show', $transaksi)
                ->with('error', 'Transaksi ini sudah selesai, tidak bisa ditambah barang.');
        }

        $transaksi->load(['event', 'details.kategori']);
        $kategoris = KategoriBarang::all();

        return view('kasir.transaksi.tambah-barang', compact('transaksi', 'kategoris'));
    }

    public function simpanBarang(Request $request, Transaksi $transaksi)
    {
        if ($transaksi->kasir_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if ($transaksi->status !== 'dititip') {
            return redirect()->route('kasir.transaksi.show', $transaksi)
                ->with('error', 'Transaksi ini sudah selesai, tidak bisa ditambah barang.');
        }

        $request->validate([
            'barang'               => 'required|array|min:1',
            'barang.*.kategori_id' => 'required|exists:kategori_barangs,id',
            'barang.*.nama_custom' => 'nullable|string|max:255',
            'barang.*.ukuran'      => 'required|in:S,M,L,XL',
            'barang.*.jumlah'      => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $transaksi) {
            // FIX #9 (PERFORMA): Load semua tarif sebelum loop
            $tarifs = Tarif::where('event_id', $transaksi->event_id)
                ->get()
                ->keyBy('ukuran');

            foreach ($request->barang as $item) {
                $harga_satuan = $tarifs->get($item['ukuran'])?->harga ?? 0;
                $subtotal     = $harga_satuan * $item['jumlah'];

                DetailTransaksi::create([
                    'transaksi_id'       => $transaksi->id,
                    'kategori_id'        => $item['kategori_id'],
                    'nama_barang_custom' => $item['nama_custom'] ?? null,
                    'ukuran'             => $item['ukuran'],
                    'jumlah'             => $item['jumlah'],
                    'harga_satuan'       => $harga_satuan,
                    'subtotal'           => $subtotal,
                ]);
            }
        });

        return redirect()->route('kasir.transaksi.show', $transaksi)
            ->with('success', 'Barang berhasil ditambahkan ke transaksi.');
    }

    public function countToday()
    {
        // FIX #6: Filter berdasarkan kasir_id yang login.
        // Sebelumnya menghitung semua transaksi hari ini dari semua kasir.
        $count = Transaksi::where('kasir_id', auth()->id())
            ->whereDate('created_at', today())
            ->count();

        return response()->json(['count' => $count]);
    }

    public function nota(Transaksi $transaksi)
    {
        // Pastikan hanya kasir pemilik yang bisa cetak nota
        if ($transaksi->kasir_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $transaksi->load(['event', 'details.kategori', 'kasir']);
        return view('kasir.transaksi.nota', compact('transaksi'));
    }

    /**
     * Decode, validasi, dan simpan foto base64 ke storage.
     *
     * FIX #8: Validasi ukuran dan MIME type sebelum menyimpan.
     *
     * @return string|false  Path relatif jika sukses, false jika gagal validasi.
     */
    private function simpanFotoBase64(string $base64, string $folder): string|false
    {
        if (strlen($base64) > self::MAX_FOTO_BASE64_LENGTH) {
            return false;
        }

        $raw        = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $binaryData = base64_decode($raw, strict: true);

        if ($binaryData === false) {
            return false;
        }

        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($binaryData);

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, strict: true)) {
            return false;
        }

        $ext = match ($mimeType) {
            'image/png'  => 'png',
            'image/webp' => 'webp',
            default      => 'jpg',
        };

        $filename = $folder . '-' . uniqid() . '.' . $ext;
        $path     = 'foto-transaksi/' . $folder . '/' . $filename;

        Storage::disk('public')->put($path, $binaryData);

        return $path;
    }
}
