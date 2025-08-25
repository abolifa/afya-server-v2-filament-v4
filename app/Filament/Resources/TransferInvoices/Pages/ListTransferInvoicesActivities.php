<?php

namespace App\Filament\Resources\TransferInvoices\Pages;

use App\Filament\Resources\TransferInvoices\TransferInvoiceResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListTransferInvoicesActivities extends ListActivities
{
    protected static string $resource = TransferInvoiceResource::class;
}
