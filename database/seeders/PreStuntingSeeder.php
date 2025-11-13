<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PreStunting;

class PreStuntingSeeder extends Seeder
{
    public function run(): void
    {
        PreStunting::create([
            'users_id' => 1, // Pastikan user dengan ID 1 ada
            'nama' => 'Ibu A',
            'usia' => 19,
            'tinggi_badan' => 148,
            'berat_badan_pra_hamil' => 42.5,
            'bmi_pra_hamil' => 17.5,
            'kenaikan_bb_trimester' => 1.5,
            'muac' => 22.0,
            'jarak_kelahiran' => 20,
            'anc_visits' => 3,
            'hb' => 10.5,
            'ttd_compliance' => false,
            'has_infection' => true,
            'efw_sga' => false,
            'status_pertumbuhan' => 'SGA',
            'level_risiko' => 'Risiko Tinggi',
        ]);

        PreStunting::create([
            'users_id' => 1,
            'nama' => 'Ibu B',
            'usia' => 28,
            'tinggi_badan' => 160,
            'berat_badan_pra_hamil' => 50,
            'bmi_pra_hamil' => 20.0,
            'kenaikan_bb_trimester' => 3.0,
            'muac' => 25.0,
            'jarak_kelahiran' => 36,
            'anc_visits' => 5,
            'hb' => 12.0,
            'ttd_compliance' => true,
            'has_infection' => false,
            'efw_sga' => false,
            'status_pertumbuhan' => 'Normal',
            'level_risiko' => 'Risiko Rendah',
        ]);
    }
}