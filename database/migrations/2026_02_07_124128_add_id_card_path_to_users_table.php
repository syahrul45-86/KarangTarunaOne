<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom id_card_path untuk menyimpan path ID Card yang sudah digenerate
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('id_card_path')->nullable()->after('qr_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id_card_path');
        });
    }
};
