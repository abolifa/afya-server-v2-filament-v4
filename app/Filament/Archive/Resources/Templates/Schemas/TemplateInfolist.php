<?php

namespace App\Filament\Archive\Resources\Templates\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TemplateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('greetings'),
                TextEntry::make('body'),
                TextEntry::make('closing'),
                TextEntry::make('letterhead'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
