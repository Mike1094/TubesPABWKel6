<?php

namespace Database\Seeders;

use App\Models\Gate;
use Illuminate\Database\Seeder;

class GateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gates = ['Gerbang 1', 'Gerbang 2', 'Gerbang 3', 'Gerbang 4'];

        foreach ($gates as $gateName) {
            Gate::firstOrCreate(
                ['name' => $gateName],
                ['is_open' => true]
            );
        }
    }
}
