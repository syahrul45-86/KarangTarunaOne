<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bendaharas', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel RT (setiap RT punya data keuangan sendiri)
            $table->foreignId('rt_id')->constrained('rts')->onDelete('cascade');

            // Kolom data transaksi
            $table->date('tanggal')->nullable();
            $table->string('keterangan')->nullable();

            // Kolom saldo dan keuangan
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->decimal('pemasukan', 15, 2)->default(0);
            $table->decimal('pengeluaran', 15, 2)->default(0);
            $table->decimal('saldo_akhir', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bendaharas');
    }
};
