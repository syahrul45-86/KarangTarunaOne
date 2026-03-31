<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('catatan_arisan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tanggal_id')->constrained('arisan_tanggal')->onDelete('cascade');
            $table->boolean('sudah_bayar')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('catatan_arisan');
    }
};
