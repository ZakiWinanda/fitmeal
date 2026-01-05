<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. DAFTARKAN MIDDLEWARE TRACKVISITOR (Kode asli Anda)
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitor::class,
        ]);

        // 2. TAMBAHKAN TRUST PROXIES (Saran Perbaikan)
        // Ini sangat krusial agar Laravel mengenali koneksi HTTPS dari Azure Load Balancer
        // dan mencegah error "419 Page Expired" saat login.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
