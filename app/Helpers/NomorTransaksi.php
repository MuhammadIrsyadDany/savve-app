<?php

namespace App\Helpers;

use App\Models\Transaksi;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class NomorTransaksi
{
    public static function generate(Event $event): string
    {
        // Harus dipanggil di dalam DB::transaction() yang sudah ada di controller
        // FOR UPDATE mengunci baris yang dibaca sehingga transaksi concurrent
        // tidak bisa membaca nilai yang sama secara bersamaan
        $max = DB::table('transaksis')
            ->where('event_id', $event->id)
            ->lockForUpdate()
            ->max(DB::raw("CAST(SUBSTRING_INDEX(nomor_transaksi, '-', -1) AS UNSIGNED)"));

        $urutan    = str_pad(($max ?? 0) + 1, 4, '0', STR_PAD_LEFT);
        $kodeEvent = $event->kode_event ?? 'EVT';

        return "SVV-{$kodeEvent}-{$urutan}";
    }
}
