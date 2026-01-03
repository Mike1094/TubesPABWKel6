<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Telkom',
            'email' => 'admin@telkom.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Satpam
        User::create([
            'name' => 'Pak Satpam',
            'email' => 'satpam@telkom.id',
            'password' => Hash::make('password'),
            'role' => 'satpam',
        ]);

        // Civitas (Mahasiswa)
        User::create([
            'name' => 'Mahasiswa Telkom',
            'email' => 'mahasiswa@telkom.id',
            'password' => Hash::make('password'),
            'role' => 'civitas',
        ]);

        // Warga
        User::create([
            'name' => 'Warga Sekitar',
            'email' => 'warga@telkom.id',
            'password' => Hash::make('password'),
            'role' => 'warga',
        ]);
        
        // Additional dummy Civitas
        User::factory(5)->create([
            'role' => 'civitas'
        ]);

        $this->call([
            GateSeeder::class,
        ]);
    }
}
