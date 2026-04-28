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
            ['username' => 'superadmin'],
            [
                'name'     => 'Super Admin',
                'username' => 'superadmin',
                'email'    => 'admin@luxelaundry.com',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );
    }
}
