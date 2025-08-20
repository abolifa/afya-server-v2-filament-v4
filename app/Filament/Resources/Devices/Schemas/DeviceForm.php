<?php

namespace App\Filament\Resources\Devices\Schemas;

use App\Filament\Forms\Components\BooleanField;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('إسم الجهاز')
                    ->required(),
                TextInput::make('manufacturer')
                    ->label('الشركة المصنعة'),
                TextInput::make('model')
                    ->label('الموديل'),
                TextInput::make('serial_number')
                    ->label('الرقم التسلسلي'),
                BooleanField::make('active')
                    ->required(),
            ]);
    }
}
