<?php

namespace App\Filament\Archive\Resources\Templates\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TemplateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('إسم القالب'),
                TextEntry::make('greetings')
                    ->label('التحية'),
                TextEntry::make('closing')
                    ->label('الخاتمة'),
                TextEntry::make('commissioner')
                    ->label('المفوض'),
                TextEntry::make('role')
                    ->label('المنصب'),
                TextEntry::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y h:i A'),
                ImageEntry::make('signature')
                    ->visibility('public')
                    ->disk('public')
                    ->label('التوقيع'),
                ImageEntry::make('stamp')
                    ->visibility('public')
                    ->disk('public')
                    ->label('الختم'),
                ImageEntry::make('letterhead')
                    ->visibility('public')
                    ->disk('public')
                    ->imageHeight(300)
                    ->imageWidth(200)
                    ->label('الرسالة المعنونة'),
            ]);
    }
}
