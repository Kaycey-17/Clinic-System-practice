<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@clinic.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Staff User',
            'email' => 'staff@clinic.com',
            'password' => Hash::make('password'),
        ]);
    }
}