<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Support\SharedTableColumns;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('center.name')
                    ->label('المركز')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->label('المورد')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->sortable()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'قيد الإنتظار',
                        'confirmed' => 'مؤكد',
                        'cancelled' => 'ملغي',
                        default => 'غير معروف',
                    })->badge()
                    ->alignCenter()
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
                ...SharedTableColumns::blame(),
            ])
            ->filters([
                TrashedFilter::make(),
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
                RestoreAction::make(),
                EditAction::make(),
                ForceDeleteAction::make(),
            ]);
    }
}
