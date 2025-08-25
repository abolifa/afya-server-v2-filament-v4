<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListOrderActivities extends ListActivities
{
    protected static string $resource = OrderResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        $user = auth()->user();
        if (!$user || !$user->whiteList || !$user->whiteList->can_see_activities) {
            abort(403, 'لا تملك صلاحية الوصول إلى هذه الصفحة.');
        }
    }
}
