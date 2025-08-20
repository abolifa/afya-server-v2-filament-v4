<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\Order;
use App\Models\OrderItem;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function fillForm(): void
    {
        $record = $this->record;

        $data = $record->attributesToArray();

        $data['ordered'] = $record->order !== null;

        $data['order_items'] = $record->order?->items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
            ];
        })->toArray() ?? [];

        $this->form->fill($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['ordered'], $data['order_items']);
        return $data;
    }

    protected function afterSave(): void
    {
        $formData = $this->form->getState();
        $record = $this->record;

        if (!empty($formData['ordered']) && !empty($formData['order_items'])) {
            $order = $record->order;

            if (!$order) {
                $order = Order::create([
                    'status' => 'pending',
                    'center_id' => $record->center_id,
                    'patient_id' => $record->patient_id,
                    'appointment_id' => $record->id,
                ]);
            } else {
                $order->update([
                    'center_id' => $record->center_id,
                    'patient_id' => $record->patient_id,
                ]);
                $order->items()->delete();
            }

            foreach ($formData['order_items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        } elseif ($record->order) {
            $record->order->items()->delete();
            $record->order->delete();
        }
    }
}
