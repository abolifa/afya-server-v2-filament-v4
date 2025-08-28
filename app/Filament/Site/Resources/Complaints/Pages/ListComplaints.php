<?php

namespace App\Filament\Site\Resources\Complaints\Pages;

use App\Filament\Site\Resources\Complaints\ComplaintResource;
use Filament\Resources\Pages\ListRecords;

class ListComplaints extends ListRecords
{
    protected static string $resource = ComplaintResource::class;
}
