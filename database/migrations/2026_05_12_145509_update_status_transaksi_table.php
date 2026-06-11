<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Superseded by recreate_transaksis_table migration which drops
    // and recreates the table with the correct schema including 'terlambat'.
    // Kept as a no-op to preserve migration history.

    public function up(): void {}

    public function down(): void {}
};
