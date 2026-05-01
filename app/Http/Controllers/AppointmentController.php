<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $date   = $request->get('date');

        $appointments = Appointment::with(['patient', 'doctor'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($date, fn($q) => $q->whereDate('appointment_date', $date))
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time')
            ->paginate(15);

        return view('appointments.index', compact('appointments', 'status', 'date'));
    }

    public function create()
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('is_active', true)->orderBy('first_name')->get();
        $services = $this->serviceTypes();

        return view('appointments.create', compact('patients', 'doctors', 'services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'service_type'     => 'required|string|max:100',
            'notes'            => 'nullable|string',
            'fee'              => 'required|numeric|min:0',
        ]);

        $conflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'This time slot is already booked for the selected doctor.'])->withInput();
        }

        Appointment::create($validated);

        return redirect()->route('appointments.index')->with('success', 'Appointment scheduled successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'transaction']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::orderBy('first_name')->get();
        $doctors  = Doctor::where('is_active', true)->orderBy('first_name')->get();
        $services = $this->serviceTypes();

        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'services'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'service_type'     => 'required|string|max:100',
            'status'           => 'required|in:pending,confirmed,completed,cancelled',
            'notes'            => 'nullable|string',
            'fee'              => 'required|numeric|min:0',
        ]);

        $conflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->whereNotIn('status', ['cancelled'])
            ->where('id', '!=', $appointment->id)
            ->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'This time slot is already booked for the selected doctor.'])->withInput();
        }

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)->with('success', 'Appointment updated.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,completed,cancelled']);
        $appointment->update(['status' => $request->status]);

        return back()->with('success', 'Appointment status updated.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);
        return redirect()->route('appointments.index')->with('success', 'Appointment cancelled.');
    }

    public function calendar()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', '>=', now()->startOfMonth())
            ->whereDate('appointment_date', '<=', now()->endOfMonth())
            ->get();

        return view('appointments.calendar', compact('appointments'));
    }

    private function serviceTypes(): array
    {
        return [
            'General Check-up',
            'Specialist Consultation',
            'Dental Check-up',
            'Eye Examination',
            'Blood Test',
            'X-Ray',
            'ECG',
            'Vaccination',
            'Follow-up Consultation',
            'Emergency Consultation',
        ];
    }
}