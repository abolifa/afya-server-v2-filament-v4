<?php

namespace App\Models;

use Database\Factories\ScheduleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    /** @use HasFactory<ScheduleFactory> */
    use HasFactory;

    protected $fillable = ['center_id', 'day', 'start_time', 'end_time', 'is_active'];

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }
}
