<?php

namespace App\Filament\Resources\TransferInvoices\Pages;

use App\Filament\Resources\TransferInvoices\TransferInvoiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransferInvoices extends ListRecords
{
    protected static string $resource = TransferInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
