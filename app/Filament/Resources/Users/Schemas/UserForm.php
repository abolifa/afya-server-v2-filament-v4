<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Filament\Forms\Components\BooleanField;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('الإسم')
                    ->required(),
                TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel(),
                TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->required(),
                Select::make('center_id')
                    ->label('المركز')
                    ->relationship('center', 'name'),
                BooleanField::make('active'),
                BooleanField::make('doctor')
                    ->label('طبيب')
                    ->default(false),
            ]);
    }
}
