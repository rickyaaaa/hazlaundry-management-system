<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@luxelaundry.com'],
            [
                'name'     => 'Admin LuxeLaundry',
                'email'    => 'admin@luxelaundry.com',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );
    }
}
