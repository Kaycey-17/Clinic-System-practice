@extends('layouts.app')
@section('title', $doctor->full_name)
@section('page-title', $doctor->full_name)

@section('content')

<div class="grid-3-1" style="grid-template-columns: 1fr 2fr;">

    {{-- Profile sidebar --}}
    <div style="display:flex; flex-direction:column; gap:14px;">

        <div class="card">
            <div class="card-body text-center" style="padding:24px 18px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#dcfce7; color:#15803d;
                            display:flex; align-items:center; justify-content:center;
                            font-size:26px; margin:0 auto 12px;">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div style="font-size:16px; font-weight:700; margin-bottom:4px;">{{ $doctor->full_name }}</div>
                <div class="mb-2">
                    <span class="badge badge-info">{{ $doctor->specialization }}</span>
                </div>
                @if($doctor->qualifications)
                    <div class="text-muted text-sm mb-2">{{ $doctor->qualifications }}</div>
                @endif
                <span class="badge {{ $doctor->is_active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="list-group">
                <div class="list-group-item" style="flex-direction:column; align-items:flex-start;">
                    <span class="text-muted text-sm">Consultation Fee</span>
                    <span style="font-size:20px; font-weight:700; color:#2563eb;">
                        ₱{{ number_format($doctor->consultation_fee, 2) }}
                    </span>
                </div>
                <div class="list-group-item" style="flex-direction:column; align-items:flex-start;">
                    <span class="text-muted text-sm">Phone</span>
                    <span style="font-weight:600;">{{ $doctor->phone }}</span>
                </div>
                <div class="list-group-item" style="flex-direction:column; align-items:flex-start;">
                    <span class="text-muted text-sm">Email</span>
                    <span style="font-weight:600;">{{ $doctor->email }}</span>
                </div>
                @if($doctor->available_days)
                <div class="list-group-item" style="flex-direction:column; align-items:flex-start;">
                    <span class="text-muted text-sm">Available Days</span>
                    <span style="font-weight:600;">{{ implode(', ', $doctor->available_days) }}</span>
                    @if($doctor->start_time)
                        <span class="text-muted text-sm">
                            {{ \Carbon\Carbon::parse($doctor->start_time)->format('h:i A') }}
                            – {{ \Carbon\Carbon::parse($doctor->end_time)->format('h:i A') }}
                        </span>
                    @endif
                </div>
                @endif
            </div>

            <div class="card-footer">
                <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-primary btn-sm" style="width:100%; justify-content:center;">
                    <i class="bi bi-pencil"></i> Edit Profile
                </a>
            </div>
        </div>

    </div>

    {{-- Appointments table --}}
    <div class="card">
        <div class="card-header">
            <h2><i class="bi bi-clock-history" style="color:#2563eb; margin-right:6px;"></i>Recent Appointments</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Patient</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Fee</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctor->appointments as $apt)
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
                    <td class="text-sm">{{ $apt->service_type }}</td>
                    <td><span class="badge badge-{{ $apt->status }}">{{ $apt->status }}</span></td>
                    <td style="font-weight:600;">₱{{ number_format($apt->fee, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-calendar-x"></i>
                            <p>No appointments yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection