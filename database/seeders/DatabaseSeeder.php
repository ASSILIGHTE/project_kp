<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin123@gmail.com'],
            [
                'name' => 'Administrator Cyber Crime',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
            ]
        );

        // Petugas
        User::firstOrCreate(
            ['email' => 'petugas123@gmail.com'],
            [
                'name' => 'Petugas STTP Ditreskrimsus',
                'password' => bcrypt('petugas123'),
                'role' => 'petugas',
            ]
        );
    }
}
