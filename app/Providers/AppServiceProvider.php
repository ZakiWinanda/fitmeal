<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Memaksa skema URL ke HTTPS jika di lingkungan production (Azure)
        // Ini untuk menghilangkan peringatan "Information not secure" di browser
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // 2. Mendefinisikan hak akses 'admin' (Kode asli Anda)
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
