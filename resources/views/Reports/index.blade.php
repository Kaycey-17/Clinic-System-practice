@extends('layouts.app')

@section('content')
<div class="container">

    <h2>Reports Dashboard</h2>


    <form method="GET" class="mb-4">
        <input type="date" name="start_date" value="{{ $start }}">
        <input type="date" name="end_date" value="{{ $end }}">
        <button type="submit">Filter</button>
    </form>

    <div>
        <h3>Total Revenue: ₱{{ number_format($totalRevenue, 2) }}</h3>
    </div>

    <div>
        <h4>Appointments</h4>
        <ul>
            <li>Total: {{ $appointmentStats['total'] }}</li>
            <li>Pending: {{ $appointmentStats['pending'] }}</li>
            <li>Confirmed: {{ $appointmentStats['confirmed'] }}</li>
            <li>Completed: {{ $appointmentStats['completed'] }}</li>
            <li>Cancelled: {{ $appointmentStats['cancelled'] }}</li>
        </ul>
    </div>

    <div>
        <h4>Doctor Performance</h4>
        <table border="1">
            <tr>
                <th>Doctor</th>
                <th>Appointments</th>
                <th>Revenue</th>
            </tr>
            @foreach($revenueByDoctor as $doc)
            <tr>
                <td>{{ $doc['doctor'] }}</td>
                <td>{{ $doc['appointments'] }}</td>
                <td>₱{{ number_format($doc['revenue'], 2) }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div>
        <h4>Monthly Revenue</h4>
        <ul>
            @foreach($monthlyRevenue as $month)
                <li>Month {{ $month->month }}: ₱{{ number_format($month->total, 2) }}</li>
            @endforeach
        </ul>
    </div>

</div>
@endsection