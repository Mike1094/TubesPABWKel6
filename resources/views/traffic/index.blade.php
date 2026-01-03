<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pantauan Lalu Lintas & Gerbang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Status Gerbang -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Status Gerbang Kampus</h3>
                    <div class="space-y-4">
                        @forelse($gates as $gate)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div id="gate-indicator-{{ $gate->id }}" class="w-3 h-3 rounded-full mr-3 
                                        {{ $gate->status == 'lancar' ? 'bg-green-500' : ($gate->status == 'macet' ? 'bg-red-500' : ($gate->status == 'tutup' ? 'bg-gray-800' : 'bg-yellow-500')) }}">
                                    </div>
                                    <span class="font-medium text-gray-700">{{ $gate->name }}</span>
                                </div>
                                <div class="flex items-center">
                                    @if(Auth::user()->role == 'satpam' || Auth::user()->role == 'admin')
                                        <select onchange="updateGateStatus({{ $gate->id }}, this.value)" 
                                            class="text-xs font-bold rounded-full uppercase border-none focus:ring-0 cursor-pointer
                                            {{ $gate->status == 'lancar' ? 'bg-green-100 text-green-800' : ($gate->status == 'macet' ? 'bg-red-100 text-red-800' : ($gate->status == 'tutup' ? 'bg-gray-200 text-gray-800' : 'bg-yellow-100 text-yellow-800')) }}"
                                            id="gate-select-{{ $gate->id }}">
                                            <option value="lancar" {{ $gate->status == 'lancar' ? 'selected' : '' }}>Lancar</option>
                                            <option value="padat" {{ $gate->status == 'padat' ? 'selected' : '' }}>Padat</option>
                                            <option value="macet" {{ $gate->status == 'macet' ? 'selected' : '' }}>Macet</option>
                                            <option value="tutup" {{ $gate->status == 'tutup' ? 'selected' : '' }}>Tutup</option>
                                        </select>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold rounded-full uppercase
                                            {{ $gate->status == 'lancar' ? 'bg-green-100 text-green-800' : ($gate->status == 'macet' ? 'bg-red-100 text-red-800' : ($gate->status == 'tutup' ? 'bg-gray-200 text-gray-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ $gate->status }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">Data gerbang belum tersedia.</p>
                        @endforelse
                    </div>
                </div>

                <script>
                    function updateGateStatus(gateId, newStatus) {
                        const selectElement = document.getElementById(`gate-select-${gateId}`);
                        const indicatorElement = document.getElementById(`gate-indicator-${gateId}`);
                        
                        // Optimistic UI update (optional, but good for responsiveness)
                        // updateVisuals(selectElement, indicatorElement, newStatus); 

                        fetch(`/gates/${gateId}`, {
                            method: 'POST', // Method spoofing specifically for Laravel
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                _method: 'PATCH',
                                status: newStatus
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update visuals based on server response/logic
                                updateVisuals(selectElement, indicatorElement, newStatus);
                                // Optional: Show toast
                                alert('Status gate updated: ' + newStatus); 
                            } else {
                                alert('Failed to update status.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred.');
                        });
                    }

                    function updateVisuals(selectEl, indicatorEl, status) {
                        // Reset classes
                        selectEl.className = selectEl.className.replace(/bg-\w+-100/g, '').replace(/text-\w+-800/g, '');
                        indicatorEl.className = indicatorEl.className.replace(/bg-\w+-500|bg-gray-800/g, '');

                        // Map status to colors
                        const colors = {
                            'lancar': { text: 'bg-green-100 text-green-800', dot: 'bg-green-500' },
                            'padat': { text: 'bg-yellow-100 text-yellow-800', dot: 'bg-yellow-500' },
                            'macet': { text: 'bg-red-100 text-red-800', dot: 'bg-red-500' },
                            'tutup': { text: 'bg-gray-200 text-gray-800', dot: 'bg-gray-800' }
                        };

                        const config = colors[status];
                        if (config) {
                             selectEl.classList.add(...config.text.split(' '));
                             indicatorEl.classList.add(...config.dot.split(' '));
                        }
                        // Re-add base classes if wiped (simplified above regex replacement might need care, 
                        // but since I'm appending, let's just make sure we strip correctly first.
                        // Actually better approach: remove specific known color classes)
                    }
                </script>

                <!-- Update Traffic Form (Satpam Only) -->
                @if(Auth::user()->role == 'satpam' || Auth::user()->role == 'admin')
                    <div class="bg-indigo-50 overflow-hidden shadow-xl sm:rounded-2xl p-6 border border-indigo-100">
                        <h3 class="text-lg font-bold text-indigo-900 mb-4">Update Info Lalu Lintas</h3>
                        <form action="{{ route('traffic.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-indigo-700">Lokasi</label>
                                <input type="text" name="location" class="mt-1 block w-full rounded-md border-indigo-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Depan Gd. Bangkit">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-indigo-700">Status</label>
                                <select name="status" class="mt-1 block w-full rounded-md border-indigo-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="lancar">🟢 Lancar</option>
                                    <option value="padat">🟡 Padat</option>
                                    <option value="macet">🔴 Macet</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-indigo-700">Keterangan Tambahan</label>
                                <textarea name="description" rows="2" class="mt-1 block w-full rounded-md border-indigo-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-md">
                                Update Status
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Timeline Traffic Updates -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Live Traffic Updates</h3>
                <div class="relative border-l-2 border-gray-200 ml-3 space-y-8">
                    @forelse($trafficUpdates as $update)
                        <div class="ml-6 relative">
                            <span class="absolute -left-10 flex h-8 w-8 items-center justify-center rounded-full ring-4 ring-white
                                {{ $update->status == 'lancar' ? 'bg-green-500' : ($update->status == 'padat' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </span>
                            <div class="bg-gray-50 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                <div class="flex justify-between items-center mb-1">
                                    <h4 class="text-md font-bold text-gray-900">{{ $update->location }}</h4>
                                    <span class="text-xs text-gray-500">{{ $update->created_at->format('H:i') }} WIB</span>
                                </div>
                                <div class="mb-2">
                                     <span class="px-2 py-0.5 text-xs font-bold rounded uppercase
                                        {{ $update->status == 'lancar' ? 'bg-green-100 text-green-800' : ($update->status == 'padat' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $update->status }}
                                    </span>
                                </div>
                                <p class="text-gray-600 text-sm mb-2">{{ $update->description ?? 'Tidak ada keterangan tambahan.' }}</p>
                                
                                @if($update->image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($update->image) }}" class="rounded-lg h-32 object-cover w-full md:w-64 border" alt="Traffic Photo">
                                    </div>
                                @endif

                                <p class="text-xs text-gray-400 mt-2">Update oleh: {{ $update->user->name }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="ml-6">
                            <p class="text-gray-500">Belum ada update lalu lintas terkini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
