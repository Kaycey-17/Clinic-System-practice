@extends('layouts.app')
@section('title', 'Edit Appointment')
@section('page-title', 'Edit Appointment')

@section('content')

<form method="POST" action="{{ route('appointments.update', $appointment) }}" style="max-width:640px;">
@csrf @method('PUT')

<div class="card mb-3">
    <div class="card-header">
        <h2><i class="bi bi-pencil-square" style="color:#2563eb; margin-right:6px;"></i>Edit Appointment</h2>
    </div>
    <div class="card-body">

        <div class="form-group">
            <label>Patient <span class="required">*</span></label>
            <select name="patient_id" required>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}"
                        {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                        {{ $patient->full_name }} — {{ $patient->phone }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Doctor <span class="required">*</span></label>
            <select name="doctor_id" required>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}"
                        {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->full_name }} — {{ $doctor->specialization }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Date <span class="required">*</span></label>
                <input type="date" name="appointment_date"
                       value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" required>
                @error('appointment_date') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Time <span class="required">*</span></label>
                <input type="time" name="appointment_time"
                       value="{{ old('appointment_time', $appointment->appointment_time) }}" required>
                @error('appointment_time') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Service Type <span class="required">*</span></label>
                <select name="service_type" required>
                    @foreach($services as $svc)
                        <option value="{{ $svc }}"
                            {{ old('service_type', $appointment->service_type) == $svc ? 'selected' : '' }}>
                            {{ $svc }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Fee (₱)</label>
                <input type="number" name="fee"
                       value="{{ old('fee', $appointment->fee) }}" min="0" step="0.01" required>
            </div>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                @foreach(['pending','confirmed','completed','cancelled'] as $s)
                    <option value="{{ $s }}"
                        {{ old('status', $appointment->status) == $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" rows="2">{{ old('notes', $appointment->notes) }}</textarea>
        </div>

    </div>
</div>

<div class="flex-center gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Save Changes
    </button>
    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline">Cancel</a>
</div>

</form>

@endsection