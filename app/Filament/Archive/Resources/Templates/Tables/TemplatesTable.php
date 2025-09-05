<?php

namespace App\Filament\Archive\Resources\Templates\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('إسم القالب')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('greetings')
                    ->label('التحية')
                    ->sortable()
                    ->limit(50)
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('closing')
                    ->label('الخاتمة')
                    ->sortable()
                    ->limit(50)
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('commissioner')
                    ->label('المفوض')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('role')
                    ->label('الصفة')
                    ->sortable()
                    ->alignCenter()
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
