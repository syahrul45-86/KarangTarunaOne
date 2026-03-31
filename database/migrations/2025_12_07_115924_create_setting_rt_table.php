<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('setting_rt', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel rts
            $table->foreignId('rt_id')
                ->constrained('rts')
                ->onDelete('cascade');
            // Pengaturan nominal
            $table->integer('iuran_arisan')->default(0);
            $table->integer('denda_absensi')->default(0);
            $table->integer('denda_arisan')->default(0);



            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('setting_rt');
    }
};
