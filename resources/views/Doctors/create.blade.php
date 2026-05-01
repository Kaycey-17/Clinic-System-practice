@extends('layouts.app')
@section('title', 'Add Doctor')
@section('page-title', 'Add New Doctor')

@section('content')

<form method="POST" action="{{ route('doctors.store') }}" style="max-width:700px;">
@csrf

<div class="card mb-3">
    <div class="card-header">
        <h2><i class="bi bi-person-badge" style="color:#2563eb; margin-right:6px;"></i>Doctor Information</h2>
    </div>
    <div class="card-body">

        <div class="form-row cols-2">
            <div class="form-group">
                <label>First Name <span class="required">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name') }}"
                       class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}" required>
                @error('first_name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Last Name <span class="required">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name') }}"
                       class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}" required>
                @error('last_name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Specialization <span class="required">*</span></label>
                <select name="specialization" class="{{ $errors->has('specialization') ? 'is-invalid' : '' }}" required>
                    <option value="">Select…</option>
                    @foreach([
                        'General Physician','Dentist','Cardiologist','Dermatologist',
                        'Orthopedic','Pediatrician','Ophthalmologist','Neurologist',
                        'Psychiatrist','OB-GYN','ENT Specialist','Gastroenterologist'
                    ] as $spec)
                        <option value="{{ $spec }}" {{ old('specialization') == $spec ? 'selected' : '' }}>
                            {{ $spec }}
                        </option>
                    @endforeach
                </select>
                @error('specialization') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Consultation Fee (₱) <span class="required">*</span></label>
                <input type="number" name="consultation_fee" value="{{ old('consultation_fee', 0) }}"
                       min="0" step="0.01"
                       class="{{ $errors->has('consultation_fee') ? 'is-invalid' : '' }}" required>
                @error('consultation_fee') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required>
                @error('email') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Phone <span class="required">*</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="{{ $errors->has('phone') ? 'is-invalid' : '' }}" required>
                @error('phone') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Qualifications</label>
            <input type="text" name="qualifications" value="{{ old('qualifications') }}"
                   placeholder="e.g. MD, FPCP, FPCC">
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
                <label class="form-check">
                    <input type="checkbox" name="available_days[]" value="{{ $day }}"
                        {{ is_array(old('available_days')) && in_array($day, old('available_days')) ? 'checked' : '' }}>
                    {{ $day }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="form-row cols-3">
            <div class="form-group">
                <label>Start Time</label>
                <input type="time" name="start_time" value="{{ old('start_time') }}">
            </div>
            <div class="form-group">
                <label>End Time</label>
                <input type="time" name="end_time" value="{{ old('end_time') }}">
            </div>
            <div class="form-group" style="display:flex; align-items:flex-end;">
                <label class="form-check">
                    <input type="checkbox" name="is_active" value="1" checked>
                    Active / Accepting Patients
                </label>
            </div>
        </div>

    </div>
</div>

<div class="flex-center gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Add Doctor
    </button>
    <a href="{{ route('doctors.index') }}" class="btn btn-outline">Cancel</a>
</div>

</form>

@endsection