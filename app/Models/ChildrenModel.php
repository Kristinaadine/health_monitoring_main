<?php

namespace App\Models;

use App\Models\User;
use App\Models\AlertModel;
use App\Models\FoodLogsModel;
use App\Models\GrowthLogsModel;
use App\Models\NutritionTargetModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChildrenModel extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'children';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function growthLogs()
    {
        return $this->hasMany(GrowthLogsModel::class);
    }
    public function foodLogs()
    {
        return $this->hasMany(FoodLogsModel::class);
    }
    public function nutritionTargets()
    {
        return $this->hasMany(NutritionTargetModel::class);
    }
    public function alerts()
    {
        return $this->hasMany(AlertModel::class);
    }
    public function nutritionTarget()
    {
        return $this->hasOne(NutritionTargetModel::class, 'child_id');
    }
}
