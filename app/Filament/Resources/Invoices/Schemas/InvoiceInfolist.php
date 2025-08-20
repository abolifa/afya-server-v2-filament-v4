<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Filament\Infolists\Components\TableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextEntry::make('center.name')
                        ->label('المركز'),
                    TextEntry::make('supplier.name')
                        ->label('المورد'),
                    TextEntry::make('status')
                        ->label('الحالة')
                        ->formatStateUsing(fn($state) => match ($state) {
                            'pending' => 'قيد الإنتظار',
                            'confirmed' => 'مؤكد',
                            'cancelled' => 'ملغي',
                            default => 'غير معروف',
                        })->badge()
                        ->color(fn($state) => match ($state) {
                            'pending' => 'warning',
                            'confirmed' => 'success',
                            'cancelled' => 'danger',
                            default => 'secondary',
                        }),
                    TextEntry::make('created_at')
                        ->label('تاريخ الإنشاء')
                        ->dateTime(),
                    TextEntry::make('updated_at')
                        ->label('تاريخ التحديث')
                        ->dateTime(),
                ])->columns(3)
                    ->columnSpanFull(),
                Section::make([
                    TableEntry::make('items')
                        ->label('الأصناف'),
                ])->columnSpanFull(),
            ]);
    }
}
