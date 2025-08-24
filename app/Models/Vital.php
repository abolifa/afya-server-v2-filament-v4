<?php

namespace App\Models;

use App\Traits\HasBlamesUsers;
use Database\Factories\VitalFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vital extends Model
{
    /** @use HasFactory<VitalFactory> */
    use HasFactory, SoftDeletes, HasBlamesUsers;

    protected $fillable = [
        'patient_id', 'doctor_id', 'recorded_at', 'weight', 'systolic', 'diastolic',
        'heart_rate', 'temperature', 'oxygen_saturation', 'sugar_level', 'notes'
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
