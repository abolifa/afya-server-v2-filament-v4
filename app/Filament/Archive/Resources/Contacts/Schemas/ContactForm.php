<?php

namespace App\Filament\Archive\Resources\Contacts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('نوع جهة الاتصال')
                    ->options([
                        'individual' => 'فرد',
                        'company' => 'شركة',
                        'organization' => 'مؤسسة',
                    ])
                    ->native(false)
                    ->required(),
                TextInput::make('name')
                    ->label('الاسم')
                    ->required(),
                TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email(),
                TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel(),
            ]);
    }
}
