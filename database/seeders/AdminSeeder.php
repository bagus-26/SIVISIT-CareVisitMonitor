<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'admin@sivisit.com'
        ], [
            'name' => 'Administrator',
            'email' => 'admin@sivisit.com',
            'password' => Hash::make('Admin123456'),
            'role' => 'admin',
            'nip' => '000000',
            'phone' => '0812345678',
            'location' => 'Pusat Kesehatan',
            'email_verified_at' => now(),
        ]);

        User::firstOrCreate([
            'email' => 'petugas@sivisit.com'
        ], [
            'name' => 'Petugas Monitoring',
            'email' => 'petugas@sivisit.com',
            'password' => Hash::make('Petugas123456'),
            'role' => 'petugas',
            'nip' => '000001',
            'phone' => '0812345679',
            'location' => 'Lapangan',
            'email_verified_at' => now(),
        ]);
    }
}
