<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\NutrientRatioSeeder;
use Database\Seeders\PreStuntingSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            NutrientRatioSeeder::class,
            SettingSeeder::class,
            PreStuntingSeeder::class,
        ]);
    }
}
