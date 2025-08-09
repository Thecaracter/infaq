<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@simaniis.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        User::create([
            'name' => 'Administrator R',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Tata Usaha
        User::create([
            'name' => 'Tata Usaha 1',
            'email' => 'tu1@simaniis.com',
            'password' => Hash::make('tatatusaha123'),
            'role' => 'tu',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Tata Usaha 2',
            'email' => 'tu2@simaniis.com',
            'password' => Hash::make('tatatusaha123'),
            'role' => 'tu',
            'is_active' => true,
        ]);
    }
}