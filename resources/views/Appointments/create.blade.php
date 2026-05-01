@extends('layouts.app')
@section('title', 'New Appointment')
@section('page-title', 'Schedule Appointment')

@section('content')

<form method="POST" action="{{ route('appointments.store') }}" style="max-width:640px;">
@csrf

<div class="card mb-3">
    <div class="card-header">
        <h2><i class="bi bi-clipboard2-plus" style="color:#2563eb; margin-right:6px;"></i>Appointment Details</h2>
    </div>
    <div class="card-body">

        <div class="form-group">
            <label>Patient <span class="required">*</span></label>
            <select name="patient_id" class="{{ $errors->has('patient_id') ? 'is-invalid' : '' }}" required>
                <option value="">Select patient…</option>
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}"
                        {{ old('patient_id', request('patient_id')) == $patient->id ? 'selected' : '' }}>
                        {{ $patient->full_name }} — {{ $patient->phone }}
                    </option>
                @endforeach
            </select>
            @error('patient_id') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Doctor <span class="required">*</span></label>
            <select name="doctor_id" id="doctorSelect"
                    class="{{ $errors->has('doctor_id') ? 'is-invalid' : '' }}" required>
                <option value="">Select doctor…</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}"
                            data-fee="{{ $doctor->consultation_fee }}"
                            {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->full_name }} — {{ $doctor->specialization }}
                        (₱{{ number_format($doctor->consultation_fee, 2) }})
                    </option>
                @endforeach
            </select>
            @error('doctor_id') <div class="field-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Date <span class="required">*</span></label>
                <input type="date" name="appointment_date"
                       value="{{ old('appointment_date', date('Y-m-d')) }}"
                       min="{{ date('Y-m-d') }}"
                       class="{{ $errors->has('appointment_date') ? 'is-invalid' : '' }}" required>
                @error('appointment_date') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Time <span class="required">*</span></label>
                <input type="time" name="appointment_time"
                       value="{{ old('appointment_time') }}"
                       class="{{ $errors->has('appointment_time') ? 'is-invalid' : '' }}" required>
                @error('appointment_time') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Service Type <span class="required">*</span></label>
                <select name="service_type" class="{{ $errors->has('service_type') ? 'is-invalid' : '' }}" required>
                    <option value="">Select service…</option>
                    @foreach($services as $svc)
                        <option value="{{ $svc }}" {{ old('service_type') == $svc ? 'selected' : '' }}>
                            {{ $svc }}
                        </option>
                    @endforeach
                </select>
                @error('service_type') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Fee (₱) <span class="required">*</span></label>
                <input type="number" name="fee" id="feeInput"
                       value="{{ old('fee', 0) }}" min="0" step="0.01"
                       class="{{ $errors->has('fee') ? 'is-invalid' : '' }}" required>
                @error('fee') <div class="field-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" rows="2"
                      placeholder="Special instructions or notes…">{{ old('notes') }}</textarea>
        </div>

    </div>
</div>

<div class="flex-center gap-2">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg"></i> Schedule Appointment
    </button>
    <a href="{{ route('appointments.index') }}" class="btn btn-outline">Cancel</a>
</div>

</form>

@push('scripts')
<script>
document.getElementById('doctorSelect').addEventListener('change', function () {
    const fee = this.options[this.selectedIndex].dataset.fee;
    if (fee) document.getElementById('feeInput').value = parseFloat(fee).toFixed(2);
});
</script>
@endpush

@endsection