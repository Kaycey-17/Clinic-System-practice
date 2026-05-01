@extends('layouts.app')
@section('title', 'Create Invoice')
@section('page-title', 'Create Invoice')

@section('content')

<div style="max-width:620px;">

{{-- Appointment summary --}}
<div class="card mb-3">
    <div class="card-header">
        <h2><i class="bi bi-clipboard2-check" style="color:#2563eb; margin-right:6px;"></i>Appointment Summary</h2>
    </div>
    <div class="list-group">
        <div class="list-group-item">
            <span class="text-muted text-sm">Patient</span>
            <span style="font-weight:600;">{{ $appointment->patient->full_name }}</span>
        </div>
        <div class="list-group-item">
            <span class="text-muted text-sm">Doctor</span>
            <span style="font-weight:600;">{{ $appointment->doctor->full_name }}</span>
        </div>
        <div class="list-group-item">
            <span class="text-muted text-sm">Service</span>
            <span style="font-weight:600;">{{ $appointment->service_type }}</span>
        </div>
        <div class="list-group-item">
            <span class="text-muted text-sm">Date</span>
            <span style="font-weight:600;">{{ $appointment->appointment_date->format('F j, Y') }}</span>
        </div>
        <div class="list-group-item">
            <span class="text-muted text-sm">Consultation Fee</span>
            <span style="font-weight:700; color:#2563eb; font-size:16px;">₱{{ number_format($appointment->fee, 2) }}</span>
        </div>
    </div>
</div>

{{-- Invoice form --}}
<form method="POST" action="{{ route('billing.store') }}">
@csrf
<input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

<div class="card mb-3">
    <div class="card-header">
        <h2><i class="bi bi-receipt" style="color:#16a34a; margin-right:6px;"></i>Invoice Details</h2>
    </div>
    <div class="card-body">

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Consultation Fee (₱) <span class="required">*</span></label>
                <input type="number" name="total_amount" id="baseAmount"
                       value="{{ old('total_amount', $appointment->fee) }}"
                       min="0" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Additional Charges (₱)</label>
                <input type="number" name="additional_amount" id="addAmount"
                       value="{{ old('additional_amount', 0) }}" min="0" step="0.01">
            </div>
        </div>

        <div class="form-group">
            <label>Additional Services</label>
            <input type="text" name="additional_services"
                   value="{{ old('additional_services') }}"
                   placeholder="e.g. Lab test, X-Ray, Medicines">
        </div>

        {{-- Grand total display --}}
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:12px 14px;
                    display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
            <span style="font-weight:600; font-size:13px;">Grand Total</span>
            <span id="grandTotal" style="font-size:20px; font-weight:700; color:#2563eb;">
                ₱{{ number_format($appointment->fee, 2) }}
            </span>
        </div>

        <div class="form-row cols-2">
            <div class="form-group">
                <label>Amount Paid (₱) <span class="required">*</span></label>
                <input type="number" name="amount_paid"
                       value="{{ old('amount_paid', 0) }}"
                       min="0" step="0.01"
                       class="{{ $errors->has('amount_paid') ? 'is-invalid' : '' }}" required>
                @error('amount_paid') <div class="field-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method">
                    <option value="">Select method</option>
                    <option value="cash"          {{ old('payment_method') == 'cash'          ? 'selected' : '' }}>Cash</option>
                    <option value="card"          {{ old('payment_method') == 'card'          ? 'selected' : '' }}>Card</option>
                    <option value="gcash"         {{ old('payment_method') == 'gcash'         ? 'selected' : '' }}>GCash</option>
                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" rows="2">{{ old('notes') }}</textarea>
        </div>

    </div>
</div>

<div class="flex-center gap-2">
    <button type="submit" class="btn btn-success">
        <i class="bi bi-check-lg"></i> Generate Invoice
    </button>
    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline">Cancel</a>
</div>

</form>
</div>

@push('scripts')
<script>
function updateTotal() {
    var base = parseFloat(document.getElementById('baseAmount').value) || 0;
    var add  = parseFloat(document.getElementById('addAmount').value)  || 0;
    var total = base + add;
    document.getElementById('grandTotal').textContent =
        '₱' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}
document.getElementById('baseAmount').addEventListener('input', updateTotal);
document.getElementById('addAmount').addEventListener('input', updateTotal);
</script>
@endpush

@endsection