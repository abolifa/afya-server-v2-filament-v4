<?php

namespace App\Filament\Resources\Centers\Schemas;

use App\Filament\Forms\Components\LocationField;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CenterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('اسم المركز')
                    ->required(),
                TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->required(),
                TextInput::make('alt_phone')
                    ->label('رقم الهاتف البديل')
                    ->tel(),
                TextInput::make('address')
                    ->label('العنوان')
                    ->required(),
                TextInput::make('street')
                    ->label('الشارع'),
                TextInput::make('city')
                    ->label('المدينة'),
                TextInput::make('latitude')
                    ->label('خط العرض')
                    ->numeric(),
                TextInput::make('longitude')
                    ->label('خط الطول')
                    ->numeric(),
                LocationField::make('location')
                    ->columnSpanFull()
                    ->label('الموقع على الخريطة')
                    ->latitude('latitude')
                    ->address('address')
                    ->street('street')
                    ->city('city')
                    ->longitude('longitude'),
            ]);
    }
}
