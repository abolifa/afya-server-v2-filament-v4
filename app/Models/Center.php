<?php

namespace App\Models;

use App\Traits\HasBlamesUsers;
use Database\Factories\CenterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static find(mixed $param)
 */
class Center extends Model
{
    /** @use HasFactory<CenterFactory> */
    use HasFactory, SoftDeletes, HasBlamesUsers;


    protected $fillable = [
        'name',
        'phone',
        'alt_phone',
        'address',
        'street',
        'city',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(User::class)->where('doctor', true);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    public function outgoingTransfers(): HasMany
    {
        return $this->hasMany(TransferInvoice::class, 'from_center_id');
    }

    public function incomingTransfers(): HasMany
    {
        return $this->hasMany(TransferInvoice::class, 'to_center_id');
    }

    public function stockMovementsFrom(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'from_center_id');
    }

    public function stockMovementsTo(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'to_center_id');
    }
}
