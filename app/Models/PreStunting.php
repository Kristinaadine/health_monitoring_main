<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreStunting extends Model
{
    use HasFactory;

    protected $table = 'pre_stuntings'; // sesuaikan nama tabelmu
    protected $fillable = [
        'users_id',
        'nama',
        'usia',
        'tinggi_badan',
        'berat_badan_pra_hamil',
        'bmi_pra_hamil',
        'kenaikan_bb_trimester',
        'muac',
        'jarak_kelahiran',
        'anc_visits',
        'hb',
        'ttd_compliance',
        'has_infection',
        'efw_sga',
        'status_pertumbuhan',
        'level_risiko',
        'weight_at_g12',
        'weight_at_g36',
        'weight_gain_trimester',
    ];

    public $timestamps = true;
}