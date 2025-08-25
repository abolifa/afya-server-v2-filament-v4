<?php

namespace App\Filament\Resources\WhiteLists\Pages;

use App\Filament\Resources\WhiteLists\WhiteListResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWhiteLists extends ListRecords
{
    protected static string $resource = WhiteListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
