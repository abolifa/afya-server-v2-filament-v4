<?php

namespace App\Filament\Archive\Resources\Letters\Pages;

use App\Filament\Archive\Resources\Letters\LetterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLetter extends CreateRecord
{
    protected static string $resource = LetterResource::class;
}
