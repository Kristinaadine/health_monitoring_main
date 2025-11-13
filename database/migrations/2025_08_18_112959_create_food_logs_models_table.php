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
        Schema::create('food_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('child_id');
            $table->date('tanggal');
            $table->string('nama_makanan');
            $table->string('porsi')->nullable();
            $table->string('foto')->nullable();
            $table->float('kalori')->default(0);
            $table->float('karbo')->default(0);
            $table->float('protein')->default(0);
            $table->float('lemak')->default(0);
            $table->json('vitamin')->nullable();
            $table->timestamps();

            $table->foreign('child_id')->references('id')->on('children')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_logs');
    }
};
