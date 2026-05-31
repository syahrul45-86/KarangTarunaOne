<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE denda MODIFY jenis VARCHAR(255) DEFAULT 'manual'");
        DB::statement("ALTER TABLE denda MODIFY status VARCHAR(255) DEFAULT 'belum_bayar'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE denda MODIFY jenis ENUM('absensi', 'manual') DEFAULT 'manual'");
        DB::statement("ALTER TABLE denda MODIFY status ENUM('belum_bayar', 'lunas') DEFAULT 'belum_bayar'");
    }
};
