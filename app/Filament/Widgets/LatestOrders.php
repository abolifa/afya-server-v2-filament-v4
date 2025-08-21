<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestOrders extends TableWidget
{

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('أحدث الطلبات المعلقة')
            ->emptyStateHeading('لا توجد طلبات معلقة')
            ->emptyStateIcon('fas-box-open')
            ->query(
                Order::query()
                    ->with('items')
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->limit(5),
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('patient.name')
                    ->label('المريض'),
                TextColumn::make('center.name')
                    ->label('المركز')
                    ->alignCenter()
                    ->placeholder('-'),
                TextColumn::make('status')
                    ->label('الحالة')
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
            ->headerActions([
                Action::make('viewAll')
                    ->label('عرض الكل')
                    ->url(route('filament.admin.resources.orders.index'))
                    ->icon('fas-list')
                    ->button()
                    ->size('sm')
                    ->color('secondary'),
            ])
            ->recordActions([
                ActionGroup::make([
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
                ])
                    ->link()
                    ->label('إجراءات'),
            ])
            ->recordUrl(fn($record) => route('filament.admin.resources.orders.view', $record->id))
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
