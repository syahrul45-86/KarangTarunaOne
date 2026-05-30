<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_forms', function (Blueprint $table) {
            $table->unsignedBigInteger('rt_id')->nullable()->after('id');
            $table->foreign('rt_id')->references('id')->on('rts')->onDelete('cascade');
        });

        // Update existing forms: assign rt_id based on the sekretaris/admin who likely created them
        // If there's only one RT, assign all to that RT
        $firstRt = \App\Models\Rt::first();
        if ($firstRt) {
            \App\Models\AbsensiForm::whereNull('rt_id')->update(['rt_id' => $firstRt->id]);
        }
    }

    public function down(): void
    {
        Schema::table('absensi_forms', function (Blueprint $table) {
            $table->dropForeign(['rt_id']);
            $table->dropColumn('rt_id');
        });
    }
};
