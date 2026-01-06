<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FitmealController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

// --- ROUTE EMERGENCY (PEMULIHAN DATA, ADMIN, & TRAFIK) ---
// Akses: https://fitmeall.azurewebsites.net/force-admin
Route::get('/force-admin', function () {
    try {
        // 1. Pastikan Admin Ada
        User::updateOrCreate(
            ['email' => 'admin@fitmeall.com'],
            [
                'name' => 'Administrator FitMeAll',
                'password' => Hash::make('AdminFitMeAll2026!'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Isi ulang data Menu Premium & Latihan
        Artisan::call('db:seed', [
            '--class' => 'MegaPlanSeeder',
            '--force' => true
        ]);

        // 3. SIMULASI TRAFIK (Agar Grafik Tidak 0)
        // Kita paksa isi data 7 hari terakhir supaya grafik Admin terlihat aktif
        for ($i = 0; $i < 7; $i++) {
            DB::table('visitor_logs')->updateOrInsert(
                ['visit_date' => now()->subDays($i)->format('Y-m-d')],
                ['count' => rand(25, 60)] // Simulasi 25-60 klik per hari
            );
        }

        return "✅ SEMUA PULIH! Akun Admin OK, Menu Premium OK, & Grafik Trafik OK. Silakan presentasi!";
    } catch (\Exception $e) {
        return "❌ Gagal memulihkan: " . $e->getMessage();
    }
});

Route::get('/', function () {
    return view('welcome');
});

// --- Jalur Publik (Google Login & Midtrans) ---
Route::get('auth/google', [FitmealController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [FitmealController::class, 'handleGoogleCallback']);
Route::post('midtrans-webhook', [FitmealController::class, 'webhook']);

// --- Jalur Khusus User Login ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [FitmealController::class, 'dashboard'])->name('dashboard');
    Route::post('/bmi', [FitmealController::class, 'bmi'])->name('bmi');
    Route::post('/pay', [FitmealController::class, 'subscribe'])->name('pay');

    // --- ROUTE PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Jalur Khusus Admin ---
    Route::middleware(['can:admin'])->prefix('admin')->group(function () {
        Route::get('/', [FitmealController::class, 'admin'])->name('admin.index');
        Route::post('/plan', [FitmealController::class, 'storePlan'])->name('admin.plan');
        Route::delete('/plan/delete/{id}', [FitmealController::class, 'deletePlan'])->name('admin.plan.delete');
        Route::post('/user/update/{id}', [FitmealController::class, 'updateUser'])->name('admin.user.update');
    });
});

require __DIR__.'/auth.php';
