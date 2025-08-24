<?php

namespace App\Filament\Resources\TransferInvoices\Pages;

use App\Filament\Resources\TransferInvoices\TransferInvoiceResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransferInvoice extends ViewRecord
{
    protected static string $resource = TransferInvoiceResource::class;

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
