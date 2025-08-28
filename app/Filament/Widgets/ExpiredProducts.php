<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ExpiredProducts extends TableWidget
{
    protected static ?string $heading = 'المنتجات منتهية الصلاحية';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => Product::query()
                ->whereNotNull('expiry_date')
                ->where(function ($q) {
                    $q->whereDate('expiry_date', '<', now())
                        ->orWhereBetween('expiry_date', [now(), now()->addMonth()]);
                })
                ->orderBy('expiry_date', 'asc') // soonest first
            )
            ->columns([
                TextColumn::make('name')
                    ->label('المنتج'),
                TextColumn::make('type')
                    ->label('النوع')
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'medicine' => 'دواء',
                        'equipment' => 'معدات',
                        'service' => 'خدمة',
                        'other' => 'أخرى',
                        default => $state
                    })
                    ->color(fn($state) => match ($state) {
                        'medicine' => 'info',
                        'equipment' => 'warning',
                        'service' => 'success',
                        'other' => 'danger',
                    })
                    ->badge(),
                TextColumn::make('expiry_date')
                    ->label('تاريخ الصلاحية')
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('تعديل')
                    ->icon('heroicon-o-pencil')
                    ->url(fn(Product $record): string => route('filament.admin.resources.products.edit', $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
