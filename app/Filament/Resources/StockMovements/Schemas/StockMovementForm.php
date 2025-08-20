<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->options(['in' => 'In', 'out' => 'Out', 'transfer' => 'Transfer'])
                    ->required(),
                TextInput::make('actor_type')
                    ->required(),
                TextInput::make('actor_id')
                    ->required()
                    ->numeric(),
                TextInput::make('subject_type')
                    ->required(),
                TextInput::make('subject_id')
                    ->required()
                    ->numeric(),
                Select::make('from_center_id')
                    ->relationship('fromCenter', 'name'),
                Select::make('to_center_id')
                    ->relationship('toCenter', 'name'),
                Select::make('patient_id')
                    ->relationship('patient', 'name'),
                Select::make('supplier_id')
                    ->relationship('supplier', 'name'),
            ]);
    }
}
