<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Civitas Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Akses Cepat</h3>
                <div class="flex gap-4">
                    <a href="{{ route('reports.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        + Buat Laporan Kerusakan
                    </a>
                    <a href="{{ route('lost-found.create') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                        + Lapor Barang Hilang
                    </a>
                </div>
            </div>

            <!-- My Reports History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Riwayat Laporan Saya</h3>
                @if($myReports->isEmpty())
                     <p class="text-gray-500 italic">Belum ada laporan kerusakan yang Anda buat.</p>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach($myReports as $report)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <div class="font-bold text-gray-800">{{ $report->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $report->location }} | {{ $report->created_at->format('d M Y') }}</div>
                                </div>
                                <div>
                                    <span class="px-2 py-1 text-xs font-bold rounded-full
                                        {{ $report->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($report->status == 'validated' ? 'bg-blue-100 text-blue-800' : 
                                           ($report->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- My Lost & Found -->
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Laporan Barang Hilang/Ditemukan</h3>
                @if($myLostFound->isEmpty())
                     <p class="text-gray-500 italic">Belum ada laporan barang.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($myLostFound as $item)
                            <div class="border rounded-lg p-4 flex items-center bg-gray-50">
                                <div class="flex-1">
                                    <div class="text-xs font-bold uppercase {{ $item->type == 'lost' ? 'text-red-500' : 'text-blue-500' }}">{{ $item->type }}</div>
                                    <div class="font-bold text-gray-900">{{ $item->item_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->status }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
