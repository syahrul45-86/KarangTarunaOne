<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
    {
        Schema::create('absensi_admin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rt_id')->constrained('rts')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->timestamps();

            // Index untuk performa
            $table->index(['user_id', 'tanggal']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi_admin');
    }
};
