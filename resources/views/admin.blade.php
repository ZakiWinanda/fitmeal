<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-red-800 tracking-tighter uppercase">Admin Control Center</h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto px-4 space-y-10">

        <div class="bg-white p-8 rounded-[3.5rem] shadow-xl border border-gray-100">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h3 class="font-black text-xl italic flex items-center gap-3">
                        <span class="p-2 bg-indigo-100 rounded-lg text-indigo-600">📈</span> Traffic Pengunjung Mingguan
                    </h3>
                    <p class="text-xs text-gray-400 mt-1 ml-12">Data klik masuk tercatat secara otomatis melalui sistem.</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Total Hit Hari Ini</p>
                    {{-- Perbaikan: Menggunakan ->last() untuk menghindari error reference --}}
                    <p class="text-4xl font-black text-indigo-600 leading-none mt-2">
                        {{ $visitorData['data']->last() ?? 0 }}
                    </p>
                </div>
            </div>
            <div class="h-80 w-full">
                <canvas id="realVisitorChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <div class="lg:col-span-4 bg-white p-10 rounded-[3rem] shadow-xl border border-gray-100 sticky top-10">
                <h3 class="font-black text-xl mb-8 flex items-center gap-3 italic">
                    <span class="p-2 bg-red-100 rounded-lg text-red-600">🎯</span> Tambah Data Baru
                </h3>
                <form action="{{ route('admin.plan') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Tipe Program</label>
                        <select name="type" class="w-full rounded-2xl border-none bg-gray-50 p-4 font-black text-gray-700 text-sm shadow-inner focus:ring-2 focus:ring-red-500">
                            <option value="nutrition">Resep Makanan</option>
                            <option value="workout">Training Plan</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-red-400 uppercase ml-2">Kategori BMI Target</label>
                        <select name="category" class="w-full rounded-2xl border-none bg-red-50 p-4 font-black text-red-600 shadow-inner text-sm">
                            <option value="low">UNTUK BMR RENDAH ( < 1500 Kcal )</option>
                            <option value="ideal" selected>UNTUK BMR IDEAL ( 1500 - 2000 Kcal )</option>
                            <option value="high">UNTUK BMR TINGGI ( > 2000 Kcal )</option>
                        </select>
                    </div>

                    <input type="text" name="title" placeholder="Nama Menu / Olahraga" class="w-full rounded-2xl border-none bg-gray-50 p-4 font-bold text-sm shadow-inner" required>
                    <input type="number" name="calories" placeholder="Estimasi Kalori" class="w-full rounded-2xl border-none bg-gray-50 p-4 font-bold text-sm shadow-inner" required>
                    <textarea name="instructions" placeholder="Resep Lengkap / Panduan Gerakan..." class="w-full rounded-2xl border-none bg-gray-50 p-4 h-32 text-sm font-medium shadow-inner" required></textarea>

                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-black py-5 rounded-3xl shadow-xl transition-all active:scale-95 uppercase tracking-widest text-xs">Simpan Data</button>
                </form>
            </div>

            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-gray-100">
                    <h3 class="font-black text-xl mb-6 flex items-center gap-3 italic"><span class="p-2 bg-emerald-100 rounded-lg text-emerald-600">👥</span> User List</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <tr class="text-gray-400 font-black uppercase text-[10px] border-b">
                                <th class="pb-3">Nama</th><th class="pb-3 text-center">Akses</th><th class="pb-3 text-right">Aksi</th>
                            </tr>
                            @foreach($users as $u)
                            <tr class="border-b border-gray-50">
                                <td class="py-4 font-bold text-gray-700">{{ $u->name }}</td>
                                <td class="text-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black {{ $u->is_subscribed ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-400' }}">
                                        {{ $u->is_subscribed ? 'PREMIUM' : 'FREE' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <button onclick="editUserProfile('{{ $u->id }}', '{{ $u->name }}', '{{ $u->email }}', '{{ $u->is_subscribed }}')" class="bg-indigo-600 text-white px-4 py-1.5 rounded-xl font-black text-[9px] uppercase hover:bg-indigo-700 transition-all">EDIT</button>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-gray-100">
                    <h3 class="font-black text-xl mb-6 italic flex items-center gap-3"><span class="p-2 bg-orange-100 rounded-lg text-orange-600">🥘</span> Database Konten</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-gray-400 font-black uppercase text-[10px] border-b">
                                    <th class="pb-3">Tipe</th><th class="pb-3">Judul</th><th class="pb-3 text-center">BMI Target</th><th class="pb-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plans as $plan)
                                <tr class="border-b border-gray-50">
                                    <td class="py-4">
                                        <span class="px-2 py-1 rounded-lg font-black text-[9px] uppercase {{ $plan->type == 'nutrition' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                                            {{ $plan->type }}
                                        </span>
                                    </td>
                                    <td class="font-bold text-gray-700">{{ $plan->title }}</td>
                                    <td class="text-center">
                                        <span class="text-[10px] font-black uppercase text-red-500">{{ $plan->category ?? 'ideal' }}</span>
                                    </td>
                                    <td class="text-right">
                                        {{-- Form Hapus --}}
                                        <form action="/admin/plan/delete/{{ $plan->id }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 font-black text-[10px] hover:underline">HAPUS</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Chart logic...
        const ctx = document.getElementById('realVisitorChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($visitorData['labels']) !!},
                datasets: [{
                    label: 'Klik Pengunjung',
                    data: {!! json_encode($visitorData['data']) !!},
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#fff',
                    borderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f8fafc' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Function editUserProfile...
        function editUserProfile(id, name, email, isSub) {
            Swal.fire({
                title: '<h2 class="font-black italic text-gray-800">UPDATE PROFIL USER</h2>',
                html: `
                    <form id="updateUserForm" action="/admin/user/update/${id}" method="POST" class="text-left space-y-5 p-4">
                        @csrf
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Nama Lengkap</label>
                            <input type="text" name="name" value="${name}" class="w-full mt-2 border-none bg-gray-100 rounded-2xl p-4 font-black text-gray-700 shadow-inner">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Email Akun</label>
                            <input type="email" name="email" value="${email}" class="w-full mt-2 border-none bg-gray-100 rounded-2xl p-4 font-black text-gray-700 shadow-inner">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">Akses Membership</label>
                            <select name="is_subscribed" class="w-full mt-2 border-none bg-gray-100 rounded-2xl p-4 font-black text-gray-700 shadow-inner">
                                <option value="0" ${isSub == '' ? 'selected' : ''}>FREE MEMBER</option>
                                <option value="1" ${isSub == '1' ? 'selected' : ''}>PREMIUM MEMBER</option>
                            </select>
                        </div>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'SAVE CHANGES',
                confirmButtonColor: '#111827',
                cancelButtonText: 'CANCEL',
                customClass: { popup: 'rounded-[3.5rem] p-10 shadow-2xl' }
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById('updateUserForm').submit(); }
            });
        }
    </script>
</x-app-layout>
