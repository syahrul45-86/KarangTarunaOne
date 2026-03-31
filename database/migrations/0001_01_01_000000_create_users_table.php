<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();

        // Data dasar
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');

        // Role & RT
        $table->enum('role', [
            'superadmin',
            'admin',
            'bendahara',
            'sekretaris',
            'anggota'
        ])->default('anggota');

        // Setiap user (kecuali superadmin) bisa terkait dengan RT tertentu
        $table->unsignedBigInteger('rt_id')->nullable();
        $table->rememberToken();
        $table->timestamps();

        // foreign key opsional, kalau kamu punya tabel rts
        // $table->foreign('rt_id')->references('id')->on('rts')->onDelete('cascade');
    });
}

};
