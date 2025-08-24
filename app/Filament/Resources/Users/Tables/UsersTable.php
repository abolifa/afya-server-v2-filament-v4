<?php

namespace App\Filament\Resources\Users\Tables;

use App\Support\SharedTableColumns;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
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
                TextColumn::make('email')
                    ->label('البريد')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('الهاتف')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('center.name')
                    ->label('المركز')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                IconColumn::make('active')
                    ->label('نشط')
                    ->alignCenter()
                    ->sortable()
                    ->boolean(),
                IconColumn::make('doctor')
                    ->label('طبيب')
                    ->sortable()
                    ->alignCenter()
                    ->boolean(),
                IconColumn::make('can_see_all_stock')
                    ->label('كل المخزون')
                    ->alignCenter()
                    ->boolean(),
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
