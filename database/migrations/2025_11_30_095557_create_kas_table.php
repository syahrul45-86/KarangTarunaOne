<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained('rts')->onDelete('cascade');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->integer('nominal');
            $table->string('keterangan')->nullable();
            $table->date('tanggal');
            $table->integer('saldo_awal')->default(0);
            $table->integer('saldo_akhir')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kas');
    }
};
