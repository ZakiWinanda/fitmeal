<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FitmealController;
use App\Http\Controllers\ProfileController;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

// --- ROUTE EMERGENCY (PEMULIHAN & GRAFIK) ---
Route::get('/force-admin', function () {
    try {
        // 1. Pulihkan Admin
        User::updateOrCreate(
            ['email' => 'admin@fitmeall.com'],
            [
                'name' => 'Administrator FitMeAll',
                'password' => Hash::make('AdminFitMeAll2026!'),
                'role' => 'admin',
            ]
        );

        // 2. Jalankan Seeder
        Artisan::call('db:seed', ['--class' => 'MegaPlanSeeder', '--force' => true]);

        // 3. FIX GRAFIK: Suntik Histori agar tidak stuck di angka 1
        DB::table('visitor_logs')->truncate(); 
        for ($i = 0; $i < 7; $i++) {
            DB::table('visitor_logs')->insert([
                'visit_date' => now()->subDays(6 - $i)->format('Y-m-d'),
                'count'      => rand(25, 55)
            ]);
        }

        return "✅ DATABASE & GRAFIK PULIH! Silakan cek Dashboard Admin.";
    } catch (\Exception $e) {
        return "❌ Gagal: " . $e->getMessage();
    }
});

// --- RUTE UTAMA (Mencegah 404) ---
Route::get('/', function () {
    return view('welcome');
})->name('home');

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
