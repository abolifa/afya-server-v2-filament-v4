<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('إسم المورد')
                    ->required(),
                TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel(),
                TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email(),
                TextInput::make('address')
                    ->label('العنوان'),
            ]);
    }
}
