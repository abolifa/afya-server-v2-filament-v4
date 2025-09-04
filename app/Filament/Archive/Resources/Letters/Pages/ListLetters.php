<?php

namespace App\Filament\Archive\Resources\Letters\Pages;

use App\Filament\Archive\Resources\Letters\LetterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLetters extends ListRecords
{
    protected static string $resource = LetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
