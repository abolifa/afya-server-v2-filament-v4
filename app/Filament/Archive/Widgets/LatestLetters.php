<?php

namespace App\Filament\Archive\Widgets;

use App\Models\Letter;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestLetters extends TableWidget
{
    protected static ?string $heading = 'أحدث الخطابات';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => Letter::query()->latest()->orderByDesc('created_at'))
            ->emptyStateHeading('لا توجد خطابات')
            ->emptyStateIcon('fas-file-alt')
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
                TextColumn::make('fromCenter.name')
                    ->label('من')
                    ->alignCenter()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('to')
                    ->label('إلى')
                    ->alignCenter()
                    ->sortable()
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
                    ->alignCenter()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
