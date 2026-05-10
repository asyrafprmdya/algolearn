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
            'name' => 'Administrator',
            'email' => 'admin@algolearn.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Dosen Algoritma',
            'email' => 'dosen@algolearn.com',
            'password' => Hash::make('password123'),
            'role' => 'lecturer',
        ]);

        User::create([
            'name' => 'Budi Tabuti',
            'email' => 'student@algolearn.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);
    }
}