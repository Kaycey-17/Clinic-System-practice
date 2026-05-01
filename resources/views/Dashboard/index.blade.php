@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')


<div class="grid-4">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
        <div>
            <div class="stat-label">Total Patients</div>
            <div class="stat-value">{{ number_format($stats['total_patients']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-person-badge-fill"></i></div>
        <div>
            <div class="stat-label">Active Doctors</div>
            <div class="stat-value">{{ number_format($stats['total_doctors']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="bi bi-calendar-check-fill"></i></div>
        <div>
            <div class="stat-label">Today's Appointments</div>
            <div class="stat-value">{{ number_format($stats['today_appointments']) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="bi bi-cash-coin"></i></div>
        <div>
            <div class="stat-label">Monthly Revenue</div>
            <div class="stat-value">₱{{ number_format($stats['monthly_revenue'], 0) }}</div>
        </div>
    </div>
</div>

{{-- Main content --}}
<div class="grid-3-1">

    {{-- Today's appointments table --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="bi bi-calendar-day" style="color:#2563eb; margin-right:6px;"></i>Today's Appointments</h2>
            <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> New
            </a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Service</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($todayAppointments as $apt)
                <tr>
                    <td style="font-weight:600;">
                        {{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}
                    </td>
                    <td>
                        <a href="{{ route('patients.show', $apt->patient) }}" style="font-weight:600;">
                            {{ $apt->patient->full_name }}
                        </a>
                    </td>
                    <td class="text-muted">{{ $apt->doctor->full_name }}</td>
                    <td>{{ $apt->service_type }}</td>
                    <td><span class="badge badge-{{ $apt->status }}">{{ $apt->status }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-calendar-x"></i>
                            <p>No appointments today</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Right column --}}
    <div style="display:flex; flex-direction:column; gap:14px;">

        {{-- Doctor status --}}
        <div class="card">
            <div class="card-header">
                <h2><i class="bi bi-person-check" style="color:#16a34a; margin-right:6px;"></i>Doctors Today</h2>
            </div>
            <div class="list-group">
                @forelse($activeDoctors as $doctor)
                <div class="list-group-item">
                    <div>
                        <div style="font-weight:600; font-size:13px;">{{ $doctor->full_name }}</div>
                        <div class="text-muted text-sm">{{ $doctor->specialization }}</div>
                    </div>
                    <span class="badge badge-info">{{ $doctor->today_appointments_count }} appts</span>
                </div>
                @empty
                <div class="list-group-item text-muted text-sm">No active doctors</div>
                @endforelse
            </div>
        </div>

        {{-- Upcoming --}}
        <div class="card">
            <div class="card-header">
                <h2><i class="bi bi-clock-history" style="color:#b45309; margin-right:6px;"></i>Upcoming</h2>
            </div>
            <div class="list-group">
                @forelse($upcomingAppointments as $apt)
                <div class="list-group-item" style="flex-direction:column; align-items:flex-start; gap:2px;">
                    <div class="flex-between" style="width:100%;">
                        <a href="{{ route('appointments.show', $apt) }}" style="font-weight:600; font-size:13px;">
                            {{ $apt->patient->full_name }}
                        </a>
                        <span class="text-muted text-sm">{{ $apt->appointment_date->format('M d') }}</span>
                    </div>
                    <div class="text-muted text-sm">
                        {{ $apt->doctor->full_name }} · {{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i A') }}
                    </div>
                </div>
                @empty
                <div class="list-group-item text-muted text-sm">No upcoming appointments</div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection