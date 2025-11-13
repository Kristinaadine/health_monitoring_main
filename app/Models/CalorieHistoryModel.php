<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalorieHistoryModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age',
        'sex',
        'height',
        'weight',
        'activity_level',
        'gain_loss_amount',
        'daily_calories',
        'carbs',
        'protein',
        'fat',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
