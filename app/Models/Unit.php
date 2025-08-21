<?php

namespace App\Models;

use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    /** @use HasFactory<UnitFactory> */
    use HasFactory;

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
