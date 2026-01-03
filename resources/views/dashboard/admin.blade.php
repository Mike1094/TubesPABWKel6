<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-400">
                    <div class="text-gray-500 text-sm font-medium">Laporan Pending</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['reports_pending'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-400">
                    <div class="text-gray-500 text-sm font-medium">Total Laporan</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['reports_total'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-400">
                    <div class="text-gray-500 text-sm font-medium">Barang Hilang</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['lost_items'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-400">
                    <div class="text-gray-500 text-sm font-medium">Barang Ditemukan</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['found_items'] }}</div>
                </div>
            </div>

            <!-- Management Section -->
            <div class="mb-8">
                <a href="{{ route('admin.users.index') }}" class="block p-6 bg-indigo-600 rounded-lg shadow-lg hover:bg-indigo-700 transition duration-300 transform hover:scale-105">
                    <div class="flex items-center justify-between text-white">
                        <div>
                            <h3 class="text-xl font-bold">Kelola Pengguna</h3>
                            <p class="mt-1 text-indigo-100">Tambah akun Civitas atau Satpam baru.</p>
                        </div>
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Validation List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Validasi Laporan Masuk</h3>
                    
                    @if($pendingReports->isEmpty())
                        <p class="text-gray-500 italic">Tidak ada laporan yang perlu divalidasi saat ini.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul / Lokasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendingReports as $report)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $report->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="font-bold border-b border-gray-100 pb-1 mb-1">{{ $report->title }}</div>
                                            {{ $report->location }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                            {{Str::limit($report->description, 50)}}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <form action="{{ route('reports.update-status', $report) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="validated">
                                                <button type="submit" class="text-green-600 hover:text-green-900 font-bold bg-green-100 px-3 py-1 rounded">Terima</button>
                                            </form>
                                            <form action="{{ route('reports.update-status', $report) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold bg-red-100 px-3 py-1 rounded">Tolak</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Validation List (Lost & Found) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Validasi Barang Hilang / Ditemukan</h3>
                    
                    @if($pendingLostFound->isEmpty())
                        <p class="text-gray-500 italic">Tidak ada laporan barang yang perlu divalidasi.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi (ACC)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendingLostFound as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold uppercase {{ $item->type == 'lost' ? 'text-red-500' : 'text-blue-500' }}">
                                            {{ $item->type }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->item_name }} ({{ $item->user->name }})
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <form action="{{ route('lost-found.update-status', $item) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="open">
                                                <button type="submit" class="text-green-600 hover:text-green-900 font-bold bg-green-100 px-3 py-1 rounded">Terima (Publish)</button>
                                            </form>
                                            <form action="{{ route('lost-found.update-status', $item) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold bg-red-100 px-3 py-1 rounded">Tolak</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
