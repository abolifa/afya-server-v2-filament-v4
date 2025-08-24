<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasCenterScope
{
    /** Set to true on-demand to bypass the scope (e.g., in admin tools) */
    public static bool $bypassCenterScope = false;

    /** Small helper to run a callback without the scope */
    public static function withoutCenterScope(callable $callback)
    {
        $old = self::$bypassCenterScope;
        self::$bypassCenterScope = true;
        try {
            return $callback();
        } finally {
            self::$bypassCenterScope = $old;
        }
    }

    protected static function bootHasCenterScope(): void
    {
        static::addGlobalScope('center_visibility', function (Builder $builder) {
            // Bypass when needed or when no auth (CLI, queue) or user can see all
            if (self::$bypassCenterScope || !Auth::check()) {
                return;
            }

            $user = Auth::user();

            if ($user->can_see_all_stock) {
                return;
            }

            // If user has no center, show nothing (and you can handle 403 in controller/page)
            if (!$user->center_id) {
                // Ensure the listing returns empty set rather than everything
                $builder->whereRaw('1=0');
                return;
            }

            // Show records from user center OR anything this user created
            $builder->where(function (Builder $q) use ($user) {
                $q->where($q->getModel()->getTable() . '.center_id', $user->center_id)
                    ->orWhere($q->getModel()->getTable() . '.created_by', $user->id);
            });
        });
    }
}
