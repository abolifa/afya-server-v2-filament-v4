<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Patients\PatientResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListPatientActivities extends ListActivities
{
    protected static string $resource = PatientResource::class;
}
