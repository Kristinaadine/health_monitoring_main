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
        Schema::create('meal_planner_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_planner_id')->constrained('meal_planner')->onDelete('cascade');
            $table->string('name_food', 100);
            $table->string('serving_size', 100);
            $table->integer('calories');
            $table->integer('protein');
            $table->integer('carbs');
            $table->integer('fat');
            $table->integer('fiber');
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
        //
    }
};
