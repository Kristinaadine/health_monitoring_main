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
        Schema::create('calorie_history_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('age');
            $table->enum('sex', ['male', 'female']);
            $table->decimal('height', 5, 2); // cm
            $table->decimal('weight', 5, 2); // kg
            $table->decimal('activity_level', 4, 3);
            $table->integer('gain_loss_amount');
            $table->integer('daily_calories');
            $table->integer('carbs'); // gram
            $table->integer('protein'); // gram
            $table->integer('fat'); // gram
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calorie_history_models');
    }
};
