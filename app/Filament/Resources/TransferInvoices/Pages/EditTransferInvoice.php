<?php

namespace App\Filament\Resources\TransferInvoices\Pages;

use App\Filament\Resources\TransferInvoices\TransferInvoiceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransferInvoice extends EditRecord
{
    protected static string $resource = TransferInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
