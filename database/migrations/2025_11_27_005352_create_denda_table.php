<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('denda', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('form_id')->nullable(); // untuk denda otomatis absensi

            $table->enum('jenis', ['absensi', 'manual'])->default('manual');
            $table->string('alasan');
            $table->integer('jumlah_denda');
            $table->enum('status', ['belum_bayar', 'lunas'])->default('belum_bayar');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('form_id')->references('id')->on('absensi_forms')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('denda');
    }
};
