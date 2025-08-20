<?php

namespace App\Filament\Resources\Prescriptions\Schemas;

use App\Filament\Infolists\Components\PrescriptionItemEntryTable;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PrescriptionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextEntry::make('patient.name')
                        ->label('المريض'),
                    TextEntry::make('doctor.name')
                        ->label('الطبيب'),
                    TextEntry::make('center.name')
                        ->label('المركز'),
                    TextEntry::make('appointment.id')
                        ->label('الموعد')
                        ->placeholder('-'),
                    TextEntry::make('date')
                        ->label('تاريخ الوصفة')
                        ->date('/d/m/Y'),
                    TextEntry::make('created_at')
                        ->label('تاريخ الإنشاء')
                        ->dateTime(),
                    TextEntry::make('updated_at')
                        ->label('تاريخ التحديث')
                        ->dateTime(),
                    IconEntry::make('dispensed')
                        ->label('تم الصرف')
                        ->boolean(),
                    TextEntry::make('dispensed_at')
                        ->label('تاريخ الصرف')
                        ->date('d/m/Y')
                        ->placeholder('-'),
                ])->columns(3)
                    ->columnSpanFull(),

                Section::make([
                    PrescriptionItemEntryTable::make('items')
                        ->label('الأصناف'),
                ])->columnSpanFull(),
            ]);
    }
}
