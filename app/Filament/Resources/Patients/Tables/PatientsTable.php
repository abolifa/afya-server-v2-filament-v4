<?php

namespace App\Filament\Resources\Patients\Tables;

use App\Filament\Resources\Patients\PatientResource;
use App\Support\SharedTableColumns;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
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
                    ->toggleable(isToggledHiddenByDefault: true)
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
                    ->toggleable(isToggledHiddenByDefault: true)
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
                ...SharedTableColumns::blame(),

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
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('activities')
                    ->url(fn($record) => PatientResource::getUrl('activities', ['record' => $record]))
                    ->label('الأنشطة')
                    ->visible(Auth::user()->whiteList && Auth::user()->whiteList->can_see_activities)
                    ->icon('lucide-activity-square'),
                ViewAction::make(),
                RestoreAction::make(),
                EditAction::make(),
                ForceDeleteAction::make(),
            ]);
    }
}
