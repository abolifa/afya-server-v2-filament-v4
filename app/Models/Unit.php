<?php

namespace App\Models;

use App\Traits\HasBlamesUsers;
use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    /** @use HasFactory<UnitFactory> */
    use HasFactory, SoftDeletes, HasBlamesUsers;

    protected $fillable = [
        'name',
        'symbol',
        'conversion_factor',
    ];

    protected $casts = [
        'conversion_factor' => 'float',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
