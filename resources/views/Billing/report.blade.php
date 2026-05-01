@extends('layouts.app')

@section('content')
<div class="container">

    <h2>Revenue Report</h2>

    <form method="GET" style="margin-bottom:20px;">
        <select name="month">
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                    {{ date('F', mktime(0,0,0,$m,1)) }}
                </option>
            @endforeach
        </select>

        <select name="year">
            @foreach(range(now()->year - 2, now()->year) as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endforeach
        </select>

        <button type="submit">Generate</button>
    </form>

    <div style="margin-bottom:20px;">
        <p><strong>Revenue:</strong> ₱{{ number_format($periodRevenue, 2) }}</p>
        <p><strong>Outstanding:</strong> ₱{{ number_format($periodPending, 2) }}</p>
        <p><strong>Appointments:</strong> {{ $periodAppointments }}</p>
        <p><strong>Refunds:</strong> ₱{{ number_format($periodRefunds, 2) }}</p>
    </div>

    <h3>Revenue by Doctor</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>Doctor</th>
            <th>Specialization</th>
            <th>Revenue</th>
        </tr>

        @forelse($revenueByDoctor as $doc)
        <tr>
            <td>{{ $doc->name }}</td>
            <td>{{ $doc->specialization }}</td>
            <td>₱{{ number_format($doc->revenue, 2) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="3">No data</td>
        </tr>
        @endforelse
    </table>

    <br>

    <h3>Recent Transactions</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>Date</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Total</th>
            <th>Status</th>
        </tr>

        @forelse($recentTransactions as $tx)
        <tr>
            <td>{{ $tx->created_at->format('M d, Y') }}</td>
            <td>{{ $tx->patient->first_name }} {{ $tx->patient->last_name }}</td>
            <td>{{ $tx->appointment->doctor->full_name }}</td>
            <td>₱{{ number_format($tx->total_amount, 2) }}</td>
            <td>{{ $tx->payment_status }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5">No transactions</td>
        </tr>
        @endforelse
    </table>

</div>
@endsection