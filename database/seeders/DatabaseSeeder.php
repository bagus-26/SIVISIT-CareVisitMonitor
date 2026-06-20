<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Monitoring;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admincarevisit@2026.dev',
            'password' => Hash::make('password'),
            'role' => 'Koordinator Layanan',
            'nip' => '199208152019031002',
            'phone' => '081234567890',
            'location' => 'Puskesmas Dinoyo, Malang',
        ]);

        $testUser = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'Perawat Home Care',
            'nip' => '199504102021022003',
            'phone' => '082345678901',
            'location' => 'Puskesmas Dinoyo, Malang',
        ]);

        $p1 = Patient::create([
            'patient_id' => 'P001',
            'patient_name' => 'John Doe',
            'nik_dummy' => '1234567890123456',
            'datebirth' => '1990-01-01',
            'address' => '123 Main Street, Malang',
            'family_phone' => '08776543210',
            'gender' => 'Male',
            'patient_category' => 'Dewasa',
        ]);

        $p2 = Patient::create([
            'patient_id' => 'P002',
            'patient_name' => 'Jane Smith',
            'nik_dummy' => '6543210987654321',
            'datebirth' => '1985-05-15',
            'address' => '456 Elm Street, Malang',
            'family_phone' => '08123456789',
            'gender' => 'Female',
            'patient_category' => 'Dewasa',
        ]);

        $p3 = Patient::create([
            'patient_id' => 'P003',
            'patient_name' => 'Bpk. Slamet',
            'nik_dummy' => '5123456789012345',
            'datebirth' => '1961-04-20',
            'address' => 'Jl. Merdeka No. 10, Malang',
            'family_phone' => '082234567890',
            'gender' => 'Male',
            'patient_category' => 'Lansia',
        ]);

        $p4 = Patient::create([
            'patient_id' => 'P004',
            'patient_name' => 'Ibu Aminah',
            'nik_dummy' => '3512345678901234',
            'datebirth' => '1968-11-12',
            'address' => 'Perumahan Pakis, Gang 3, Malang',
            'family_phone' => '085334567891',
            'gender' => 'Female',
            'patient_category' => 'Lansia',
        ]);

        // Add some monitoring records for today
        Monitoring::create([
            'patient_id' => 'P003',
            'user_id' => $testUser->id,
            'monitoring_date' => date('Y-m-d'),
            'monitoring_time' => '08:00:00',
            'blood_pressure' => '130/80',
            'heart_rate' => 82,
            'respiratory_rate' => 18,
            'body_temperature' => '36.5',
            'oxygen_saturation' => 97,
            'symptoms' => 'Hipertensi',
            'notes' => 'Kondisi stabil, disarankan tetap istirahat cukup.',
            'status' => 'Stable',
        ]);

        Monitoring::create([
            'patient_id' => 'P004',
            'user_id' => $testUser->id,
            'monitoring_date' => date('Y-m-d'),
            'monitoring_time' => '10:30:00',
            'blood_pressure' => '140/90',
            'heart_rate' => 88,
            'respiratory_rate' => 20,
            'body_temperature' => '37.0',
            'oxygen_saturation' => 96,
            'symptoms' => 'Diabetes Melitus',
            'notes' => 'Gula darah acak normal, perlu pemantauan diet.',
            'status' => 'Stable',
        ]);
    }
}

