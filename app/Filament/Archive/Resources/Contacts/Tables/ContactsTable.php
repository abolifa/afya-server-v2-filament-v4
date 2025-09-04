<?php

namespace App\Filament\Archive\Resources\Contacts\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('النوع')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'individual' => 'فرد',
                        'company' => 'شركة',
                        'organization' => 'مؤسسة',
                    })->sortable(),
                TextColumn::make('name')
                    ->label('الإسم')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('البريد')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('الهاتف')
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
