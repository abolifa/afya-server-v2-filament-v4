<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

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
