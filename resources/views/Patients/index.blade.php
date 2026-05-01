@extends('layouts.app')
@section('title', 'Patients')
@section('page-title', 'Patient Directory')

@section('content')

<div class="card">
    <div class="card-header">
        <h2><i class="bi bi-people" style="color:#2563eb; margin-right:6px;"></i>All Patients</h2>
        <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus"></i> Register Patient
        </a>
    </div>

    {{-- Search bar --}}
    <div class="card-body" style="border-bottom:1px solid #f1f5f9; padding-bottom:14px;">
        <form method="GET" style="display:flex; gap:8px;">
            <input type="text" name="search" placeholder="Search by name, phone or email…" value="{{ $search }}" style="max-width:320px;">
            <button type="submit" class="btn btn-outline btn-sm">Search</button>
            @if($search)
                <a href="{{ route('patients.index') }}" class="btn btn-ghost btn-sm">Clear</a>
            @endif
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Phone</th>
                <th>Blood Type</th>
                <th>Visits</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
            <tr>
                <td class="text-muted text-sm">{{ $patient->id }}</td>
                <td>
                    <a href="{{ route('patients.show', $patient) }}" style="font-weight:600;">
                        {{ $patient->full_name }}
                    </a>
                    @if($patient->email)
                        <div class="text-muted text-sm">{{ $patient->email }}</div>
                    @endif
                </td>
                <td class="text-muted" style="text-transform:capitalize;">{{ $patient->gender }}</td>
                <td>{{ $patient->date_of_birth->format('M d, Y') }}</td>
                <td>{{ $patient->phone }}</td>
                <td>
                    @if($patient->blood_type)
                        <span class="badge badge-danger">{{ $patient->blood_type }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <span class="badge badge-info">{{ $patient->appointments_count }}</span>
                </td>
                <td>
                    <div class="flex-center gap-2">
                        <a href="{{ route('patients.show', $patient) }}" class="btn btn-outline btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-outline btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="{{ route('patients.destroy', $patient) }}"
                              onsubmit="return confirm('Delete this patient?')">
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
                        <i class="bi bi-people"></i>
                        <p>No patients found.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($patients->hasPages())
        <div class="pagination">
            {{ $patients->withQueryString()->links('pagination::simple-default') }}
        </div>
    @endif
</div>

@endsection