<?php

namespace App\Filament\Resources\Centers\Tables;

use App\Support\SharedTableColumns;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CentersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('الإسم')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('الهاتف')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('city')
                    ->label('المدينة')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('address')
                    ->label('العنوان')
                    ->alignCenter()
                    ->limit(50)
                    ->searchable(),
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
