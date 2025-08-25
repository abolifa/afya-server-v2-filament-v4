<?php

namespace App\Filament\Resources\TransferInvoices\Pages;

use App\Filament\Resources\TransferInvoices\TransferInvoiceResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListTransferInvoicesActivities extends ListActivities
{
    protected static string $resource = TransferInvoiceResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        $user = auth()->user();
        if (!$user || !$user->whiteList || !$user->whiteList->can_see_activities) {
            abort(403, 'لا تملك صلاحية الوصول إلى هذه الصفحة.');
        }
    }
}
