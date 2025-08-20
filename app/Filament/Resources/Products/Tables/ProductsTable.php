<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('الصورة')
                    ->placeholder('-')
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('الإسم')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('type')
                    ->label('نوع الصنف')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'medicine' => 'دواء',
                        'equipment' => 'معدات',
                        'service' => 'خدمة',
                        'other' => 'أخرى',
                    })
                    ->alignCenter()
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'medicine' => 'primary',
                        'equipment' => 'secondary',
                        'service' => 'success',
                        'other' => 'warning',
                    }),
                TextColumn::make('expiry_date')
                    ->label('الصلاحية')
                    ->date('d/m/Y')
                    ->placeholder('-')
                    ->alignCenter()
                    ->color(function ($state) {
                        if (!$state) {
                            return null;
                        } elseif ($state < now()) {
                            return 'danger';
                        } elseif ($state < now()->addDays(30)) {
                            return 'warning';
                        } elseif ($state < now()->addDays(90)) {
                            return 'warning';
                        }
                        return 'success';
                    })
                    ->sortable(),
                TextColumn::make('alert_threshold')
                    ->label('حد التنبيه')
                    ->placeholder('-')
                    ->alignCenter()
                    ->sortable(),
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
