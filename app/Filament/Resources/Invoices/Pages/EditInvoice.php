<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Resources\Invoices\InvoiceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }


    protected function beforeSave(): void
    {
        if ($this->record->status === 'confirmed') {
            Notification::make()
                ->title('لا يمكن تعديل الطلبات المؤكدة')
                ->body('تم تأكيد هذا الطلب ولا يُسمح بتعديله.')
                ->danger()
                ->seconds(8)
                ->send();
            $this->halt();
        }
    }
}
