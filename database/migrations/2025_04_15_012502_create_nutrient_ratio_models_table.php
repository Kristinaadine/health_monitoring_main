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
        Schema::create('nutrient_ratio', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('protein')->unsigned();
            $table->integer('carbs')->unsigned();
            $table->integer('fat')->unsigned();
            $table->string('login_created', 100)->nullable();
            $table->string('login_edit', 100)->nullable();
            $table->string('login_deleted', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrient_ratio');
    }
};
