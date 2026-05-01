<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'specialization', 'email',
        'phone', 'qualifications', 'consultation_fee',
        'available_days', 'start_time', 'end_time', 'is_active',
    ];

    protected $casts = [
        'available_days' => 'array',
        'is_active'      => 'boolean',
    ];

    public function getFullNameAttribute(): string
    {
        return "Dr. {$this->first_name} {$this->last_name}";
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function todayAppointments()
    {
        return $this->hasMany(Appointment::class)
            ->whereDate('appointment_date', today())
            ->whereIn('status', ['pending', 'confirmed']);
    }
}