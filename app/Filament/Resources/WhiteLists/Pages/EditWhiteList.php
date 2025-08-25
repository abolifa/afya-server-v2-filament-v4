<?php

namespace App\Filament\Resources\WhiteLists\Pages;

use App\Filament\Resources\WhiteLists\WhiteListResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWhiteList extends EditRecord
{
    protected static string $resource = WhiteListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
