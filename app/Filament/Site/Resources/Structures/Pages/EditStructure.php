<?php

namespace App\Filament\Site\Resources\Structures\Pages;

use App\Filament\Site\Resources\Structures\StructureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStructure extends EditRecord
{
    protected static string $resource = StructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
