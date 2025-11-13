<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NutrientRatioModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NutrientRatioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => "@t('Low Carb')",
                'protein' => 40,
                'carbs' => 20,
                'fat' => 40,
                'login_created' => 'admin@admin.com'
            ],
            [
                'name' => 'Balanced',
                'protein' => 30,
                'carbs' => 40,
                'fat' => 30,
                'login_created' => 'admin@admin.com'
            ],
            [
                'name' => 'High Protein',
                'protein' => 40,
                'carbs' => 40,
                'fat' => 20,
                'login_created' => 'admin@admin.com'
            ],
        ];
        foreach ($data as $item) {
            NutrientRatioModel::create($item);
        }
    }
}
