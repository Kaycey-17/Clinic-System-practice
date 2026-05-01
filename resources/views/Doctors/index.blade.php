@extends('layouts.app')
@section('title', 'Doctors')
@section('page-title', 'Doctor Manager')

@section('content')

<div class="card">
    <div class="card-header">
        <h2><i class="bi bi-person-badge" style="color:#2563eb; margin-right:6px;"></i>All Doctors</h2>
        <a href="{{ route('doctors.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus"></i> Add Doctor
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Specialization</th>
                <th>Phone</th>
                <th>Fee</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>Appts</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($doctors as $doctor)
            <tr>
                <td>
                    <a href="{{ route('doctors.show', $doctor) }}" style="font-weight:600;">
                        {{ $doctor->full_name }}
                    </a>
                    @if($doctor->qualifications)
                        <div class="text-muted text-sm">{{ $doctor->qualifications }}</div>
                    @endif
                </td>
                <td><span class="badge badge-info">{{ $doctor->specialization }}</span></td>
                <td class="text-muted text-sm">{{ $doctor->phone }}</td>
                <td style="font-weight:600;">₱{{ number_format($doctor->consultation_fee, 2) }}</td>
                <td class="text-sm">
                    @if($doctor->available_days)
                        {{ implode(', ', array_map(fn($d) => substr($d, 0, 3), $doctor->available_days)) }}
                        @if($doctor->start_time)
                            <div class="text-muted text-sm">
                                {{ \Carbon\Carbon::parse($doctor->start_time)->format('h:i A') }}
                                – {{ \Carbon\Carbon::parse($doctor->end_time)->format('h:i A') }}
                            </div>
                        @endif
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $doctor->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td><span class="badge badge-info">{{ $doctor->appointments_count }}</span></td>
                <td>
                    <div class="flex-center gap-2">
                        <a href="{{ route('doctors.show', $doctor) }}" class="btn btn-outline btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-outline btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('doctors.destroy', $doctor) }}"
                              onsubmit="return confirm('Remove this doctor?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline btn-sm" style="color:#dc2626; border-color:#fecaca;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <i class="bi bi-person-badge"></i>
                        <p>No doctors found.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($doctors->hasPages())
        <div class="pagination">
            {{ $doctors->links('pagination::simple-default') }}
        </div>
    @endif
</div>

@endsection