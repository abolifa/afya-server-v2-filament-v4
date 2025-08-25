<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListAppointmentActivities extends ListActivities
{
    protected static string $resource = AppointmentResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        $user = auth()->user();
        if (!$user || !$user->whiteList || !$user->whiteList->can_see_activities) {
            abort(403, 'لا تملك صلاحية الوصول إلى هذه الصفحة.');
        }
    }
}
