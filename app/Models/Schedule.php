<?php

namespace App\Models;

use App\Traits\HasBlamesUsers;
use Database\Factories\ScheduleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    /** @use HasFactory<ScheduleFactory> */
    use HasFactory, SoftDeletes, HasBlamesUsers;

    protected $fillable = ['center_id', 'day', 'start_time', 'end_time', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }
}
