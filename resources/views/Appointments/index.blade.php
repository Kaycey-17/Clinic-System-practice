@extends('layouts.app')
@section('title', 'Appointments')
@section('page-title', 'Appointment Desk')

@section('content')

<div class="card">
    <div class="card-header">
        <h2><i class="bi bi-clipboard2-pulse" style="color:#2563eb; margin-right:6px;"></i>All Appointments</h2>
        <div class="flex-center gap-2">
            <a href="{{ route('appointments.calendar') }}" class="btn btn-outline btn-sm">
                <i class="bi bi-calendar3"></i> Calendar
            </a>
            <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> New Appointment
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card-body" style="border-bottom:1px solid #f1f5f9; padding-bottom:14px;">
        <form method="GET" style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
            <select name="status" style="width:auto;">
                <option value="">All Statuses</option>
                @foreach(['pending','confirmed','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ $status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ $date }}" style="width:auto;">
            <button type="submit" class="btn btn-outline btn-sm">Filter</button>
            @if($status || $date)
                <a href="{{ route('appointments.index') }}" class="btn btn-ghost btn-sm">Clear</a>
            @endif
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Service</th>
                <th>Fee</th>
                <th>Status</th>
                <th>Billing</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $apt)
            <tr>
                <td>
                    <div style="font-weight:600;">{{ $apt->appointment_date->format('M d, Y') }}</div>
                    <div class="text-muted text-sm">{{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}</div>
                </td>
                <td>
                    <a href="{{ route('patients.show', $apt->patient) }}" style="font-weight:600;">
                        {{ $apt->patient->full_name }}
                    </a>
                </td>
                <td class="text-muted text-sm">{{ $apt->doctor->full_name }}</td>
                <td class="text-sm">{{ $apt->service_type }}</td>
                <td style="font-weight:600;">₱{{ number_format($apt->fee, 2) }}</td>
                <td><span class="badge badge-{{ $apt->status }}">{{ $apt->status }}</span></td>
                <td>
                    @if($apt->transaction)
                        <span class="badge badge-{{ $apt->transaction->payment_status }}">
                            {{ $apt->transaction->payment_status }}
                        </span>
                    @elseif($apt->status !== 'cancelled')
                        <a href="{{ route('billing.create', ['appointment_id' => $apt->id]) }}"
                           class="btn btn-success btn-sm">
                            <i class="bi bi-receipt"></i> Invoice
                        </a>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <div class="flex-center gap-2">
                        <a href="{{ route('appointments.show', $apt) }}" class="btn btn-outline btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('appointments.edit', $apt) }}" class="btn btn-outline btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <p>No appointments found.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($appointments->hasPages())
        <div class="pagination">
            {{ $appointments->withQueryString()->links('pagination::simple-default') }}
        </div>
    @endif
</div>

@endsection