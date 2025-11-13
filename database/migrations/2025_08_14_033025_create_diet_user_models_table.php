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
        Schema::create('diet_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->integer('usia');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->float('berat_badan');
            $table->float('tinggi_badan');
            $table->tinyInteger('frekuensi_sayur')->unsigned();
            $table->tinyInteger('konsumsi_protein')->unsigned();
            $table->tinyInteger('konsumsi_karbo')->unsigned();
            $table->tinyInteger('konsumsi_gula')->unsigned();
            $table->boolean('vegetarian')->default(false);
            $table->integer('frekuensi_jajan')->default(0);
            $table->string('target')->nullable();
            $table->float('bmi')->nullable();
            $table->string('status_gizi')->nullable();
            $table->string('rekomendasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diet_users');
    }
};
