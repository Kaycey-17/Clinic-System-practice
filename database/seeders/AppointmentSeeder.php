<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $doctors = Doctor::all();

        foreach (range(1, 30) as $i) {
            Appointment::create([
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'appointment_date' => now()->addDays(rand(0, 10)),
                'appointment_time' => sprintf('%02d:00', rand(8, 17)),
                'service_type' => 'General Check-up',
                'status' => ['pending', 'confirmed'][rand(0,1)],
                'notes' => 'Sample note '.$i,
                'fee' => rand(300, 1000),
            ]);
        }
    }
}