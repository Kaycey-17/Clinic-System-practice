<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id', 'patient_id', 'total_amount', 'amount_paid',
        'balance', 'payment_status', 'payment_method',
        'additional_services', 'additional_amount', 'notes',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->payment_status) {
            'unpaid'   => 'danger',
            'partial'  => 'warning',
            'paid'     => 'success',
            'refunded' => 'secondary',
            default    => 'secondary',
        };
    }
}