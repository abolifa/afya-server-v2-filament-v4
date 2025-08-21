<?php

namespace App\Filament\Resources\TransferInvoices\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransferInvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('fromCenter.name')
                    ->label('المصدر')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('toCenter.name')
                    ->label('الوجهة')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->sortable()
                    ->searchable()
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'قيد الإنتظار',
                        'confirmed' => 'مؤكد',
                        'cancelled' => 'ملغي',
                        default => 'غير معروف',
                    })->badge()
                    ->color(fn($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    }),
                TextColumn::make('items')
                    ->label('الأصناف')
                    ->alignCenter()
                    ->getStateUsing(fn($record) => ($record->items ?? collect())->sum('quantity'))
                    ->badge()
                    ->color('primary'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    Action::make('confirm')
                        ->label('تأكيد')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn($record) => $record->status === 'pending')
                        ->action(fn($record) => $record->update(['status' => 'confirmed'])),
                    Action::make('cancel')
                        ->label('إلغاء')
                        ->icon('fas-xmark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn($record) => $record->status === 'pending')
                        ->action(fn($record) => $record->update(['status' => 'cancelled'])),
                ]),
                EditAction::make(),
            ]);
    }
}
