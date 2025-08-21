<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('type')
                    ->label('النوع')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'in' => 'وارد',
                        'out' => 'صادر',
                        'transfer' => 'تحويل',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        'transfer' => 'warning',
                        default => 'secondary',
                    })->sortable()
                    ->searchable(),
                TextColumn::make('actor.name')
                    ->label('بواسطة')
                    ->alignCenter()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject_type')
                    ->label('نوع الطلب')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'App\Models\Invoice' => 'مشتريات',
                        'App\Models\TransferInvoice' => 'تحويل',
                        'App\Models\Order' => 'طلب مريض',
                    })
                    ->alignCenter()
                    ->color(fn($state) => match ($state) {
                        'App\Models\Invoice' => 'success',
                        'App\Models\TransferInvoice' => 'warning',
                        'App\Models\Order' => 'dander',
                        default => 'secondary',
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('subject_id')
                    ->label('رقم الطلب')
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('fromCenter.name')
                    ->label('من المركز')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('toCenter.name')
                    ->label('إلى المركز')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('patient.name')
                    ->label('إلى المريض')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->label('المورد')
                    ->alignCenter()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('التاريخ')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
