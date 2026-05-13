<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Transaksi;
use Illuminate\Console\Command;

class UpdateStatusEvent extends Command
{
    protected $signature   = 'event:update-status';
    protected $description = 'Nonaktifkan event expired & tandai transaksi terlambat';

    public function handle()
    {
        // Nonaktifkan event yang sudah melewati tanggal selesai
        $expiredEvents = Event::where('status', 'aktif')
            ->where('tanggal_selesai', '<', today())
            ->get();

        foreach ($expiredEvents as $event) {
            $event->update(['status' => 'nonaktif']);

            // Tandai transaksi yang masih dititip di event ini sebagai terlambat
            Transaksi::where('event_id', $event->id)
                ->where('status', 'dititip')
                ->update(['status' => 'terlambat']);

            $this->info("Event '{$event->nama_event}' dinonaktifkan.");
        }

        // Cek event yang sudah nonaktif, tandai transaksi yang masih dititip
        $nonaktifEvents = Event::where('status', 'nonaktif')->pluck('id');
        $updated = Transaksi::whereIn('event_id', $nonaktifEvents)
            ->where('status', 'dititip')
            ->update(['status' => 'terlambat']);

        $this->info("$updated transaksi ditandai terlambat.");
    }
}
