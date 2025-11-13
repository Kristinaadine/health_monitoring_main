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
        Schema::table('stunting_user', function (Blueprint $table) {
            $table->text('haz_recommendation')->nullable()->after('rekomendasi');
            $table->text('whz_recommendation')->nullable()->after('haz_recommendation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stunting_user', function (Blueprint $table) {
            $table->dropColumn(['haz_recommendation', 'whz_recommendation']);
        });
    }
};
