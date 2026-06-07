<?php

namespace App\Helpers;

use App\Models\Transaksi;
use App\Models\Event;

class NomorTransaksi
{

    public static function generateNomor(Event $event): string
    {
        $kodeEvent = $event->kode_event ?? 'EVT';

        // Hitung urutan transaksi di event ini
        $count = static::where('event_id', $event->id)->count() + 1;
        $urutan = str_pad($count, 4, '0', STR_PAD_LEFT);

        return "SVV-{$kodeEvent}-{$urutan}";
    }
}
