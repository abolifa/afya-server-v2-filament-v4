<?php

namespace App\Models;

use App\Traits\HasBlamesUsers;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    /** @use HasFactory<AppointmentFactory> */
    use HasFactory, SoftDeletes, HasBlamesUsers;

    protected $fillable = [
        'center_id', 'patient_id', 'doctor_id', 'device_id',
        'date', 'time', 'status', 'intended', 'notes', 'start_time', 'end_time',
        'ordered'
    ];


    protected $appends = ['total_hours'];

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }

    public function prescription(): HasOne
    {
        return $this->hasOne(Prescription::class);
    }

    public function getTotalHoursAttribute(): float
    {
        if (!$this->start_time || !$this->end_time) {
            return 0;
        }
        $defference = strtotime($this->end_time) - strtotime($this->start_time);
        return round($defference / 3600, 2);
    }
}
