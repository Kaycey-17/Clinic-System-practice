<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Appointment;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = Appointment::all();

        foreach ($appointments as $appointment) {

            $amountPaid = rand(0, $appointment->fee);
            $balance = $appointment->fee - $amountPaid;

            $status = 'unpaid';
            if ($amountPaid >= $appointment->fee) {
                $status = 'paid';
                $balance = 0;
            } elseif ($amountPaid > 0) {
                $status = 'partial';
            }

            Transaction::create([
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'total_amount' => $appointment->fee,
                'amount_paid' => $amountPaid,
                'balance' => $balance,
                'payment_status' => $status,
                'payment_method' => ['cash', 'gcash', 'card'][rand(0,2)],
                'additional_services' => null,
                'additional_amount' => 0,
                'notes' => 'Auto-generated transaction',
            ]);
        }
    }
}