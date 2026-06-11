<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE transaksis MODIFY status ENUM('dititip', 'terlambat', 'sudah_diambil') NOT NULL DEFAULT 'dititip'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transaksis MODIFY status ENUM('dititip', 'sudah_diambil') NOT NULL DEFAULT 'dititip'");
    }
};
