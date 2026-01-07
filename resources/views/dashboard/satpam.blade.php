<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach($gates as $gate)
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-bold text-xl">{{ $gate->nama_gerbang }}</h3>

        <div class="mt-4">
            <span class="text-gray-600">Status Pintu:</span>
            <span class="px-2 py-1 rounded {{ $gate->status == 'open' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                {{ strtoupper($gate->status) }}
            </span>
        </div>

        <form action="{{ route('gates.update', $gate->id) }}" method="POST" class="mt-4">
            @csrf @method('PATCH')
            <select name="status" class="border rounded p-1">
                <option value="open" {{ $gate->status == 'open' ? 'selected' : '' }}>Open</option>
                <option value="closed" {{ $gate->status == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
            <select name="traffic_status" class="border rounded p-1 ml-2">
                <option value="lancar" {{ $gate->traffic_status == 'lancar' ? 'selected' : '' }}>Lancar</option>
                <option value="macet" {{ $gate->traffic_status == 'macet' ? 'selected' : '' }}>Macet</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded ml-2">Update</button>
        </form>
    </div>
    @endforeach
</div>
