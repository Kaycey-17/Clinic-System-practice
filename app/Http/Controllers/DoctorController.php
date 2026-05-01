<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::withCount('appointments')->latest()->paginate(15);
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('doctors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'specialization'   => 'required|string|max:100',
            'email'            => 'required|email|unique:doctors,email',
            'phone'            => 'required|string|max:20',
            'qualifications'   => 'nullable|string',
            'consultation_fee' => 'required|numeric|min:0',
            'available_days'   => 'nullable|array',
            'available_days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'       => 'nullable|date_format:H:i',
            'end_time'         => 'nullable|date_format:H:i|after:start_time',
            'is_active'        => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Doctor::create($validated);

        return redirect()->route('doctors.index')->with('success', 'Doctor added successfully.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['appointments' => function ($q) {
            $q->with('patient')->latest()->limit(10);
        }]);
        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'specialization'   => 'required|string|max:100',
            'email'            => 'required|email|unique:doctors,email,' . $doctor->id,
            'phone'            => 'required|string|max:20',
            'qualifications'   => 'nullable|string',
            'consultation_fee' => 'required|numeric|min:0',
            'available_days'   => 'nullable|array',
            'available_days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time'       => 'nullable|date_format:H:i',
            'end_time'         => 'nullable|date_format:H:i|after:start_time',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $doctor->update($validated);

        return redirect()->route('doctors.show', $doctor)->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('doctors.index')->with('success', 'Doctor removed.');
    }
}