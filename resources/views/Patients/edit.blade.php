@extends('layouts.app')
@section('title', 'Edit Patient')
@section('page-title', 'Edit: ' . $patient->full_name)

@section('content')

<form method="POST" action="{{ route('patients.update', $patient) }}" style="max-width:760px;">
@csrf @method('PUT')

<div class="card mb-3">
    <div class="card-header">
        <h2><i class="bi bi-person-vcard" style="color:#2563eb; margin-right:6px;"></i>Personal Information</h2>
    </div>
    <div class="card-body">

        <div class="form-row cols-2">
            <div class="form-group">
                <label>First Name <span class="required">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name', $patient->first_name) }}" required>
                @error('first_name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Last Name <span class="required">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name', $patient->last_name) }}" required>
                @error('last_name') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row cols-3">
            <div class="form-group">
                <label>Date of Birth <span class="required">*</span></label>
                <input type="date" name="date_of_birth"
                       value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label>Gender <span class="required">*</span></label>
                <select name="gender" required>
                    @foreach(['male','female','other'] as $g)
                        <option value="{{ $g }}" {{ old('gender', $patient->gender) == $g ? 'selected' : '' }}>
                            {{ ucfirst($g) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Blood Type</label>
                <select name="blood_type">
                    <option value="">Unknown</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                        <option value="{{ $bt }}" {{ old('blood_type', $patient->blood_type) == $bt ? 'selected' : '' }}>
                            {{ $bt }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Phone <span class="required">*</span></label>
                <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $patient->email) }}">
            </div>
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" rows="2">{{ old('address', $patient->address) }}</textarea>
        </div>

    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h2><i class="bi bi-heart-pulse" style="color:#dc2626; margin-right:6px;"></i>Medical Information</h2>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>Medical History</label>
            <textarea name="medical_history" rows="3">{{ old('medical_history', $patient->medical_history) }}</textarea>
        </div>
        <div class="form-group">
            <label>Allergies</label>
            <textarea name="allergies" rows="2">{{ old('allergies', $patient->allergies) }}</textarea>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h2><i class="bi bi-telephone" style="color:#b45309; margin-right:6px;"></i>Emergency Contact</h2>
    </div>
    <div class="card-body">
        <div class="form-row cols-3">
            <div class="form-group">
                <label>Contact Name</label>
                <input type="text" name="emergency_contact_name"
                       value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="emergency_contact_phone"
                       value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}">
            </div>
            <div class="form-group">
                <label>Relation</label>
                <input type="text" name="emergency_contact_relation"
                       value="{{ old('emergency_contact_relation', $patient->emergency_contact_relation) }}">
            </div>
        </div>
    </div>
</div>

<div class="flex-center gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Save Changes
    </button>
    <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline">Cancel</a>
</div>

</form>

@endsection