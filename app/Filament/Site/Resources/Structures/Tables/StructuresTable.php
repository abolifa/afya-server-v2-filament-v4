<?php

namespace App\Filament\Site\Resources\Structures\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StructuresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),
                TextColumn::make('parent.name')
                    ->label('الهيكل الأب')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('النوع')
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'authority' => 'هيئة',
                        'directorate' => 'إدارة',
                        'department' => 'قسم',
                        'division' => 'شعبة',
                        'unit' => 'وحدة',
                        'center' => 'مركز',
                        'office' => 'مكتب',
                    })
                    ->badge()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('الهاتف')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('البريد')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('address')
                    ->label('العنوان')
                    ->alignCenter()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
