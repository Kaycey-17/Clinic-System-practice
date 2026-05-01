<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $specializations = [
            'Cardiology',
            'Dentistry',
            'General Medicine',
            'Pediatrics',
            'Dermatology'
        ];

        foreach (range(1, 10) as $i) {
            Doctor::create([
                'first_name' => 'Doctor'.$i,
                'last_name' => 'Test',
                'specialization' => $specializations[array_rand($specializations)],
                'email' => "doctor$i@gmail.com",
                'phone' => '09'.rand(100000000, 999999999),
                'qualifications' => 'MD',
                'consultation_fee' => rand(300, 1000),
                'available_days' => json_encode(['Monday', 'Wednesday', 'Friday']),
                'start_time' => '09:00',
                'end_time' => '17:00',
                'is_active' => true,
            ]);
        }
    }
}