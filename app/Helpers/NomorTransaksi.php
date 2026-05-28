<?php

namespace App\Helpers;

use App\Models\Transaksi;

class NomorTransaksi
{
    /**
     * Generate nomor transaksi unik untuk hari ini.
     *
     * PENTING: method ini HARUS dipanggil dari dalam DB::transaction()
     * yang aktif agar lockForUpdate() benar-benar menahan lock dan
     * mencegah race condition duplikat nomor.
     */
    public static function generate(): string
    {
        $prefix  = 'SVV';
        $tanggal = now()->format('Ymd');

        // FIX #3: lockForUpdate() hanya efektif di dalam transaksi DB aktif.
        // Sebelumnya dipanggil di luar transaksi sehingga lock tidak dipegang.
        // Sekarang caller (TransaksiController::store) sudah membungkus dengan
        // DB::transaction(), sehingga lock ini benar-benar bekerja.
        $count = Transaksi::whereDate('created_at', today())
            ->lockForUpdate()
            ->count();

        $urutan = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$tanggal}-{$urutan}";
    }
}
