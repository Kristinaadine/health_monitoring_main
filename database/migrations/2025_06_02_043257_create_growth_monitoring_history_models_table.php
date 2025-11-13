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
        Schema::create('growth_monitoring_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_growth')->constrained('growth_monitoring')->onDelete('cascade');
            $table->enum('type', ['LH', 'W']);
            $table->double('zscore', 15, 2)->nullable()->default(0.00);
            $table->string('hasil_diagnosa', 100);
            $table->text('deskripsi_diagnosa');
            $table->text('penanganan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('growth_monitoring_history');
    }
};
