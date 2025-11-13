<?php

namespace App\Models;

use App\Models\ChildrenModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NutritionTargetModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nutrition_target';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = ['id'];

    protected $casts = ['vitamin' => 'array'];

    public function child()
    {
        return $this->belongsTo(ChildrenModel::class);
    }
}
