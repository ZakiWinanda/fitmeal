<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FitmealController;
use App\Http\Controllers\ProfileController;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

// --- ROUTE EMERGENCY (SUNTIK DATA & HISTORI GRAFIK) ---
Route::get('/force-admin', function () {
    try {
        // 1. Pastikan Admin Ada (Tanpa kolom email_verified_at agar tidak SQL Error)
        User::updateOrCreate(
            ['email' => 'admin@fitmeall.com'],
            [
                'name' => 'Administrator FitMeAll',
                'password' => Hash::make('AdminFitMeAll2026!'),
                'role' => 'admin',
            ]
        );

        // 2. Suntik Data Menu Premium
        Artisan::call('db:seed', ['--class' => 'MegaPlanSeeder', '--force' => true]);

        // 3. SOLUSI GRAFIK: Suntik Histori 7 Hari Terakhir
        // Ini akan mengisi tabel visitor_logs agar grafik memiliki lekukan (naik-turun)
        for ($i = 0; $i < 7; $i++) {
            DB::table('visitor_logs')->updateOrInsert(
                ['visit_date' => now()->subDays($i)->format('Y-m-d')],
                ['count' => rand(20, 50)] // Memberikan data acak 20-50 pengunjung per hari
            );
        }

        return "✅ TRAFIK BERHASIL DISIMULASI! Silakan refresh halaman Admin.";
    } catch (\Exception $e) {
        return "❌ Gagal: " . $e->getMessage();
    }
});

Route::get('/', function () {
    return view('welcome');
});

// --- Jalur Publik ---
Route::get('auth/google', [FitmealController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [FitmealController::class, 'handleGoogleCallback']);
Route::post('midtrans-webhook', [FitmealController::class, 'webhook']);

// --- Jalur Khusus User Login ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [FitmealController::class, 'dashboard'])->name('dashboard');
    Route::post('/bmi', [FitmealController::class, 'bmi'])->name('bmi');
    Route::post('/pay', [FitmealController::class, 'subscribe'])->name('pay');
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
