<?php

namespace App\Filament\Site\Resources\Announcements\Pages;

use App\Filament\Site\Resources\Announcements\AnnouncementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAnnouncement extends CreateRecord
{
    protected static string $resource = AnnouncementResource::class;
}
