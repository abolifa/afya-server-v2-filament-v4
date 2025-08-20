<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Filament\Infolists\Components\TableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextEntry::make('center.name')
                        ->label('المركز'),
                    TextEntry::make('patient.name')
                        ->label('المريض'),
                    TextEntry::make('appointment.id')
                        ->placeholder('-')
                        ->label('رقم الموعد'),
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
                ])->columnSpanFull()
                    ->columns(3),


                Section::make([
                    TableEntry::make('items')
                        ->label('الأصناف'),
                ])->columnSpanFull(),
            ]);
    }
}
