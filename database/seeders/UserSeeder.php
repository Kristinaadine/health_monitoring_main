<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'roles' => 'Admin',
                'password' => bcrypt('123456'),
                'login_created' => 'admin@admin.com',
            ],
            [
                'name' => 'User',
                'email' => 'user@user.com',
                'roles' => 'User',
                'calorie_target' => 1200,
                'nutrient_ration' => 1,
                'password' => bcrypt('123456'),
                'login_created' => 'user@user.com',
            ],
        ];

        foreach ($data as $item) {
            User::create($item);
        }
    }
}
