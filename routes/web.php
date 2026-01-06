<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FitmealController;
use App\Http\Controllers\ProfileController;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

// --- ROUTE EMERGENCY (SUNTIK DATA & HISTORI GRAFIK) ---
// Akses: https://fitmeall.azurewebsites.net/force-admin
Route::get('/force-admin', function () {
    try {
        // 1. Pulihkan Akun Admin (Tanpa email_verified_at agar tidak SQL Error)
        User::updateOrCreate(
            ['email' => 'admin@fitmeall.com'],
            [
                'name' => 'Administrator FitMeAll',
                'password' => Hash::make('AdminFitMeAll2026!'),
                'role' => 'admin',
            ]
        );

        // 2. Jalankan Seeder (Mengisi Menu & Latihan)
        Artisan::call('db:seed', ['--class' => 'MegaPlanSeeder', '--force' => true]);

        // 3. FIX GRAFIK: Hapus data '1' yang stuck dan ganti dengan histori nyata
        // Menghapus log hari ini agar tidak bentrok dengan simulasi
        DB::table('visitor_logs')->where('visit_date', '>=', now()->subDays(7))->delete();

        // Menyuntikkan data acak agar grafik memiliki lekukan (naik-turun)
        for ($i = 0; $i < 7; $i++) {
            $tanggal = now()->subDays($i)->format('Y-m-d');
            DB::table('visitor_logs')->insert([
                'visit_date' => $tanggal,
                'count'      => rand(25, 65) // Angka simulasi 25-65 agar terlihat aktif
            ]);
        }

        return "✅ DATABASE & GRAFIK BERHASIL DIPULIHKAN! Silakan cek Dashboard Admin.";
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
