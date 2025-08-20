<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StockMovementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type'),
                TextEntry::make('actor_type'),
                TextEntry::make('actor_id')
                    ->numeric(),
                TextEntry::make('subject_type'),
                TextEntry::make('subject_id')
                    ->numeric(),
                TextEntry::make('fromCenter.name'),
                TextEntry::make('toCenter.name'),
                TextEntry::make('patient.name'),
                TextEntry::make('supplier.name'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
