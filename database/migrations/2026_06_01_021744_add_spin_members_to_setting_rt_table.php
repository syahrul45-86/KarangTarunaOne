<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_rt', function (Blueprint $table) {
            $table->text('spin_members')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('setting_rt', function (Blueprint $table) {
            $table->dropColumn('spin_members');
        });
    }
};
