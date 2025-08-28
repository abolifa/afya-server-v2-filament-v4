<?php

namespace App\Filament\Site\Resources\Awarenesses\Pages;

use App\Filament\Site\Resources\Awarenesses\AwarenessResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAwarenesses extends ListRecords
{
    protected static string $resource = AwarenessResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
