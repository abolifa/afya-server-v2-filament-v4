<?php

namespace App\Filament\Archive\Resources\Letters\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LettersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('issue_number')
                    ->label('إشاري')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('issue_date')
                    ->label('تاريخ الإصدار')
                    ->sortable()
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('نوع الخطاب')
                    ->sortable()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'internal' => 'داخلي',
                        'external' => 'خارجي',
                        default => $state,
                    })
                    ->alignCenter()
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'internal' => 'info',
                        'external' => 'warning',
                        default => 'secondary',
                    }),
                TextColumn::make('to')
                    ->label('إلى')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('template.name')
                    ->label('القالب')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('followUp.issue_number')
                    ->label('تابع')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('subject')
                    ->label('الموضوع')
                    ->limit(50)
                    ->sortable()
                    ->searchable()
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
