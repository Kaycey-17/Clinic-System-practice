@extends('layouts.app')
@section('title', 'Calendar')
@section('page-title', 'Appointment Calendar')

@push('styles')
<style>
.cal-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
}

.cal-day-label {
    text-align: center;
    font-size: 12px;
    font-weight: bold;
    color: #555;
    padding: 6px 0;
}

.cal-cell {
    min-height: 80px;
    border: 1px solid #ccc;
    padding: 5px;
    font-size: 12px;
    background: #fff;
}

.cal-cell.today {
    background: #eef5ff;
}

.cal-cell.other {
    background: #f5f5f5;
}

.cal-num {
    font-weight: bold;
    font-size: 13px;
    margin-bottom: 4px;
}

.cal-event {
    display: block;
    font-size: 11px;
    padding: 2px 4px;
    margin-bottom: 2px;
    border-radius: 2px;
    text-decoration: none;
    color: #000;
    background: #e0e7ff;
}

.cal-event.confirmed { background: #d1fae5; }
.cal-event.completed { background: #e5e7eb; }
.cal-event.cancelled { background: #fecaca; text-decoration: line-through; }

.cal-more {
    font-size: 10px;
    color: #777;
}
</style>
@endpush

@section('content')

<div class="card">
    <div class="card-header">
        <h2><i class="bi bi-calendar3" style="color:#2563eb; margin-right:6px;"></i>{{ now()->format('F Y') }}</h2>
        <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus"></i> New Appointment
        </a>
    </div>

    <div class="card-body">

        {{-- Day labels --}}
        <div class="cal-grid" style="margin-bottom:4px;">
            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)
                <div class="cal-day-label">{{ $d }}</div>
            @endforeach
        </div>

        @php
            $startOfMonth = now()->startOfMonth();
            $endOfMonth   = now()->endOfMonth();
            $startOfCal   = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
            $endOfCal     = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
            $cursor       = $startOfCal->copy();
            $byDate       = $appointments->groupBy(fn($a) => $a->appointment_date->format('Y-m-d'));
        @endphp

        <div class="cal-grid">
            @while($cursor <= $endOfCal)
            @php
                $key        = $cursor->format('Y-m-d');
                $isToday    = $cursor->isToday();
                $isThisMonth = $cursor->month === now()->month;
                $dayApts    = $byDate->get($key, collect());
            @endphp
            <div class="cal-cell {{ $isToday ? 'is-today' : '' }} {{ !$isThisMonth ? 'other' : '' }}">
                <div class="cal-num {{ $isToday ? 'today-num' : '' }}">{{ $cursor->day }}</div>
                @foreach($dayApts->take(3) as $apt)
                    <a href="{{ route('appointments.show', $apt) }}"
                       class="cal-event {{ $apt->status }}"
                       title="{{ $apt->patient->full_name }} — {{ $apt->service_type }}">
                        {{ \Carbon\Carbon::parse($apt->appointment_time)->format('h:i') }}
                        {{ $apt->patient->first_name }}
                    </a>
                @endforeach
                @if($dayApts->count() > 3)
                    <div class="cal-more">+{{ $dayApts->count() - 3 }} more</div>
                @endif
            </div>
            @php $cursor->addDay(); @endphp
            @endwhile
        </div>

        {{-- Legend --}}
        <div style="display:flex; gap:16px; margin-top:16px; flex-wrap:wrap;">
            <span style="font-size:12px; color:#64748b;">
                <span style="display:inline-block; width:10px; height:10px; background:#dbeafe; border-radius:2px; margin-right:4px;"></span>Pending
            </span>
            <span style="font-size:12px; color:#64748b;">
                <span style="display:inline-block; width:10px; height:10px; background:#dcfce7; border-radius:2px; margin-right:4px;"></span>Confirmed
            </span>
            <span style="font-size:12px; color:#64748b;">
                <span style="display:inline-block; width:10px; height:10px; background:#f1f5f9; border-radius:2px; margin-right:4px;"></span>Completed
            </span>
            <span style="font-size:12px; color:#64748b;">
                <span style="display:inline-block; width:10px; height:10px; background:#fee2e2; border-radius:2px; margin-right:4px;"></span>Cancelled
            </span>
        </div>

    </div>
</div>

@endsection