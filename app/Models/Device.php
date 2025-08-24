<?php

namespace App\Models;

use App\Traits\HasBlamesUsers;
use Database\Factories\DeviceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    /** @use HasFactory<DeviceFactory> */
    use HasFactory, SoftDeletes, HasBlamesUsers;

    protected $fillable = ['name', 'manufacturer', 'model', 'serial_number', 'active'];

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
