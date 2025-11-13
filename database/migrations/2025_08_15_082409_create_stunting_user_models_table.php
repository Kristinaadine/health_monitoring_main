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
        Schema::create('stunting_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Identitas
            $table->string('nama');
            $table->unsignedSmallInteger('usia'); // dalam bulan (0-60)
            $table->enum('jenis_kelamin', ['L','P']);
            $table->float('berat_badan'); // kg
            $table->float('tinggi_badan'); // cm
            $table->float('lingkar_lengan')->nullable(); // MUAC cm (opsional)

            // Data kesehatan
            $table->json('riwayat_penyakit')->nullable(); // array checklist
            $table->boolean('menggunakan_obat')->default(false);
            $table->text('detail_obat')->nullable();
            $table->json('pola_pertumbuhan')->nullable(); // [{bulan:'2025-06', bb: X, tb: Y}, ...]
            $table->unsignedTinyInteger('frekuensi_sakit_6_bulan')->nullable();

            // Kebiasaan nutrisi (1-5)
            $table->tinyInteger('sayur_buah')->nullable();
            $table->tinyInteger('protein')->nullable();
            $table->tinyInteger('karbohidrat')->nullable();
            $table->tinyInteger('gula')->nullable();
            $table->boolean('vegetarian')->default(false);
            $table->tinyInteger('frekuensi_jajan')->nullable(); // 1-5

            // Faktor risiko lingkungan
            $table->json('akses_pangan')->nullable(); // array checklist

            // Target & monitoring
            $table->boolean('target_tinggi')->default(false);
            $table->boolean('target_berat')->default(false);
            $table->boolean('target_gizi')->default(false);
            $table->boolean('izinkan_monitoring')->default(false);
            $table->enum('frekuensi_update', ['mingguan','bulanan'])->nullable();

            // Output AI
            $table->float('haz')->nullable(); // Height-for-Age Z
            $table->float('whz')->nullable(); // Weight-for-Height Z
            $table->string('status_pertumbuhan')->nullable(); // Normal/Stunted/Severely Stunted/Wasting/Severe Wasting
            $table->string('level_risiko')->nullable(); // Rendah/Sedang/Tinggi
            $table->string('faktor_utama')->nullable(); // ringkas
            $table->text('rekomendasi')->nullable(); // detail
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stunting_user');
    }
};
