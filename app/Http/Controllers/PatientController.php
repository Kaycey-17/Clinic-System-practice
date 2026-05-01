<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $patients = Patient::when($search, function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        })
        ->withCount('appointments')
        ->latest()
        ->paginate(15);

        return view('patients.index', compact('patients', 'search'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'               => 'required|string|max:100',
            'last_name'                => 'required|string|max:100',
            'date_of_birth'            => 'required|date|before:today',
            'gender'                   => 'required|in:male,female,other',
            'phone'                    => 'required|string|max:20',
            'email'                    => 'nullable|email|max:100',
            'address'                  => 'nullable|string',
            'blood_type'               => 'nullable|string|max:5',
            'medical_history'          => 'nullable|string',
            'allergies'                => 'nullable|string',
            'emergency_contact_name'   => 'nullable|string|max:100',
            'emergency_contact_phone'  => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:50',
        ]);

        Patient::create($validated);

        return redirect()->route('patients.index')->with('success', 'Patient registered successfully.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['appointments.doctor', 'transactions']);
        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name'               => 'required|string|max:100',
            'last_name'                => 'required|string|max:100',
            'date_of_birth'            => 'required|date|before:today',
            'gender'                   => 'required|in:male,female,other',
            'phone'                    => 'required|string|max:20',
            'email'                    => 'nullable|email|max:100',
            'address'                  => 'nullable|string',
            'blood_type'               => 'nullable|string|max:5',
            'medical_history'          => 'nullable|string',
            'allergies'                => 'nullable|string',
            'emergency_contact_name'   => 'nullable|string|max:100',
            'emergency_contact_phone'  => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:50',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.show', $patient)->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted.');
    }
}