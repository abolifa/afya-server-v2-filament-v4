<?php

namespace App\Filament\Resources\Devices\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DeviceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextEntry::make('name')
                        ->label('إسم الجهاز'),
                    TextEntry::make('manufacturer')
                        ->label('الشركة المصنعة'),
                    TextEntry::make('model')
                        ->label('الموديل'),
                    TextEntry::make('serial_number')
                        ->label('الرقم التسلسلي'),
                    TextEntry::make('created_at')
                        ->label('تاريخ الإنشاء')
                        ->dateTime(),
                    TextEntry::make('updated_at')
                        ->label('تاريخ التحديث')
                        ->dateTime(),
                    IconEntry::make('active')
                        ->label('نشط')
                        ->boolean(),
                    TextEntry::make('appointments')
                        ->label('ساعات العمل')
                        ->getStateUsing(function ($record) {
                            $totalHours = $record->appointments()
                                ->where('status', 'completed')
                                ->get()
                                ->sum(fn($appointment) => $appointment->total_hours);
                            $hours = floor($totalHours);
                            $minutes = round(($totalHours - $hours) * 60);
                            return "{$hours}س {$minutes}د";
                        }),
                ])->columns(3)->columnSpanFull(),
            ]);
    }
}
