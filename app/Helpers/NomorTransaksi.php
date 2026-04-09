<?php

namespace App\Helpers;

use App\Models\Transaksi;

class NomorTransaksi
{
    public static function generate(): string
    {
        $prefix = 'SVV';
        $tanggal = now()->format('Ymd');
        $latest = Transaksi::whereDate('created_at', today())
            ->lockForUpdate()
            ->count();

        $urutan = str_pad($latest + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$tanggal}-{$urutan}";
    }
}
