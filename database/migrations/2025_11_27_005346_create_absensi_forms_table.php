<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('absensi_forms', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            // Token unik untuk QR Code
            $table->string('qr_token')->unique();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi_forms');
    }
};
