<?php

namespace App\Helpers;

use App\Models\Transaksi;
use App\Models\Event;

class NomorTransaksi
{

    public static function generateNomor(Event $event): string
    {
        $kodeEvent = $event->kode_event ?? 'EVT';

        do {
            $count  = Transaksi::where('event_id', $event->id)->count() + 1;
            $urutan = str_pad($count, 4, '0', STR_PAD_LEFT);
            $nomor  = "SVV-{$kodeEvent}-{$urutan}";
        } while (Transaksi::where('nomor_transaksi', $nomor)->exists());

        return $nomor;
    }
}
