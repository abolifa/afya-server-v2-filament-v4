<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextInput::make('name')
                        ->label('الإسم')
                        ->required(),
                    TextInput::make('symbol')
                        ->label('الرمز')
                        ->required(),
                    TextInput::make('conversion_factor')
                        ->label('معدل التحويل')
                        ->required()
                        ->numeric()
                        ->default(1.0),
                ])->columnSpanFull(),
            ]);
    }
}
