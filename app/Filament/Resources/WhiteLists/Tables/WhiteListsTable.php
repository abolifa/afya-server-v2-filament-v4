<?php

namespace App\Filament\Resources\WhiteLists\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WhiteListsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('can_see_activities')
                    ->label('سجل النشاطات')
                    ->alignCenter()
                    ->boolean(),
                IconColumn::make('can_see_all_stock')
                    ->label('كل المخزون')
                    ->alignCenter()
                    ->boolean(),
                IconColumn::make('can_select_all_centers')
                    ->label('كل المراكز')
                    ->alignCenter()
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
