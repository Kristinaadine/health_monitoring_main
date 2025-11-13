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
            $table->string('medical_id')->nullable()->unique()->after('user_id');
            $table->string('photo')->nullable()->after('medical_id');
            $table->date('tanggal_lahir')->nullable()->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stunting_user', function (Blueprint $table) {
            $table->dropColumn(['medical_id', 'photo', 'tanggal_lahir']);
        });
    }
};
