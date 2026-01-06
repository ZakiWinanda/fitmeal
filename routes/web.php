<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FitmealController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

// --- ROUTE EMERGENCY (VERSI AMAN TANPA EMAIL_VERIFIED_AT) ---
Route::get('/force-admin', function () {
    try {
        // 1. Buat admin tanpa kolom email_verified_at agar tidak SQL Error
        User::updateOrCreate(
            ['email' => 'admin@fitmeall.com'],
            [
                'name' => 'Administrator FitMeAll',
                'password' => Hash::make('AdminFitMeAll2026!'),
                'role' => 'admin',
            ]
        );

        // 2. Paksa isi data menu & latihan
        Artisan::call('db:seed', [
            '--class' => 'MegaPlanSeeder',
            '--force' => true
        ]);

        // 3. Isi data grafik dummy (7 hari terakhir) agar grafik muncul
        for ($i = 0; $i < 7; $i++) {
            DB::table('visitor_logs')->updateOrInsert(
                ['visit_date' => now()->subDays($i)->format('Y-m-d')],
                ['count' => rand(20, 50)]
            );
        }

        return "✅ BERHASIL! Akun Admin & Grafik telah dipulihkan. Silakan login.";
    } catch (\Exception $e) {
        return "❌ Masih Error: " . $e->getMessage();
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
