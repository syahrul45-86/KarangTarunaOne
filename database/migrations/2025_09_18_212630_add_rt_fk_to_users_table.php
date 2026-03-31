<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // pastikan kolom rt_id ada; kalau belum, tambahkan
            if (!Schema::hasColumn('users', 'rt_id')) {
                $table->unsignedBigInteger('rt_id')->nullable()->after('role');
            }
            // tambahkan foreign key
            $table->foreign('rt_id')->references('id')->on('rts')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users','rt_id')) {
                $table->dropForeign(['rt_id']);
                // jika mau, hapus kolom rt_id: $table->dropColumn('rt_id');
            }
        });
    }
};
