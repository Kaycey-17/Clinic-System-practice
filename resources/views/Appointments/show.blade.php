@extends('layouts.app')
@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('content')

<div class="grid-2" style="grid-template-columns: 1fr 1.5fr;">

    {{-- Left: details + status --}}
    <div style="display:flex; flex-direction:column; gap:14px;">

        <div class="card">
            <div class="card-header">
                <h2><i class="bi bi-info-circle" style="color:#2563eb; margin-right:6px;"></i>Info</h2>
                <span class="badge badge-{{ $appointment->status }}">{{ $appointment->status }}</span>
            </div>

            <div class="list-group">
                <div class="list-group-item" style="flex-direction:column; align-items:flex-start;">
                    <span class="text-muted text-sm">Date & Time</span>
                    <span style="font-weight:600;">
                        {{ $appointment->appointment_date->format('F j, Y') }}
                        at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                    </span>
                </div>
                <div class="list-group-item" style="flex-direction:column; align-items:flex-start;">
                    <span class="text-muted text-sm">Patient</span>
                    <a href="{{ route('patients.show', $appointment->patient) }}" style="font-weight:600;">
                        {{ $appointment->patient->full_name }}
                    </a>
                </div>
                <div class="list-group-item" style="flex-direction:column; align-items:flex-start;">
                    <span class="text-muted text-sm">Doctor</span>
                    <a href="{{ route('doctors.show', $appointment->doctor) }}" style="font-weight:600;">
                        {{ $appointment->doctor->full_name }}
                    </a>
                    <span class="text-muted text-sm">{{ $appointment->doctor->specialization }}</span>
                </div>
                <div class="list-group-item" style="flex-direction:column; align-items:flex-start;">
                    <span class="text-muted text-sm">Service</span>
                    <span style="font-weight:600;">{{ $appointment->service_type }}</span>
                </div>
                <div class="list-group-item" style="flex-direction:column; align-items:flex-start;">
                    <span class="text-muted text-sm">Consultation Fee</span>
                    <span style="font-size:18px; font-weight:700; color:#2563eb;">
                        ₱{{ number_format($appointment->fee, 2) }}
                    </span>
                </div>
                @if($appointment->notes)
                <div class="list-group-item" style="flex-direction:column; align-items:flex-start;">
                    <span class="text-muted text-sm">Notes</span>
                    <span class="text-sm">{{ $appointment->notes }}</span>
                </div>
                @endif
            </div>

            <div class="card-footer" style="display:flex; gap:8px; flex-wrap:wrap;">
                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-outline btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                @if(!$appointment->transaction && $appointment->status !== 'cancelled')
                    <a href="{{ route('billing.create', ['appointment_id' => $appointment->id]) }}"
                       class="btn btn-success btn-sm">
                        <i class="bi bi-receipt"></i> Create Invoice
                    </a>
                @endif
            </div>
        </div>

        {{-- Quick status update --}}
        @if(!in_array($appointment->status, ['completed','cancelled']))
        <div class="card">
            <div class="card-header">
                <h2 style="font-size:13px;"><i class="bi bi-arrow-repeat" style="margin-right:6px;"></i>Update Status</h2>
            </div>
            <div class="card-body" style="display:flex; gap:8px; flex-wrap:wrap;">
                @foreach(['pending','confirmed','completed','cancelled'] as $s)
                    @if($appointment->status !== $s)
                    <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="{{ $s }}">
                        <button class="btn btn-outline btn-sm" style="text-transform:capitalize;">{{ $s }}</button>
                    </form>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Right: billing --}}
    <div>
        @if($appointment->transaction)
        <div class="card">
            <div class="card-header">
                <h2><i class="bi bi-receipt" style="color:#16a34a; margin-right:6px;"></i>Invoice</h2>
                <a href="{{ route('billing.show', $appointment->transaction) }}" class="btn btn-outline btn-sm">
                    View Full
                </a>
            </div>
            <div class="list-group">
                <div class="list-group-item">
                    <span class="text-muted text-sm">Total Amount</span>
                    <span style="font-weight:700;">₱{{ number_format($appointment->transaction->total_amount, 2) }}</span>
                </div>
                <div class="list-group-item">
                    <span class="text-muted text-sm">Amount Paid</span>
                    <span style="font-weight:700; color:#16a34a;">₱{{ number_format($appointment->transaction->amount_paid, 2) }}</span>
                </div>
                <div class="list-group-item">
                    <span class="text-muted text-sm">Balance</span>
                    @php $balanceColor = $appointment->transaction->balance > 0 ? '#dc2626' : '#16a34a'; @endphp
                    <span style="font-weight:700; color: '{{ $balanceColor }}';">
                        ₱{{ number_format($appointment->transaction->balance, 2) }}
                    </span>
                </div>
                <div class="list-group-item">
                    <span class="text-muted text-sm">Payment Status</span>
                    <span class="badge badge-{{ $appointment->transaction->payment_status }}">
                        {{ $appointment->transaction->payment_status }}
                    </span>
                </div>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body">
                <div class="empty-state">
                    <i class="bi bi-receipt"></i>
                    <p>No invoice created yet.</p>
                    @if($appointment->status !== 'cancelled')
                        <a href="{{ route('billing.create', ['appointment_id' => $appointment->id]) }}"
                           class="btn btn-success btn-sm" style="margin-top:12px;">
                            <i class="bi bi-plus"></i> Create Invoice
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

</div>

@endsection