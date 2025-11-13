<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nutrition_target', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('child_id');
            $table->float('kalori');
            $table->float('karbo');
            $table->float('protein');
            $table->float('lemak');
            $table->json('vitamin')->nullable();
            $table->enum('periode', ['mingguan', 'bulanan'])->default('mingguan');
            $table->timestamps();

            $table->foreign('child_id')->references('id')->on('children')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrition_target');
    }
};
