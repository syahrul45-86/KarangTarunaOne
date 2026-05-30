<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('izin_absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('form_id')->constrained('absensi_forms')->onDelete('cascade');
            $table->string('foto_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('alasan')->nullable();
            $table->timestamps();

            // Satu user hanya bisa mengajukan izin sekali per form
            $table->unique(['user_id', 'form_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('izin_absensi');
    }
};
