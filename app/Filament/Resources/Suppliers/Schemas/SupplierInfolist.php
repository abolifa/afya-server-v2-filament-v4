<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SupplierInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('إسم المورد'),
                TextEntry::make('phone')
                    ->label('رقم الهاتف'),
                TextEntry::make('email')
                    ->label('البريد الإلكتروني'),
                TextEntry::make('address')
                    ->label('العنوان'),
                TextEntry::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime(),
            ]);
    }
}
