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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->string('name_food', 100);
            $table->foreignId('id_categories');
            $table->integer('calories');
            $table->integer('protein');
            $table->integer('carbs');
            $table->integer('fiber');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('image_path')->nullable();
            $table->string('login_created', 100)->nullable();
            $table->string('login_edit', 100)->nullable();
            $table->string('login_deleted', 100)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
