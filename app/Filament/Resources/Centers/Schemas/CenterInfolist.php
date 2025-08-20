<?php

namespace App\Filament\Resources\Centers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CenterInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('اسم المركز'),
                TextEntry::make('phone')
                    ->label('رقم الهاتف'),
                TextEntry::make('alt_phone')
                    ->label('رقم الهاتف البديل'),
                TextEntry::make('address')
                    ->limit(50)
                    ->label('العنوان'),
                TextEntry::make('street')
                    ->label('الشارع'),
                TextEntry::make('city')
                    ->label('المدينة'),
                TextEntry::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime(),
            ])->columns(3);
    }
}
