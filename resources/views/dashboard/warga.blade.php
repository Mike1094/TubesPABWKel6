<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Warga') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                <div class="p-6 text-gray-900 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold">Selamat Datang, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-600">Pantau keamanan dan lalu lintas lingkungan kampus secara real-time.</p>
                    </div>
                    <span class="text-sm bg-blue-100 text-blue-800 py-1 px-3 rounded-full">
                        Status Warga
                    </span>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        Live Monitoring CCTV
                    </h3>
                    <span class="text-sm text-gray-500 animate-pulse">● Live Feed Connected</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                    @foreach($cctvs as $cctv)
                    <div class="relative bg-black rounded-lg overflow-hidden border border-gray-700 shadow-xl group">

                        <div class="absolute top-0 left-0 w-full bg-gradient-to-b from-black/80 to-transparent p-3 flex justify-between items-center z-10">
                            <div class="text-white">
                                <p class="font-bold text-sm tracking-wider">{{ strtoupper($cctv['name']) }}</p>
                                <p class="text-[10px] text-gray-400 font-mono cctv-time">{{ now()->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($cctv['status'] == 'Online')
                                    <span class="relative flex h-3 w-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                                    </span>
                                    <span class="text-[10px] font-bold text-red-500 bg-black/50 px-1 rounded">REC</span>
                                @else
                                    <span class="h-3 w-3 rounded-full bg-gray-500"></span>
                                @endif
                            </div>
                        </div>

                        <div class="aspect-video w-full relative overflow-hidden bg-gray-900">
                            @if($cctv['status'] == 'Online')
                                <img src="{{ $cctv['image'] }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition-all duration-700">
                                <div class="absolute inset-0 bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.06),rgba(0,255,0,0.02),rgba(0,0,255,0.06))] z-0 bg-[length:100%_2px,3px_100%] pointer-events-none"></div>
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-gray-500 space-y-2">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    <span class="font-mono text-xs tracking-widest">SIGNAL LOST</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-white p-6 rounded-lg shadow-sm md:col-span-1">
                    <h4 class="font-bold text-gray-800 mb-4 border-b pb-2">Status Gerbang</h4>
                    <div class="space-y-4">
                        @foreach($gates as $gate)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <div class="font-medium text-gray-700">{{ $gate->nama_gerbang }}</div>
                                <div class="text-xs text-gray-500">Kondisi: {{ ucfirst($gate->traffic_status) }}</div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $gate->status == 'open' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $gate->status == 'open' ? 'TERBUKA' : 'DITUTUP' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm md:col-span-2">
                    <h4 class="font-bold text-gray-800 mb-4 border-b pb-2">Update Lalu Lintas Terkini</h4>
                    @if($trafficUpdates->count() > 0)
                        <div class="space-y-4">
                            @foreach($trafficUpdates as $update)
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 ring-4 ring-blue-100"></div>
                                </div>
                                <div>
                                    <p class="text-gray-800 text-sm">{{ $update->description ?? 'Tidak ada deskripsi' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $update->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm italic">Belum ada info lalu lintas terkini.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- <script>
        setInterval(() => {
            const now = new Date();
            const timeString = now.toLocaleDateString('id-ID') + ' ' + now.toLocaleTimeString('id-ID');
            document.querySelectorAll('.cctv-time').forEach(el => el.innerText = timeString);
        }, 1000);
    </script> --}}
</x-app-layout>
