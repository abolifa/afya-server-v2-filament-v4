<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Patients\PatientResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListPatientActivities extends ListActivities
{
    protected static string $resource = PatientResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        $user = auth()->user();
        if (!$user || !$user->whiteList || !$user->whiteList->can_see_activities) {
            abort(403, 'لا تملك صلاحية الوصول إلى هذه الصفحة.');
        }
    }
}
