<?php

namespace App\Filament\Resources\Prescriptions\Tables;

use App\Support\SharedTableColumns;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PrescriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('patient.name')
                    ->label('المريض')
                    ->searchable(),
                TextColumn::make('doctor.name')
                    ->label('الطبيب')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('center.name')
                    ->label('المركز')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('appointment.id')
                    ->label('الموعد')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('date')
                    ->label('تاريخ')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                IconColumn::make('dispensed')
                    ->label('تم الصرف')
                    ->boolean()
                    ->tooltip(fn($record) => $record->dispensed_date)
                    ->alignCenter()
                    ->sortable(),
                ...SharedTableColumns::blame(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('dispense')
                        ->label('صرف الوصفة')
                        ->icon('heroicon-o-check')
                        ->action(fn($record) => $record->update(['dispensed' => true, 'dispensed_date' => now()]))
                        ->requiresConfirmation()
                        ->color('success')
                        ->visible(fn($record) => !$record->dispensed),
                ]),
                RestoreAction::make(),
                EditAction::make(),
                ForceDeleteAction::make(),
            ]);
    }
}
