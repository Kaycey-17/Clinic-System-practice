<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');

        $transactions = Transaction::with(['appointment.doctor', 'patient'])
            ->when($status, fn($q) => $q->where('payment_status', $status))
            ->latest()
            ->paginate(15);

        $summary = [
            'total_revenue'  => Transaction::where('payment_status', '!=', 'refunded')->sum('amount_paid'),
            'pending'        => Transaction::whereIn('payment_status', ['unpaid', 'partial'])->sum('balance'),
            'this_month'     => Transaction::whereMonth('created_at', now()->month)
                                    ->where('payment_status', '!=', 'refunded')->sum('amount_paid'),
        ];

        return view('billing.index', compact('transactions', 'status', 'summary'));
    }

    public function create(Request $request)
    {
        $appointmentId = $request->get('appointment_id');
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($appointmentId);

        if ($appointment->transaction) {
            return redirect()->route('billing.show', $appointment->transaction)->with('info', 'Invoice already exists.');
        }

        return view('billing.create', compact('appointment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id'      => 'required|exists:appointments,id',
            'total_amount'        => 'required|numeric|min:0',
            'amount_paid'         => 'required|numeric|min:0|lte:total_amount',
            'payment_method'      => 'nullable|in:cash,card,gcash,bank_transfer',
            'additional_services' => 'nullable|string',
            'additional_amount'   => 'nullable|numeric|min:0',
            'notes'               => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($validated['appointment_id']);

        $additionalAmount = $validated['additional_amount'] ?? 0;
        $totalAmount      = $validated['total_amount'] + $additionalAmount;
        $amountPaid       = $validated['amount_paid'];
        $balance          = $totalAmount - $amountPaid;

        $paymentStatus = 'unpaid';
        if ($amountPaid >= $totalAmount) {
            $paymentStatus = 'paid';
        } elseif ($amountPaid > 0) {
            $paymentStatus = 'partial';
        }

        $transaction = Transaction::create([
            'appointment_id'      => $appointment->id,
            'patient_id'          => $appointment->patient_id,
            'total_amount'        => $totalAmount,
            'amount_paid'         => $amountPaid,
            'balance'             => max(0, $balance),
            'payment_status'      => $paymentStatus,
            'payment_method'      => $validated['payment_method'],
            'additional_services' => $validated['additional_services'],
            'additional_amount'   => $additionalAmount,
            'notes'               => $validated['notes'],
        ]);

        if ($paymentStatus === 'paid') {
            $appointment->update(['status' => 'completed']);
        }

        return redirect()->route('billing.show', $transaction)->with('success', 'Invoice created successfully.');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['appointment.doctor', 'appointment.patient', 'patient']);
        return view('billing.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $transaction->load(['appointment.doctor', 'patient']);
        return view('billing.edit', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'amount_paid'    => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:cash,card,gcash,bank_transfer',
            'notes'          => 'nullable|string',
        ]);

        $newPaid    = $transaction->amount_paid + $validated['amount_paid'];
        $balance    = max(0, $transaction->total_amount - $newPaid);

        $status = 'partial';
        if ($newPaid >= $transaction->total_amount) {
            $status  = 'paid';
            $newPaid = $transaction->total_amount;
            $balance = 0;
        } elseif ($newPaid == 0) {
            $status = 'unpaid';
        }

        $transaction->update([
            'amount_paid'    => $newPaid,
            'balance'        => $balance,
            'payment_status' => $status,
            'payment_method' => $validated['payment_method'],
            'notes'          => $validated['notes'],
        ]);

        if ($status === 'paid') {
            $transaction->appointment->update(['status' => 'completed']);
        }

        return redirect()->route('billing.show', $transaction)->with('success', 'Payment updated.');
    }

    public function refund(Transaction $transaction)
    {
        $transaction->update(['payment_status' => 'refunded']);
        $transaction->appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Refund processed successfully.');
    }

    public function report(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year  = (int) $request->get('year', now()->year);

        $transactionsQuery = Transaction::whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        $periodRevenue = (clone $transactionsQuery)
            ->where('payment_status', '!=', 'refunded')
            ->sum('amount_paid');

        $periodRefunds = (clone $transactionsQuery)
            ->where('payment_status', 'refunded')
            ->sum('total_amount');

        $periodPending = (clone $transactionsQuery)
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->sum('balance');

        $periodAppointments = Appointment::whereMonth('appointment_date', $month)
            ->whereYear('appointment_date', $year)
            ->where('status', '!=', 'cancelled')
            ->count();

        $revenueByDoctor = DB::table('transactions')
            ->join('appointments', 'transactions.appointment_id', '=', 'appointments.id')
            ->join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->whereMonth('transactions.created_at', $month)
            ->whereYear('transactions.created_at', $year)
            ->where('transactions.payment_status', '!=', 'refunded')
            ->selectRaw("
                CONCAT('Dr. ', doctors.first_name, ' ', doctors.last_name) as name,
                doctors.specialization,
                SUM(transactions.amount_paid) as revenue,
                COUNT(transactions.id) as total_invoices,
                SUM(transactions.balance) as outstanding
            ")
            ->groupBy('doctors.id', 'doctors.first_name', 'doctors.last_name', 'doctors.specialization')
            ->orderByDesc('revenue')
            ->get();

        $revenueByService = DB::table('transactions')
            ->join('appointments', 'transactions.appointment_id', '=', 'appointments.id')
            ->whereMonth('transactions.created_at', $month)
            ->whereYear('transactions.created_at', $year)
            ->where('transactions.payment_status', '!=', 'refunded')
            ->selectRaw("
                appointments.service_type,
                SUM(transactions.amount_paid) as revenue,
                COUNT(*) as count
            ")
            ->groupBy('appointments.service_type')
            ->orderByDesc('revenue')
            ->get();

        $revenueByMethod = DB::table('transactions')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('payment_status', '!=', 'refunded')
            ->whereNotNull('payment_method')
            ->selectRaw("
                payment_method,
                SUM(amount_paid) as total,
                COUNT(*) as count
            ")
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        $monthlyTotals = DB::table('transactions')
            ->whereYear('created_at', $year)
            ->where('payment_status', '!=', 'refunded')
            ->selectRaw("
                MONTH(created_at) as month,
                SUM(amount_paid) as revenue,
                COUNT(*) as invoices
            ")
            ->groupByRaw('MONTH(created_at)')
            ->pluck('revenue', 'month');

        $monthlyChart = collect(range(1, 12))->map(fn ($m) => [
            'month'   => $m,
            'label'   => date('M', mktime(0, 0, 0, $m, 1)),
            'revenue' => $monthlyTotals[$m] ?? 0,
        ]);

        $recentTransactions = Transaction::with(['appointment.doctor', 'patient'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->latest()
            ->limit(10)
            ->get();

        return view('billing.report', compact(
            'month', 'year',
            'periodRevenue', 'periodRefunds', 'periodPending', 'periodAppointments',
            'revenueByDoctor', 'revenueByService', 'revenueByMethod',
            'monthlyChart', 'recentTransactions'
        ));
    }
}