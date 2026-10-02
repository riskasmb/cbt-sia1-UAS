<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin / Developer
        User::updateOrCreate(
            ['username' => 'developer'],
            [
                'name' => 'Riska sembiring',
                'email' => 'riskasmb@gmail.com',
                'is_staff' => true,
                'password' => Hash::make('rahasia'),
            ]
        );

        // 2. Akun Siswa Pertama (Yaya)
        User::updateOrCreate(
            ['username' => '2024010004'],
            [
                'name' => 'Yaya',
                'email' => 'yaya@student.test',
                'is_staff' => false,
                'password' => Hash::make('12345678'),
            ]
        );

      
        
    }
}