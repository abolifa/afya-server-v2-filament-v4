<?php

namespace App\Filament\Resources\Suppliers\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
