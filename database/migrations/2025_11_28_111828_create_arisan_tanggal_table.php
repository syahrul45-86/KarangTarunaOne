<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('arisan_tanggal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arisan_tahun_id')->constrained('arisan_tahun')->onDelete('cascade');
            $table->string('tanggal'); // contoh: Januari, Februari
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('arisan_tanggal');
    }
};
