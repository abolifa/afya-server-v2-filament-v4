<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListOrderActivities extends ListActivities
{
    protected static string $resource = OrderResource::class;
}
