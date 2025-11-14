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
        Schema::table('growth_monitoring', function (Blueprint $table) {
            $table->string('child_id')->nullable()->after('id');
            $table->string('photo')->nullable()->after('child_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('growth_monitoring', function (Blueprint $table) {
            $table->dropColumn(['child_id', 'photo']);
        });
    }
};
