<?php

namespace App\Filament\Archive\Resources\Letters\Pages;

use App\Filament\Archive\Resources\Letters\LetterResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLetter extends EditRecord
{
    protected static string $resource = LetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
