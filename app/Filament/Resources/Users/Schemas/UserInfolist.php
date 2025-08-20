<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('الإسم'),
                TextEntry::make('email')
                    ->label('البريد الإلكتروني'),
                TextEntry::make('phone')
                    ->label('رقم الهاتف'),
                TextEntry::make('center.name')
                    ->label('المركز'),
                IconEntry::make('active')
                    ->label('نشط')
                    ->boolean(),
                IconEntry::make('doctor')
                    ->label('طبيب')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime(),
            ]);
    }
}
