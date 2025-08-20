<?php

namespace App\Filament\Resources\Patients\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('file_number')
                    ->label('رقم الملف')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('الإسم')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('national_id')
                    ->label('الرقم الوطني')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('family_issue_number')
                    ->label('رقم القيد')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('الهاتف')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('center.name')
                    ->label('المركز')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('device.name')
                    ->label('الجهاز')
                    ->alignCenter()
                    ->sortable()
                    ->placeholder('غير محدد')
                    ->searchable(),
                IconColumn::make('verified')
                    ->label('مكتمل')
                    ->alignCenter()
                    ->sortable()
                    ->boolean(),

                TextColumn::make('email')
                    ->label('البريد')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('gender')
                    ->label('الجنس')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn($state) => match ($state) {
                        'male' => 'ذكر',
                        'female' => 'أنثى',
                    })
                    ->alignCenter(),
                TextColumn::make('dob')
                    ->label('تاريخ الميلاد')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->sortable(),
                TextColumn::make('blood_group')
                    ->label('فصيلة الدم')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignCenter()
                    ->searchable(),

            ])
            ->filters([
                SelectFilter::make('center_id')
                    ->label('المركز')
                    ->relationship('center', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('الكل'),
                SelectFilter::make('device_id')
                    ->label('الجهاز')
                    ->relationship('device', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('الكل'),
                SelectFilter::make('verified')
                    ->label('مكتمل')
                    ->options([
                        true => 'نعم',
                        false => 'لا',
                    ])
                    ->placeholder('الكل'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
