<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PatientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('image')
                    ->label('الصورة الشخصية')
                    ->imageSize(80),
                TextEntry::make('name'),
                TextEntry::make('national_id')
                    ->label('الرقم الوطني'),
                TextEntry::make('file_number')
                    ->label('رقم الملف'),
                TextEntry::make('phone')
                    ->label('رقم الهاتف'),
                TextEntry::make('family_issue_number')
                    ->placeholder('X لا يوجد')
                    ->label('رقم قيد العائلة'),
                TextEntry::make('email')
                    ->placeholder('X لا يوجد')
                    ->label('البريد الإلكتروني'),
                TextEntry::make('gender')
                    ->placeholder('X لا يوجد')
                    ->label('الجنس'),
                TextEntry::make('dob')
                    ->label('تاريخ الميلاد')
                    ->placeholder('X لا يوجد')
                    ->date('d/m/Y'),
                TextEntry::make('blood_group')
                    ->placeholder('X لا يوجد')
                    ->label('فصيلة الدم'),
                TextEntry::make('center.name')
                    ->label('المركز الصحي'),
                TextEntry::make('device.name')
                    ->placeholder('X لا يوجد')
                    ->label('الجهاز'),
                TextEntry::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime(),
                IconEntry::make('verified')
                    ->label('مستوفي البيانات')
                    ->boolean(),
            ])->columns(3);
    }
}
