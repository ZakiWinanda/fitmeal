<div class="space-y-10 animate-fade-in pb-20">
    @php $prof = json_decode(Auth::user()->profile_data); @endphp

    <div class="bg-white rounded-[3rem] p-8 shadow-2xl border border-emerald-50">
        <div class="flex flex-col lg:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-emerald-200">F</div>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight">Dashboard Premium</h1>
                    <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">Hi, {{ Auth::user()->name }} • Your health is our priority</p>
                </div>
            </div>

            <form action="{{ route('bmi') }}" method="POST" class="flex items-end gap-4 bg-gray-50 p-4 rounded-[2.5rem] border border-white shadow-inner">
                @csrf
                <div class="flex gap-4">
                    <div class="text-center space-y-1">
                        <label class="text-[9px] font-black text-gray-400 uppercase">Berat (kg)</label>
                        <input type="number" name="weight" value="{{ $prof->weight ?? '' }}" class="w-20 border-none bg-white rounded-xl py-2 px-3 font-bold text-center shadow-sm focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div class="text-center space-y-1">
                        <label class="text-[9px] font-black text-gray-400 uppercase">Tinggi (cm)</label>
                        <input type="number" name="height" value="{{ $prof->height ?? '' }}" class="w-20 border-none bg-white rounded-xl py-2 px-3 font-bold text-center shadow-sm focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
                <button type="submit" class="bg-gray-900 hover:bg-black text-white px-8 py-2.5 rounded-2xl font-black shadow-lg transition-all active:scale-95 text-xs">RECALCULATE</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white rounded-[3.5rem] p-10 shadow-2xl sticky top-10 border border-gray-50">
                <h3 class="text-xl font-black italic uppercase mb-10 tracking-tighter flex items-center gap-2">
                    <span class="w-2 h-6 bg-indigo-500 rounded-full"></span> Target Nutrisi
                </h3>

                @if($prof && isset($prof->nutrisi))
                <div class="space-y-10">
                    <div class="space-y-8">
                        <div>
                            <div class="flex justify-between text-[10px] font-black mb-3 uppercase tracking-widest text-blue-500"><span>Protein (25%)</span><span>{{ $prof->nutrisi->protein }}g</span></div>
                            <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden shadow-inner"><div class="bg-gradient-to-r from-blue-400 to-blue-600 h-full rounded-full" style="width: 25%"></div></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-[10px] font-black mb-3 uppercase tracking-widest text-yellow-500"><span>Karbohidrat (50%)</span><span>{{ $prof->nutrisi->karbo }}g</span></div>
                            <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden shadow-inner"><div class="bg-gradient-to-r from-yellow-300 to-yellow-500 h-full rounded-full" style="width: 50%"></div></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-[10px] font-black mb-3 uppercase tracking-widest text-red-500"><span>Lemak Sehat (25%)</span><span>{{ $prof->nutrisi->lemak }}g</span></div>
                            <div class="w-full bg-gray-100 h-3 rounded-full overflow-hidden shadow-inner"><div class="bg-gradient-to-r from-red-400 to-red-600 h-full rounded-full" style="width: 25%"></div></div>
                        </div>
                    </div>

                    <div class="pt-10 text-center border-t-2 border-dashed border-gray-50">
                        <p class="text-[10px] text-gray-400 font-black tracking-[0.4em] uppercase mb-2">Target Energi Harian</p>
                        <h4 class="text-7xl font-black text-gray-900 tracking-tighter leading-none">{{ $prof->bmr }}</h4>
                        <div class="mt-8 grid grid-cols-2 gap-4">
                            <div class="bg-indigo-50 p-4 rounded-3xl border border-indigo-100">
                                <p class="text-[9px] font-black text-indigo-400 uppercase mb-1">BMI Score</p>
                                <p class="text-xl font-black text-indigo-700">{{ $prof->bmi }}</p>
                            </div>
                            <div class="bg-emerald-50 p-4 rounded-3xl border border-emerald-100">
                                <p class="text-[9px] font-black text-emerald-400 uppercase mb-1">Status</p>
                                <p class="text-sm font-black text-emerald-700">Healthy</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-8 space-y-12">

            <div class="space-y-6">
                <div class="flex items-center justify-between px-4">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight italic flex items-center gap-3">
                        <span class="p-2 bg-orange-100 rounded-xl">🍎</span> 20 Menu Rekomendasi
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[900px] overflow-y-auto pr-4 custom-scrollbar">
                    @forelse($plans->where('type', 'nutrition') as $p)
                    <div onclick="openModal('{{ $p->title }}', '{{ $p->instructions }}', '{{ $p->calories }} Kcal', 'orange')"
                         class="group bg-white p-8 rounded-[3rem] shadow-sm border-b-[8px] border-orange-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 cursor-pointer border border-gray-100">
                        <h4 class="font-black text-gray-800 text-lg leading-tight group-hover:text-orange-600 transition-colors">{{ $p->title }}</h4>
                        <p class="text-xs text-gray-400 mt-3 line-clamp-2 font-medium">{{ $p->description }}</p>
                        <div class="mt-6 flex justify-between items-center bg-orange-50/50 p-4 rounded-2xl border border-orange-100/50">
                            <span class="text-lg font-black text-orange-600">{{ $p->calories }} <small class="text-[9px] uppercase">Kcal</small></span>
                            <span class="text-[9px] font-black uppercase text-orange-400 tracking-widest">Resep →</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-400 py-10">Belum ada menu makan tersedia.</p>
                    @endforelse
                </div>
            </div>

            <div class="space-y-6">
                <div class="px-4">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tight italic flex items-center gap-3">
                        <span class="p-2 bg-blue-100 rounded-xl">🏋️</span> 10 Program Latihan
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($plans->where('type', 'workout') as $p)
                    <div onclick="openModal('{{ $p->title }}', '{{ $p->instructions }}', '{{ $p->calories }} Kcal Burn', 'blue')"
                         class="group bg-white p-8 rounded-[3rem] shadow-sm border-b-[8px] border-blue-500 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 cursor-pointer border border-gray-100">
                        <h4 class="font-black text-gray-800 text-lg leading-tight group-hover:text-blue-600 transition-colors">{{ $p->title }}</h4>
                        <p class="text-xs text-gray-400 mt-3 line-clamp-2 font-medium">{{ $p->description }}</p>
                        <div class="mt-6 flex justify-between items-center bg-blue-50/50 p-4 rounded-2xl border border-blue-100/50">
                            <span class="text-lg font-black text-blue-600">{{ $p->calories }} <small class="text-[9px] uppercase">Kcal</small></span>
                            <span class="text-[9px] font-black uppercase text-blue-400 tracking-widest">Panduan →</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openModal(title, instr, cal, color) {
        Swal.fire({
            title: `<span class="font-black text-${color}-600 uppercase text-2xl">${title}</span>`,
            html: `
                <div class="text-left space-y-4">
                    <div class="flex items-center gap-2 text-xs font-black text-gray-400 uppercase tracking-widest border-b pb-2">
                        <span>📋</span>
                        <span>${color === 'orange' ? 'Resep & Cara Memasak' : 'Panduan Gerakan'}</span>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100 font-medium italic text-gray-600 leading-relaxed text-sm">
                        ${instr}
                    </div>
                    <div class="p-4 bg-${color}-50 rounded-2xl text-center">
                        <p class="text-[10px] font-black text-${color}-400 uppercase mb-1">Estimasi Energi</p>
                        <p class="text-xl font-black text-${color}-600">${cal}</p>
                    </div>
                </div>
            `,
            confirmButtonText: 'Selesai Membaca',
            confirmButtonColor: color === 'orange' ? '#f97316' : '#3b82f6',
            customClass: {
                popup: 'rounded-[3.5rem] p-10',
                confirmButton: 'rounded-2xl px-10 py-4 font-black uppercase tracking-widest text-xs'
            }
        });
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; border: 2px solid #f8fafc; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
