<?php

namespace App\Filament\Site\Resources\Complaints\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ComplaintsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('الاسم'),
                TextEntry::make('phone')
                    ->label('رقم الهاتف'),
                TextEntry::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->date('d/m/Y'),
                TextEntry::make('message')
                    ->label('الرسالة')
                    ->columnSpanFull(),
            ])->columns(3);
    }
}
