<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Logika mencatat pengunjung secara otomatis
        try {
            DB::table('visitor_logs')->insert([
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'visit_date' => now()->toDateString(), // Penting untuk grouping grafik per hari
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Biarkan request tetap jalan meskipun logging gagal
            report($e);
        }

        return $next($request);
    }
}
