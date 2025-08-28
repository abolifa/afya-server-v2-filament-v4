<?php

namespace App\Filament\Site\Resources\Posts\Pages;

use App\Filament\Site\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
