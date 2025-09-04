<?php

namespace App\Filament\Archive\Resources\Letters\Pages;

use App\Filament\Archive\Resources\Letters\LetterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLetter extends ViewRecord
{
    protected static string $resource = LetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
