<?php

namespace App\Models;

use Database\Factories\TemplateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    /** @use HasFactory<TemplateFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'show_signature' => 'boolean',
        'show_stamp' => 'boolean',
        'show_commissioner' => 'boolean',
        'show_role' => 'boolean',
    ];

    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class);
    }
}
