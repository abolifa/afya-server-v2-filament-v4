<?php

namespace App\Filament\Resources\Devices\Tables;

use App\Support\SharedTableColumns;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DevicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('إسم الجهاز')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('manufacturer')
                    ->label('الشركة المصنعة')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('model')
                    ->label('الموديل')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('serial_number')
                    ->label('الرقم التسلسلي')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                IconColumn::make('active')
                    ->label('نشط')
                    ->alignCenter()
                    ->sortable()
                    ->boolean(),
                TextColumn::make('work_hours')
                    ->label('ساعات العمل')
                    ->badge()
                    ->color('warning')
                    ->getStateUsing(function ($record) {
                        $totalHours = $record->appointments()
                            ->where('status', 'completed')
                            ->get()
                            ->sum(fn($appointment) => $appointment->total_hours);
                        $hours = floor($totalHours);
                        $minutes = round(($totalHours - $hours) * 60);
                        return "{$hours}س {$minutes}د";
                    })
                    ->alignCenter(),
                ...SharedTableColumns::blame(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ]);
    }
}
