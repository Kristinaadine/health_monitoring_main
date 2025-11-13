<?php

namespace Database\Seeders;

use App\Models\SettingModel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'key' => 'website_name',
                'value' => __('home.websiteTitle'),
            ],
            [
                'key' => 'website_logo',
                'value' => 'logo.svg',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
            ],
        ];
        foreach ($data as $item) {
            SettingModel::create($item);
        }
    }
}
