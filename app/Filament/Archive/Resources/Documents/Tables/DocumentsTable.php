<?php

namespace App\Filament\Archive\Resources\Documents\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('issue_number')
                    ->label('إشاري')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->label('نوع المستند')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'letter' => 'خطاب',
                        'archive' => 'أرشيف',
                        'report' => 'تقرير',
                        'other' => 'أخرى',
                    })
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('fromCenter.name')
                    ->label('من المركز')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('fromContact.name')
                    ->label('من الجهة')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('issue_date')
                    ->label('التاريخ')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('attachments')
                    ->label('مرفقات')
                    ->getStateUsing(fn($record) => count($record->attachments ?? []))
                    ->alignCenter()
                    ->badge()
                    ->color(fn($state) => $state > 0 ? 'success' : 'danger'),
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
