<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait HasBlamesUsers
{
    /**
     * Boot the trait for a model.
     * This is automatically called by Laravel when the trait is used.
     */
    protected static function bootHasBlamesUsers(): void
    {
        // Set the 'created_by' on model creation
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });

        // Set the 'updated_by' on model update
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });

        // Set the 'deleted_by' on model deletion
        static::deleting(function ($model) {
            // Check if the model uses SoftDeletes
            if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))) {
                $model->deleted_by = Auth::id();
                $model->deleted_at = now(); // Set the deleted_at timestamp
                $model->save();
            }
        });
    }

    // Define the relationships within the trait

    /**
     * Get the user who created the record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who soft-deleted the record.
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
