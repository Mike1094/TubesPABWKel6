<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Barang Hilang & Ditemukan') }}
            </h2>
            <a href="{{ route('lost-found.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-full shadow-lg transition transform hover:scale-105">
                + Lapor Barang
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($items as $item)
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl hover:shadow-2xl transition duration-300 border border-gray-100 group">
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @if($item->image)
                                <img src="{{ asset('storage/'.$item->image) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-0 right-0 mt-2 mr-2">
                                @if($item->type == 'lost')
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full uppercase font-bold tracking-wide shadow-sm">Hilang</span>
                                @else
                                    <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full uppercase font-bold tracking-wide shadow-sm">Ditemukan</span>
                                @endif
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <h3 class="font-bold text-lg text-gray-800 mb-2 truncate">{{ $item->item_name }}</h3>
                                @if($item->status == 'resolved')
                                    <span class="text-green-600 text-xs font-semibold bg-green-100 px-2 py-1 rounded-full">Selesai</span>
                                @elseif($item->status == 'pending')
                                    <span class="text-yellow-600 text-xs font-semibold bg-yellow-100 px-2 py-1 rounded-full">Menunggu Verifikasi</span>
                                @elseif($item->status == 'claimed')
                                    <span class="text-blue-600 text-xs font-semibold bg-blue-100 px-2 py-1 rounded-full">Diklaim (Ditemukan)</span>
                                @endif
                            </div>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $item->description }}</p>
                            
                            <div class="flex items-center text-gray-500 text-xs mb-4">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $item->location }}
                            </div>

                            <div class="flex justify-between items-center border-t pt-4">
                                <div class="text-xs text-gray-400">
                                    {{ $item->created_at->diffForHumans() }}
                                </div>
                                <!-- Only owner or admin/satpam could theoretically resolve -->
                                <!-- Only owner or admin/satpam could theoretically resolve -->
                                <div class="flex space-x-2">
                                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'satpam' || Auth::id() == $item->user_id)
                                        @if($item->status == 'open')
                                            <form action="{{ route('lost-found.update', $item) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold">Tandai Selesai</button>
                                            </form>
                                        @endif
                                    @endif

                                    <!-- Admin/Satpam Approval for Pending Items -->
                                    @if($item->status == 'pending' && (Auth::user()->role == 'admin' || Auth::user()->role == 'satpam'))
                                        <form action="{{ route('lost-found.update-status', $item) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="open">
                                            <button type="submit" class="text-green-600 hover:text-green-900 text-xs font-semibold">Setujui/Verifikasi</button>
                                        </form>
                                    @endif

                                    <!-- Report Found Button for OPEN LOST items (for any user) -->
                                    @if($item->type == 'lost' && $item->status == 'open')
                                        <a href="{{ route('lost-found.create', ['type' => 'found', 'linked_lost_id' => $item->id]) }}" class="text-blue-600 hover:text-blue-900 text-xs font-semibold">Saya Menemukan Ini</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($items->isEmpty())
                <div class="text-center py-20">
                    <div class="text-gray-400 mb-4">
                        <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 002 2V6a2 2 0 00-2-2h-2V4a1 1 0 00-1-1H9a1 1 0 00-1 1v1H5a2 2 0 00-2 2v5a2 2 0 002 2v2a2 2 0 002 2h2v-2"></path></svg>
                    </div>
                    <p class="text-gray-500 text-lg">Belum ada laporan barang hilang/ditemukan.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
