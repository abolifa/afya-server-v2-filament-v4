<?php

namespace App\Filament\Resources\Suppliers\Tables;

use App\Support\SharedTableColumns;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('الإسم')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('الهاتف')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('البريد')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('address')
                    ->label('العنوان')
                    ->sortable()
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
                RestoreAction::make(),
                EditAction::make(),
                ForceDeleteAction::make(),
            ]);
    }
}
