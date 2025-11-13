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
        Schema::create('zscore', function (Blueprint $table) {
            $table->id();
            $table->enum('gender', ['L', 'P']);
            $table->enum('type', ['LH', 'W'])->comment('LH = Length/Height, W = Weight');
            $table->integer('month');
            $table->double('L', 15, 6)->default(0.00);
            $table->double('M', 15, 6)->default(0.00);
            $table->double('S', 15, 6)->default(0.00);
            $table->double('SD', 15, 6)->nullable()->default(0.00);
            $table->double('SD3neg', 15, 6)->default(0.00);
            $table->double('SD2neg', 15, 6)->default(0.00);
            $table->double('SD1neg', 15, 6)->default(0.00);
            $table->double('SD0', 15, 6)->default(0.00);
            $table->double('SD1', 15, 6)->default(0.00);
            $table->double('SD2', 15, 6)->default(0.00);
            $table->double('SD3', 15, 6)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zscore ');
    }
};
