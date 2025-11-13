<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pre_stuntings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->string('nama')->nullable();
            $table->integer('usia')->nullable();
            $table->float('tinggi_badan')->nullable();
            $table->float('berat_badan_pra_hamil')->nullable();
            $table->float('bmi_pra_hamil')->nullable();
            $table->float('kenaikan_bb_trimester')->nullable();
            $table->float('muac')->nullable();
            $table->integer('jarak_kelahiran')->nullable();
            $table->integer('anc_visits')->nullable();
            $table->float('hb')->nullable();
            $table->boolean('ttd_compliance')->default(true);
            $table->boolean('has_infection')->default(false);
            $table->boolean('efw_sga')->default(false);
            $table->string('status_pertumbuhan')->nullable();
            $table->string('level_risiko')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pre_stuntings');
    }
};