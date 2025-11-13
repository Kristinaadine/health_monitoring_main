<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeightFieldsToPreStuntingsTable extends Migration
{
    public function up()
    {
        Schema::table('pre_stuntings', function (Blueprint $table) {
            $table->decimal('weight_at_g12', 5, 2)->nullable()->after('bmi_pra_hamil');
            $table->decimal('weight_at_g36', 5, 2)->nullable()->after('weight_at_g12');
            $table->decimal('weight_gain_trimester', 5, 2)->nullable()->after('weight_at_g36');
        });
    }

    public function down()
    {
        Schema::table('pre_stuntings', function (Blueprint $table) {
            $table->dropColumn(['weight_at_g12', 'weight_at_g36', 'weight_gain_trimester']);
        });
    }
}