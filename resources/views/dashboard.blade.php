<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">Dashboard</h2>
            @if(Auth::user()->is_subscribed)
                <span class="bg-emerald-100 text-emerald-700 px-4 py-1 rounded-full text-sm font-bold border border-emerald-200">✨ Premium</span>
            @endif
        </div>
    </x-slot>

    <script src="{{ config('services.midtrans.snap_url') }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(Auth::user()->is_subscribed)
                @include('dashboard-parts.premium')
            @else
                @include('dashboard-parts.free')
            @endif

        </div>
    </div>

    <script>
        document.getElementById('pay-btn')?.addEventListener('click', function () {
            fetch('{{ route("pay") }}', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            })
            .then(r => r.json()).then(d => window.snap.pay(d.snap_token));
        });
    </script>
</x-app-layout>
