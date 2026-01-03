<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Warga Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Traffic Info Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Info Lalu Lintas Terkini</h3>
                @if($trafficUpdates->isEmpty())
                    <p class="text-gray-500">Belum ada update.</p>
                @else
                    @foreach($trafficUpdates as $update)
                        <div class="flex items-center space-x-2 mb-2">
                             <span class="w-3 h-3 rounded-full {{ $update->status == 'lancar' ? 'bg-green-500' : ($update->status == 'padat' ? 'bg-yellow-500' : 'bg-red-500') }}"></span>
                             <span class="font-medium">{{ $update->location }}:</span>
                             <span class="text-gray-600">{{ ucfirst($update->status) }}</span>
                        </div>
                    @endforeach
                @endif
                <div class="mt-4">
                     <a href="{{ route('traffic.index') }}" class="text-indigo-600 hover:underline text-sm">Lihat Detail Traffic & Gate &rarr;</a>
                </div>
            </div>

            <!-- CCTV Monitoring -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($cctvs as $cctv)
                    <div class="bg-black rounded-xl overflow-hidden shadow-lg relative group">
                        <img src="{{ $cctv['image'] }}" class="w-full h-64 object-cover opacity-80 group-hover:opacity-100 transition duration-500">
                        <div class="absolute top-4 left-4 bg-red-600 text-white text-xs px-2 py-1 rounded animate-pulse">
                            🔴 LIVE
                        </div>
                        <div class="absolute bottom-0 w-full bg-gradient-to-t from-black to-transparent p-4">
                            <h4 class="text-white font-bold text-lg">{{ $cctv['name'] }}</h4>
                            <p class="text-gray-300 text-sm">Status: {{ $cctv['status'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
