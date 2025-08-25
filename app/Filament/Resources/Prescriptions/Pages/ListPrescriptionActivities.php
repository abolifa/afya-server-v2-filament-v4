<?php

namespace App\Filament\Resources\Prescriptions\Pages;

use App\Filament\Resources\Prescriptions\PrescriptionResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListPrescriptionActivities extends ListActivities
{
    protected static string $resource = PrescriptionResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        $user = auth()->user();
        if (!$user || !$user->whiteList || !$user->whiteList->can_see_activities) {
            abort(403, 'لا تملك صلاحية الوصول إلى هذه الصفحة.');
        }
    }
}
