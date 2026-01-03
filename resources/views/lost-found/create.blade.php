<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lapor Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-8 text-gray-900">
                    <form action="{{ route('lost-found.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="type" :value="__('Jenis Laporan')" />
                                <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" onchange="toggleLinkedItem(this.value)">
                                    <option value="lost" {{ (isset($type) && $type == 'lost') ? 'selected' : '' }}>Kehilangan (Lost)</option>
                                    <option value="found" {{ (isset($type) && $type == 'found') ? 'selected' : '' }}>Penemuan (Found)</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="item_name" :value="__('Nama Barang')" />
                                <x-text-input id="item_name" class="block mt-1 w-full" type="text" name="item_name" required placeholder="Contoh: Dompet Hitam" />
                            </div>
                        </div>

                        <!-- Linked Lost Item (Only for Found) -->
                        <div id="linked_item_container" class="hidden">
                            <x-input-label for="linked_lost_id" :value="__('Apakah ini barang yang dilaporkan hilang? (Opsional)')" />
                            <select id="linked_lost_id" name="linked_lost_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                                <option value="">-- Bukan / Tidak Tahu --</option>
                                @if(isset($lostItems))
                                    @foreach($lostItems as $item)
                                        <option value="{{ $item->id }}" {{ (isset($linked_lost_id) && $linked_lost_id == $item->id) ? 'selected' : '' }}>{{ $item->item_name }} ({{ $item->location }}) - {{ $item->user->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Jika Anda memilih, pelapor akan mendapat notifikasi.</p>
                        </div>

                        <script>
                            function toggleLinkedItem(type) {
                                const container = document.getElementById('linked_item_container');
                                if (type === 'found') {
                                    container.classList.remove('hidden');
                                } else {
                                    container.classList.add('hidden');
                                }
                            }

                            // Auto trigger on load if type is found
                            document.addEventListener("DOMContentLoaded", function(event) { 
                                var typeSelect = document.getElementById('type');
                                if(typeSelect.value === 'found') {
                                    toggleLinkedItem('found');
                                }
                            });
                        </script>

                        <div>
                            <x-input-label for="location" :value="__('Lokasi Terakhir / Ditemukan')" />
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" required placeholder="Gedung Tokong Nanas, Lt 1" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Detail')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required placeholder="Ciri-ciri barang, warna, isi, dll..."></textarea>
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('Foto Barang (Opsional)')" />
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="image" name="image" type="file" class="sr-only" onchange="previewImage(event)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    <p id="file-name" class="text-sm text-gray-800 mt-2 font-semibold"></p>
                                    <img id="image-preview" class="hidden mt-4 mx-auto h-40 object-cover rounded-lg border" src="#" alt="Image Preview" />
                                </div>
                            </div>
                            <script>
                                function previewImage(event) {
                                    const input = event.target;
                                    const preview = document.getElementById('image-preview');
                                    const fileName = document.getElementById('file-name');
                                    
                                    if (input.files && input.files[0]) {
                                        const reader = new FileReader();
                                        
                                        reader.onload = function(e) {
                                            preview.src = e.target.result;
                                            preview.classList.remove('hidden');
                                        }
                                        
                                        reader.readAsDataURL(input.files[0]);
                                        fileName.textContent = "Selected: " + input.files[0].name;
                                    }
                                }
                            </script>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Batal') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Kirim Laporan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
