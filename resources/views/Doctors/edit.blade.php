@extends('layouts.app')
@section('title', 'Edit Doctor')
@section('page-title', 'Edit: ' . $doctor->full_name)

@section('content')

<form method="POST" action="{{ route('doctors.update', $doctor) }}" style="max-width:700px;">
@csrf @method('PUT')

<div class="card mb-3">
    <div class="card-header">
        <h2><i class="bi bi-person-badge" style="color:#2563eb; margin-right:6px;"></i>Doctor Information</h2>
    </div>
    <div class="card-body">

        <div class="form-row cols-2">
            <div class="form-group">
                <label>First Name <span class="required">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name', $doctor->first_name) }}" required>
                @error('first_name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Last Name <span class="required">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name', $doctor->last_name) }}" required>
                @error('last_name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Specialization <span class="required">*</span></label>
                <select name="specialization" required>
                    @foreach([
                        'General Physician','Dentist','Cardiologist','Dermatologist',
                        'Orthopedic','Pediatrician','Ophthalmologist','Neurologist',
                        'Psychiatrist','OB-GYN','ENT Specialist','Gastroenterologist'
                    ] as $spec)
                        <option value="{{ $spec }}" {{ old('specialization', $doctor->specialization) == $spec ? 'selected' : '' }}>
                            {{ $spec }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Consultation Fee (₱) <span class="required">*</span></label>
                <input type="number" name="consultation_fee"
                       value="{{ old('consultation_fee', $doctor->consultation_fee) }}"
                       min="0" step="0.01" required>
            </div>
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" value="{{ old('email', $doctor->email) }}" required>
                @error('email') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Phone <span class="required">*</span></label>
                <input type="text" name="phone" value="{{ old('phone', $doctor->phone) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label>Qualifications</label>
            <input type="text" name="qualifications" value="{{ old('qualifications', $doctor->qualifications) }}">
        </div>

    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h2><i class="bi bi-calendar-week" style="color:#16a34a; margin-right:6px;"></i>Schedule</h2>
    </div>
    <div class="card-body">

        <div class="form-group">
            <label>Available Days</label>
            <div style="display:flex; flex-wrap:wrap; gap:12px; margin-top:4px;">
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                @php $days = old('available_days', $doctor->available_days ?? []); @endphp
                <label class="form-check">
                    <input type="checkbox" name="available_days[]" value="{{ $day }}"
                        {{ is_array($days) && in_array($day, $days) ? 'checked' : '' }}>
                    {{ $day }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="form-row cols-3">
            <div class="form-group">
                <label>Start Time</label>
                <input type="time" name="start_time" value="{{ old('start_time', $doctor->start_time) }}">
            </div>
            <div class="form-group">
                <label>End Time</label>
                <input type="time" name="end_time" value="{{ old('end_time', $doctor->end_time) }}">
            </div>
            <div class="form-group" style="display:flex; align-items:flex-end;">
                <label class="form-check">
                    <input type="checkbox" name="is_active" value="1" {{ $doctor->is_active ? 'checked' : '' }}>
                    Active / Accepting Patients
                </label>
            </div>
        </div>

    </div>
</div>

<div class="flex-center gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Save Changes
    </button>
    <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-outline">Cancel</a>
</div>

</form>

@endsection