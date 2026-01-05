<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Akun Admin (UpdateOrCreate agar tidak duplikat jika dijalankan ulang)
        User::updateOrCreate(
            ['email' => 'admin@fitmeall.com'], // Pencarian berdasarkan email
            [
                'name' => 'Administrator FitMeAll',
                'password' => Hash::make('AdminFitMeAll2026!'), // Password Anda
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Membuat Test User (Bawaan Laravel)
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // 3. Memanggil MegaPlanSeeder (Untuk Menu & Olahraga)
        $this->call([
            MegaPlanSeeder::class,
        ]);
    }
}
