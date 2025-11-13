<?php

namespace App\Models;

use App\Models\ChildrenModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodLogsModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'food_logs';

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
