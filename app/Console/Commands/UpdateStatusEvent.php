<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class UpdateStatusEvent extends Command
{
    protected $signature   = 'event:update-status';
    protected $description = 'Nonaktifkan event yang sudah melewati tanggal selesai';

    public function handle()
    {
        $updated = Event::where('status', 'aktif')
            ->where('tanggal_selesai', '<', today())
            ->update(['status' => 'nonaktif']);

        $this->info("$updated event dinonaktifkan.");
    }
}
