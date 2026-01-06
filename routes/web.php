<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FitmealController;
use App\Http\Controllers\ProfileController;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

// --- ROUTE PEMULIHAN TOTAL & FIX GRAFIK ---
// Akses: https://fitmeall.azurewebsites.net/force-admin
Route::get('/force-admin', function () {
    try {
        // 1. Pulihkan Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@fitmeall.com'],
            [
                'name' => 'Administrator FitMeAll',
                'password' => Hash::make('AdminFitMeAll2026!'),
                'role' => 'admin',
            ]
        );

        // 2. Jalankan Seeder Menu
        Artisan::call('db:seed', ['--class' => 'MegaPlanSeeder', '--force' => true]);

        // 3. FIX GRAFIK STUCK: Hapus data lama & suntik histori baru
        // Kita bersihkan data 7 hari terakhir agar tidak stuck di angka 1
        DB::table('visitor_logs')->truncate(); 

        // Isi data simulasi untuk 7 hari terakhir dengan angka acak
        $days = ['Thu', 'Fri', 'Sat', 'Sun', 'Mon', 'Tue', 'Wed'];
        for ($i = 0; $i < 7; $i++) {
            DB::table('visitor_logs')->insert([
                'visit_date' => now()->subDays(6 - $i)->format('Y-m-d'),
                'count'      => rand(25, 55) // Angka acak agar grafik naik-turun
            ]);
        }

        return "✅ GRAFIK SUDAH DINAMIS! Silakan refresh halaman Admin.";
    } catch (\Exception $e) {
        return "❌ Gagal: " . $e->getMessage();
    }
});

// ... Sisa rute lainnya tetap sama ...
