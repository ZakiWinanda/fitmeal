<div class="space-y-10 animate-fade-in">
    <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-700 rounded-[2.5rem] p-10 text-white shadow-2xl">
        <div class="relative z-10 md:w-2/3">
            <h1 class="text-4xl font-black leading-tight">Mulai Hidup Sehat, <br>{{ Auth::user()->name }}!</h1>
            <p class="mt-4 text-emerald-50 text-lg opacity-90">Pantau BMI dan BMR Anda secara akurat untuk mencapai berat badan ideal.</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <button id="pay-btn" class="bg-white text-emerald-600 px-8 py-3 rounded-2xl font-black hover:bg-emerald-50 transition-all transform active:scale-95 shadow-lg">
                    Upgrade Premium Sekarang
                </button>
                <div class="flex items-center gap-2 text-sm font-medium text-emerald-100">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-300 animate-ping"></span>
                    Hanya Rp 50.000 / 3 Bulan
                </div>
            </div>
        </div>
        <div class="absolute right-[-10%] top-[-10%] opacity-10">
            <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 h-full flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-8">
                    <div class="p-3 bg-indigo-50 rounded-2xl text-indigo-600 shadow-inner">⚖️</div>
                    <h3 class="text-xl font-bold text-gray-800">Cek Fisik</h3>
                </div>

                <form action="{{ route('bmi') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Berat Badan (kg)</label>
                        <input type="number" name="weight" value="{{ json_decode(Auth::user()->profile_data)->weight ?? '' }}" placeholder="Contoh: 70"
                            class="w-full border-gray-100 rounded-2xl p-4 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Tinggi Badan (cm)</label>
                        <input type="number" name="height" value="{{ json_decode(Auth::user()->profile_data)->height ?? '' }}" placeholder="Contoh: 175"
                            class="w-full border-gray-100 rounded-2xl p-4 bg-gray-50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold" required>
                    </div>
                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-black py-4 rounded-2xl shadow-xl transition-all transform active:scale-95 mt-4">
                        Hitung Skor Saya
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 relative overflow-hidden">
            <h3 class="text-xl font-bold text-gray-800 mb-8 flex items-center gap-2">📊 Analisis Tubuh</h3>

            @php $prof = json_decode(Auth::user()->profile_data); @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-8 bg-indigo-50/50 rounded-[2rem] border border-indigo-100 text-center relative group overflow-hidden">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">Body Mass Index</p>
                    <h4 class="text-6xl font-black text-indigo-600 leading-none">{{ $prof->bmi ?? '--' }}</h4>
                    <div class="mt-4 inline-block bg-indigo-100 text-indigo-700 text-[10px] px-4 py-1 rounded-full font-black uppercase tracking-tighter">
                        Status: Normal
                    </div>
                </div>

                <div class="p-8 bg-emerald-50/50 rounded-[2rem] border border-emerald-100 text-center relative group overflow-hidden">
                    <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-2">Basal Metabolic Rate</p>
                    <h4 class="text-6xl font-black text-emerald-600 leading-none">{{ $prof->bmr ?? '--' }}</h4>
                    <p class="mt-2 text-gray-400 font-bold text-sm">Kcal / Hari</p>
                </div>
            </div>

            <div class="mt-8 p-6 bg-gradient-to-r from-orange-50 to-amber-50 rounded-3xl border border-orange-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4 text-center md:text-left">
                    <div class="text-3xl">✨</div>
                    <div>
                        <p class="font-black text-gray-800">Dapatkan Rekomendasi Nutrisi Otomatis</p>
                        <p class="text-xs text-gray-500">Berdasarkan BMR Anda, kami akan menyusun menu makan harian.</p>
                    </div>
                </div>
                <button onclick="document.getElementById('pay-btn').click()" class="shrink-0 bg-orange-500 text-white px-6 py-2 rounded-xl font-black text-sm hover:bg-orange-600 shadow-lg shadow-orange-200 transition-all">
                    Buka Fitur
                </button>
            </div>
        </div>
    </div>
</div>
