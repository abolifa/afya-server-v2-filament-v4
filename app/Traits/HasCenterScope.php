<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait HasCenterScope
{
    public static bool $bypassCenterScope = false;

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
            if (self::$bypassCenterScope || !Auth::check()) {
                return;
            }

            $user = Auth::user();

            if ($user->see_all_center) {
                return;
            }
            if (!$user->center_id) {
                $builder->whereRaw('1=0');
                return;
            }
            $builder->where(function (Builder $q) use ($user) {
                $q->where($q->getModel()->getTable() . '.center_id', $user->center_id)
                    ->orWhere($q->getModel()->getTable() . '.created_by', $user->id);
            });
        });
    }
}
