<?php

namespace App\Filament\Resources\Units\Tables;

use App\Support\SharedTableColumns;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('الإسم')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('symbol')
                    ->label('الرمز')
                    ->badge()
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('conversion_factor')
                    ->label('معدل التحويل')
                    ->alignCenter()
                    ->numeric()
                    ->sortable(),
                ...SharedTableColumns::blame(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                RestoreAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
            ]);
    }
}
