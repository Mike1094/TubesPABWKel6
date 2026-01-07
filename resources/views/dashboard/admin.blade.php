<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Laporan</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $total_reports }}</h3>
                </div>
                <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Perlu Validasi</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $pending_reports }}</h3>
                </div>
                <div class="p-3 bg-yellow-50 rounded-full text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Gate Terbuka</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $open_gates }}</h3>
                </div>
                <div class="p-3 bg-green-50 rounded-full text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Barang Hilang</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $lost_items }}</h3>
                </div>
                <div class="p-3 bg-red-50 rounded-full text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
            <h3 class="font-bold text-lg mb-4 text-gray-800">Analisa Laporan Harian</h3>
            <canvas id="adminChart" class="w-full h-64"></canvas>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-bold text-lg mb-4 text-gray-800">Validasi Terbaru</h3>
            <div class="space-y-4">
                @forelse($recent_reports as $report)
                <div class="flex items-start gap-3 pb-4 border-b last:border-0">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex-shrink-0 flex items-center justify-center">
                        {{ substr($report->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $report->judul }}</p>
                        <p class="text-xs text-gray-500">{{ $report->user->name }} • {{ $report->created_at->diffForHumans() }}</p>
                        <div class="mt-2 flex gap-2">
                            <button class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:bg-green-200">Validasi</button>
                            <button class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded hover:bg-red-200">Tolak</button>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Tidak ada laporan baru.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('adminChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($chart_data->toArray())) !!},
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: {!! json_encode(array_values($chart_data->toArray())) !!},
                    borderColor: '#ef4444', // Red-500
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, grid: { borderDash: [2, 4] } }, x: { grid: { display: false } } }
            }
        });
    </script>
</x-app-layout>
