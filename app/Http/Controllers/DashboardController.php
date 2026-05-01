<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todayAppointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        $stats = [
            'total_patients'      => Patient::count(),
            'total_doctors'       => Doctor::where('is_active', true)->count(),
            'today_appointments'  => $todayAppointments->count(),
            'pending_payments'    => Transaction::whereIn('payment_status', ['unpaid', 'partial'])->count(),
            'monthly_revenue'     => Transaction::whereMonth('created_at', now()->month)
                                        ->where('payment_status', '!=', 'refunded')
                                        ->sum('amount_paid'),
        ];

        $upcomingAppointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', '>=', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();

        $activeDoctors = Doctor::where('is_active', true)
            ->withCount(['todayAppointments'])
            ->get();

        return view('dashboard.index', compact(
            'stats', 'todayAppointments', 'upcomingAppointments', 'activeDoctors'
        ));
    }
}