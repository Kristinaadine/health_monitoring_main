<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StuntingUserModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stunting_user';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $casts = [
        'riwayat_penyakit' => 'array',
        'pola_pertumbuhan' => 'array',
        'akses_pangan' => 'array',
        'menggunakan_obat' => 'boolean',
        'vegetarian' => 'boolean',
        'target_tinggi' => 'boolean',
        'target_berat' => 'boolean',
        'target_gizi' => 'boolean',
        'izinkan_monitoring' => 'boolean',
    ];
}
