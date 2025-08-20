<?php

namespace App\Filament\Resources\Prescriptions\Pages;

use App\Filament\Resources\Prescriptions\PrescriptionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrescription extends CreateRecord
{
    protected static string $resource = PrescriptionResource::class;


    public function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['dispensed'])) {
            $data['dispensed_at'] = now();
        } else {
            $data['dispensed_at'] = null;
        }

        return $data;
    }
}
