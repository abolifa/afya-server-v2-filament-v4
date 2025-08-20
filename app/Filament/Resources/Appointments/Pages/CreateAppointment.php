<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Helpers\CommonHelpers;
use App\Models\Center;
use App\Models\Order;
use App\Models\OrderItem;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;


    public function mutateFormDataBeforeCreate(array $data): array
    {
        $selectedCenter = Center::find($data['center_id'] ?? null);
        $selectedDate = $data['date'] ?? null;
        $selectedTime = $data['time'] ?? null;

        $check = CommonHelpers::checkCenterSchedule($selectedCenter, $selectedDate, $selectedTime);

        if ($check) {
            return $data;
        }

        Notification::make('center-unavailable')
            ->title('تحذير')
            ->body('المركز غير متاح في هذا الوقت. تم إنشاء الموعد على أي حال، ولكن يرجى مراجعة المركز للتأكيد.')
            ->warning()
            ->id($selectedCenter)
            ->send();

        return $data;
    }


    protected function afterCreate(): void
    {
        $appointment = $this->record;
        $data = $this->form->getState();
        if ($data['ordered']) {
            $order = Order::create([
                'center_id' => $appointment->center_id,
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
            ]);
            foreach ($data['order']['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }
    }
}
