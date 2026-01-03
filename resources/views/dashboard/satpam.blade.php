<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Satpam Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-bold mb-6">Pusat Kontrol Satpam</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Control Gate -->
                        <div class="bg-gray-50 p-4 rounded-lg border">
                            <h4 class="font-bold text-lg mb-4">Status Gerbang</h4>
                            @foreach($gates as $gate)
                                <div class="bg-white p-3 rounded shadow-sm mb-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-3 
                                                {{ $gate->status == 'lancar' ? 'bg-green-500' : ($gate->status == 'macet' ? 'bg-red-500' : ($gate->status == 'tutup' ? 'bg-gray-800' : 'bg-yellow-500')) }}">
                                            </div>
                                            <span class="font-medium">{{ $gate->name }}</span>
                                        </div>
                                        <span class="text-xs font-bold uppercase
                                            {{ $gate->status == 'lancar' ? 'text-green-600' : ($gate->status == 'macet' ? 'text-red-600' : ($gate->status == 'tutup' ? 'text-gray-800' : 'text-yellow-600')) }}">
                                            {{ $gate->status }}
                                        </span>
                                    </div>
                                    
                                    <form action="{{ route('gates.update', $gate) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="flex-1 text-xs border-gray-300 rounded shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="lancar" {{ $gate->status == 'lancar' ? 'selected' : '' }}>Lancar</option>
                                            <option value="padat" {{ $gate->status == 'padat' ? 'selected' : '' }}>Padat</option>
                                            <option value="macet" {{ $gate->status == 'macet' ? 'selected' : '' }}>Macet</option>
                                            <option value="tutup" {{ $gate->status == 'tutup' ? 'selected' : '' }}>Ditutup</option>
                                        </select>
                                        <button type="submit" class="px-3 py-1.5 text-xs text-white bg-indigo-600 hover:bg-indigo-700 rounded transition">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <!-- Update Traffic -->
                        <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                            <h4 class="font-bold text-lg mb-4 text-indigo-900">Update Info Lalu Lintas</h4>
                            <form action="{{ route('traffic.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                                    <select id="location_select" class="w-full rounded-md border-gray-300 text-sm mb-2" onchange="toggleLocationInput(this)">
                                        <option value="Gerbang 1">Gerbang 1</option>
                                        <option value="Gerbang 2">Gerbang 2</option>
                                        <option value="Gerbang 3">Gerbang 3</option>
                                        <option value="Gerbang 4">Gerbang 4</option>
                                        <option value="other">Lainnya (Input Manual)</option>
                                    </select>
                                    <input type="text" id="location_input" class="hidden w-full rounded-md border-gray-300 text-sm" placeholder="Masukkan lokasi manual...">
                                    <!-- Hidden input to store actual value sent to server -->
                                    <input type="hidden" id="final_location" name="location" value="Gerbang 1">
                                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                </div>

                                <script>
                                    function toggleLocationInput(select) {
                                        const input = document.getElementById('location_input');
                                        const finalInput = document.getElementById('final_location');
                                        
                                        if (select.value === 'other') {
                                            input.classList.remove('hidden');
                                            input.required = true;
                                            input.value = '';
                                            finalInput.value = '';
                                        } else {
                                            input.classList.add('hidden');
                                            input.required = false;
                                            finalInput.value = select.value;
                                        }
                                    }

                                    // Listen to manual input changes
                                    document.getElementById('location_input').addEventListener('input', function(e) {
                                        document.getElementById('final_location').value = e.target.value;
                                    });
                                </script>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" class="w-full rounded-md border-gray-300 text-sm">
                                        <option value="lancar">Lancar</option>
                                        <option value="padat">Padat</option>
                                        <option value="macet">Macet</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                    <textarea name="description" class="w-full rounded-md border-gray-300 text-sm" rows="2"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Foto (Opsional)</label>
                                    <input type="file" name="image" class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-xs file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700
                                      hover:file:bg-indigo-100
                                    "/>
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded text-sm font-bold">
                                    Kirim Update
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Recent Updates -->
                    <div class="mt-8">
                        <h4 class="font-bold text-lg mb-4">Riwayat Update Traffic</h4>
                        <div class="space-y-3">
                        @foreach($trafficUpdates as $update)
                            <div class="border-l-4 border-indigo-400 pl-3 py-1">
                                <div class="text-sm font-bold">{{ $update->location }} - <span class="uppercase">{{ $update->status }}</span></div>
                                <div class="text-xs text-gray-500">{{ $update->created_at->diffForHumans() }} by {{ $update->user->name }}</div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
