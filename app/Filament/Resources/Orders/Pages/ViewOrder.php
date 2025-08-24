<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
            EditAction::make(),
        ];
    }
}
