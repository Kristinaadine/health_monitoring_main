<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GrowthMonitoringHistoryModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrowthMonitoringModel extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'growth_monitoring';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function history()
    {
        return $this->hasMany(GrowthMonitoringHistoryModel::class, 'id_growth', 'id');
    }
}
