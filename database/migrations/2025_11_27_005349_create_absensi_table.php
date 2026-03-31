<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('user_id');

            $table->dateTime('waktu_absen')->nullable();
            $table->enum('status', ['hadir']);

            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('absensi_forms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('absensi');
    }
};
