<?php

namespace App\Filament\Site\Resources\Awarenesses\Pages;

use App\Filament\Site\Resources\Awarenesses\AwarenessResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAwareness extends EditRecord
{
    protected static string $resource = AwarenessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
