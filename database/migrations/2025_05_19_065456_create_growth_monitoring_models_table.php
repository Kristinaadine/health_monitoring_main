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
        Schema::create('growth_monitoring', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->integer('age');
            $table->enum('gender', ['L', 'P']);
            $table->double('height');
            $table->double('weight');
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
        Schema::dropIfExists('growth_monitoring');
    }
};
