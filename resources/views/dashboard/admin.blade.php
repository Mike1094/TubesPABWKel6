<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl font-bold">Total Laporan</div>
                    <div class="text-4xl text-blue-600">{{ $total_reports }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl font-bold">Menunggu Validasi</div>
                    <div class="text-4xl text-red-600">{{ $pending_reports }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-900 text-xl font-bold">Gate Terbuka</div>
                    <div class="text-4xl text-green-600">{{ $open_gates }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-4">Analisa Laporan Harian</h3>
                <canvas id="reportChart"></canvas>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Validasi Laporan Masuk</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recent_reports as $report)
                        <tr>
                            <td class="px-6 py-4">{{ $report->judul }}</td>
                            <td class="px-6 py-4">{{ $report->user->name }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('reports.update-status', $report->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button name="status" value="validated" class="text-green-600 hover:text-green-900">Validasi</button>
                                    <button name="status" value="rejected" class="text-red-600 hover:text-red-900 ml-2">Tolak</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('reportChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($chart_data->toArray())) !!},
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: {!! json_encode(array_values($chart_data->toArray())) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });
    </script>
</x-app-layout>
