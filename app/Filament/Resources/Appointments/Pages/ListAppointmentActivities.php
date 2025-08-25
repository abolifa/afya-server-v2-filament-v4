<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListAppointmentActivities extends ListActivities
{
    protected static string $resource = AppointmentResource::class;
}
