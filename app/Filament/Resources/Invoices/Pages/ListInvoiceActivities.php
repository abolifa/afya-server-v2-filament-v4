<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use pxlrbt\FilamentActivityLog\Pages\ListActivities;

class ListInvoiceActivities extends ListActivities
{
    protected static string $resource = InvoiceResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        $user = auth()->user();
        if (!$user || !$user->whiteList || !$user->whiteList->can_see_activities) {
            abort(403, 'لا تملك صلاحية الوصول إلى هذه الصفحة.');
        }
    }
}
