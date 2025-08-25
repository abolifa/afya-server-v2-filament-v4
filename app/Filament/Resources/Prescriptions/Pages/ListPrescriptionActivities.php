<?php

namespace App\Filament\Resources\Prescriptions\Pages;

use App\Filament\Resources\Prescriptions\PrescriptionResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListPrescriptionActivities extends ListActivities
{
    protected static string $resource = PrescriptionResource::class;
}
