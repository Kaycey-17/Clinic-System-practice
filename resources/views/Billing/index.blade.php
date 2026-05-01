@extends('layouts.app')
@section('title', 'Billing')
@section('page-title', 'Billing Center')

@section('content')

{{-- Summary stats --}}
<div class="grid-4" style="grid-template-columns: repeat(3, 1fr); margin-bottom:20px;">
    <div class="stat-card">
        <div class="stat-icon green"><i class="bi bi-cash-stack"></i></div>
        <div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">₱{{ number_format($summary['total_revenue'], 0) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
        <div>
            <div class="stat-label">Pending Balance</div>
            <div class="stat-value">₱{{ number_format($summary['pending'], 0) }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="bi bi-graph-up"></i></div>
        <div>
            <div class="stat-label">This Month</div>
            <div class="stat-value">₱{{ number_format($summary['this_month'], 0) }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2><i class="bi bi-receipt" style="color:#2563eb; margin-right:6px;"></i>Transactions</h2>
        <a href="{{ route('billing.report') }}" class="btn btn-outline btn-sm">
            <i class="bi bi-bar-chart"></i> Reports
        </a>
    </div>

    <div class="card-body" style="border-bottom:1px solid #f1f5f9; padding-bottom:14px;">
        <form method="GET" style="display:flex; gap:8px; align-items:center;">
            <select name="status" style="width:auto;">
                <option value="">All Statuses</option>
                @foreach(['unpaid','partial','paid','refunded'] as $s)
                    <option value="{{ $s }}" {{ $status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline btn-sm">Filter</button>
            @if($status)
                <a href="{{ route('billing.index') }}" class="btn btn-ghost btn-sm">Clear</a>
            @endif
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Service</th>
                <th>Total</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tx)
            <tr>
                <td class="text-muted text-sm">{{ str_pad($tx->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <a href="{{ route('patients.show', $tx->patient) }}" style="font-weight:600;">
                        {{ $tx->patient->full_name }}
                    </a>
                </td>
                <td class="text-muted text-sm">{{ $tx->appointment->doctor->full_name }}</td>
                <td class="text-sm">{{ $tx->appointment->service_type }}</td>
                <td style="font-weight:600;">₱{{ number_format($tx->total_amount, 2) }}</td>
                <td style="font-weight:600; color:#16a34a;">₱{{ number_format($tx->amount_paid, 2) }}</td>
                @if($tx->balance > 0)
                <td style="font-weight:600; color:#dc2626">
                @else
                <td style="font-weight:600; color:#94a3b8">
                @endif
                    ₱{{ number_format($tx->balance, 2) }}
                </td>
                <td><span class="badge badge-{{ $tx->payment_status }}">{{ $tx->payment_status }}</span></td>
                <td>
                    <a href="{{ route('billing.show', $tx) }}" class="btn btn-outline btn-sm">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <i class="bi bi-receipt"></i>
                        <p>No transactions yet.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($transactions->hasPages())
        <div class="pagination">
            {{ $transactions->withQueryString()->links('pagination::simple-default') }}
        </div>
    @endif
</div>

@endsection