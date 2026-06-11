<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Transaksi;
use Illuminate\Console\Command;

class NonaktifkanEventExpired extends Command
{
    protected $signature   = 'savve:nonaktifkan-event-expired';
    protected $description = 'Nonaktifkan event yang sudah melewati tanggal selesai dan tandai transaksinya sebagai terlambat';

    public function handle(): void
    {
        $events = Event::where('status', 'aktif')
            ->where('tanggal_selesai', '<', today())
            ->get();

        if ($events->isEmpty()) {
            $this->info('Tidak ada event expired.');
            return;
        }

        foreach ($events as $event) {
            $event->update(['status' => 'nonaktif']);

            $jumlah = Transaksi::where('event_id', $event->id)
                ->where('status', 'dititip')
                ->update(['status' => 'terlambat']);

            $this->info("Event [{$event->nama_event}] dinonaktifkan. {$jumlah} transaksi ditandai terlambat.");
        }
    }
}
