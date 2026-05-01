<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Patient::create([
                'first_name' => 'Patient'.$i,
                'last_name' => 'Test',
                'date_of_birth' => now()->subYears(rand(18, 60)),
                'gender' => ['male', 'female'][rand(0, 1)],
                'phone' => '09'.rand(100000000, 999999999),
                'email' => "patient$i@gmail.com",
                'address' => 'Sample Address '.$i,
                'blood_type' => ['A+', 'B+', 'O+', 'AB+'][rand(0,3)],
                'medical_history' => 'None',
                'allergies' => 'None',
                'emergency_contact_name' => 'Contact '.$i,
                'emergency_contact_phone' => '09'.rand(100000000, 999999999),
                'emergency_contact_relation' => 'Parent',
            ]);
        }
    }
}