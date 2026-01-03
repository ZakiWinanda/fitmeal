<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fitmeal - Healthier Life Starts Here</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
    </style>
</head>
<body class="antialiased bg-gray-50">
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-emerald-600">Fitmeal</span>
                </div>
                <div class="space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-emerald-600 font-semibold">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-emerald-600 font-semibold">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-emerald-600 text-white px-5 py-2 rounded-full hover:bg-emerald-700 transition font-semibold shadow-md">Daftar Sekarang</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="hero-gradient text-white py-20 px-4">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 space-y-6">
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight">
                    Kelola Nutrisi & <br> Kesehatan Anda <br> <span class="text-emerald-200">Dengan Pintar</span>
                </h1>
                <p class="text-lg text-emerald-50 opacity-90">
                    Fitmeal membantu Anda menghitung BMI, BMR, menyediakan resep makanan harian, dan jadwal olahraga yang personal.
                </p>
                <div class="pt-4">
                    <a href="{{ route('register') }}" class="bg-white text-emerald-600 px-8 py-3 rounded-full text-lg font-bold hover:bg-gray-100 transition shadow-lg inline-block">Mulai Gratis Sekarang</a>
                </div>
            </div>
            <div class="md:w-1/2 mt-12 md:mt-0 flex justify-center">
                <svg class="w-64 h-64 text-emerald-200 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-20 px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800">Kenapa Memilih Fitmeal?</h2>
            <div class="w-20 h-1 bg-emerald-500 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="bg-emerald-100 w-14 h-14 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Kalkulator BMI & BMR</h3>
                <p class="text-gray-600">Cek status kesehatan Anda secara gratis dan dapatkan perhitungan kalori harian yang akurat.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="bg-orange-100 w-14 h-14 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Resep Makanan Harian</h3>
                <p class="text-gray-600">Khusus pengguna premium, nikmati resep sehat yang disesuaikan dengan kebutuhan kalori Anda.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition">
                <div class="bg-blue-100 w-14 h-14 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Program Olahraga</h3>
                <p class="text-gray-600">Dapatkan jadwal latihan harian untuk membantu Anda mencapai target berat badan impian.</p>
            </div>
        </div>
    </div>

    <footer class="bg-white border-t py-10 mt-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-500">&copy; 2026 Fitmeal. Platform Kesehatan No. 1 di Indonesia.</p>
        </div>
    </footer>
</body>
</html>
