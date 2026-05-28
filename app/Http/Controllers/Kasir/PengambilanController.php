<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengambilanController extends Controller
{
    // Ukuran maksimum data base64 foto yang diizinkan (~5 MB setelah decode)
    private const MAX_FOTO_BASE64_LENGTH = 7_000_000;

    // Tipe MIME gambar yang diizinkan
    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

    public function index()
    {
        return view('kasir.pengambilan.index');
    }

    public function cari(Request $request)
    {
        $request->validate([
            'nama_penitip' => 'required|string|max:100',
        ]);

        $transaksis = Transaksi::with(['event', 'details.kategori', 'kasir'])
            ->where('nama_penitip', 'like', '%' . $request->nama_penitip . '%')
            ->whereIn('status', ['dititip', 'terlambat'])
            ->get();

        if ($transaksis->isEmpty()) {
            return back()->with('error', 'Data penitip tidak ditemukan atau barang sudah diambil.');
        }

        return view('kasir.pengambilan.index', compact('transaksis'));
    }

    public function konfirmasi(Request $request, Transaksi $transaksi)
    {
        // FIX #2 (KEAMANAN - IDOR): Verifikasi kepemilikan transaksi.
        // Sebelumnya tidak ada pengecekan kasir_id sehingga kasir manapun
        // bisa mengonfirmasi pengambilan transaksi milik kasir lain.
        if ($transaksi->kasir_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if ($transaksi->status === 'sudah_diambil') {
            return redirect()->route('kasir.pengambilan.index')
                ->with('error', 'Barang ini sudah diambil sebelumnya.');
        }

        $isTerlambat = $transaksi->status === 'terlambat';

        // Simpan foto pengambilan jika ada
        $fotoPath = null;
        if ($request->filled('foto_pengambilan')) {
            // FIX #8 (KEAMANAN): Validasi ukuran & tipe MIME sebelum menyimpan
            $fotoPath = $this->simpanFotoBase64(
                $request->foto_pengambilan,
                'foto-pengambilan'
            );

            if ($fotoPath === false) {
                return back()->with('error', 'Foto tidak valid atau ukuran terlalu besar (maks 5 MB). Format yang diizinkan: JPG, PNG, WebP.');
            }
        }

        $transaksi->update([
            'status'            => 'sudah_diambil',
            'waktu_pengambilan' => now(),
            'foto_pengambilan'  => $fotoPath,
        ]);

        $pesan = $isTerlambat
            ? "Barang atas nama {$transaksi->nama_penitip} berhasil diambil. ⚠️ Catatan: Pengambilan melebihi batas waktu event."
            : "Barang atas nama {$transaksi->nama_penitip} berhasil diambil.";

        return redirect()->route('kasir.pengambilan.index')
            ->with('success', $pesan);
    }

    public function scanQr(Request $request)
    {
        $request->validate(['nomor_transaksi' => 'required|string|max:30']);

        $transaksi = Transaksi::with(['event', 'details.kategori', 'kasir'])
            ->where('nomor_transaksi', $request->nomor_transaksi)
            ->whereIn('status', ['dititip', 'terlambat'])
            ->first();

        if (!$transaksi) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found'     => true,
            'transaksi' => [
                'id'              => $transaksi->id,
                'nomor'           => $transaksi->nomor_transaksi,
                'nama_penitip'    => $transaksi->nama_penitip,
                'no_whatsapp'     => $transaksi->no_whatsapp,
                'event'           => $transaksi->event->nama_event,
                'status'          => $transaksi->status,
                'waktu_penitipan' => $transaksi->waktu_penitipan->format('d M Y H:i'),
                'total_barang'    => $transaksi->details->sum('jumlah'),
                'foto_penitipan'  => $transaksi->foto_penitipan
                    ? asset('storage/' . $transaksi->foto_penitipan)
                    : null,
                'details' => $transaksi->details->map(fn($d) => [
                    'nama'    => $d->nama_barang_custom ?? $d->kategori->nama_kategori,
                    'ukuran'  => $d->ukuran,
                    'jumlah'  => $d->jumlah,
                    'subtotal' => 'Rp ' . number_format($d->subtotal, 0, ',', '.'),
                ]),
            ],
        ]);
    }

    /**
     * Decode, validasi, dan simpan foto base64 ke storage.
     *
     * FIX #8: Tambahkan validasi ukuran string base64 dan verifikasi
     * MIME type melalui finfo setelah decode untuk mencegah upload file
     * yang tidak valid atau terlalu besar (memory exhaustion).
     *
     * @return string|false  Path relatif jika sukses, false jika gagal validasi.
     */
    private function simpanFotoBase64(string $base64, string $folder): string|false
    {
        // Validasi panjang string base64 sebelum decode
        if (strlen($base64) > self::MAX_FOTO_BASE64_LENGTH) {
            return false;
        }

        // Hapus header data URI (misal: "data:image/jpeg;base64,")
        $raw = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $binaryData = base64_decode($raw, strict: true);

        if ($binaryData === false) {
            return false;
        }

        // Verifikasi MIME type dari konten biner (bukan dari header yang bisa dipalsukan)
        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($binaryData);

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, strict: true)) {
            return false;
        }

        $ext      = match ($mimeType) {
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
