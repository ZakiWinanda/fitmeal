<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FitmealController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use App\Models\User; // Tambahan untuk force-admin
use Illuminate\Support\Facades\Hash; // Tambahan untuk force-admin

// --- ROUTE EMERGENCY (Pancingan Admin) ---
// Akses: https://fitmeall.azurewebsites.net/force-admin
Route::get('/force-admin', function () {
    $user = User::updateOrCreate(
        ['email' => 'admin@fitmeall.com'],
        [
            'name' => 'Admin Paksa',
            'password' => Hash::make('AdminFitMeAll2026!'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]
    );
    return "Akun Admin Berhasil Dibuat/Diperbarui! Silakan kembali ke halaman /login";
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
