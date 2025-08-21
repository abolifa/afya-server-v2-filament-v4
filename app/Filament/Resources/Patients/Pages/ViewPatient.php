<?php

namespace App\Filament\Resources\Patients\Pages;

use App\Filament\Resources\Patients\PatientResource;
use App\Models\Patient;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPatient extends ViewRecord
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')
                ->label('كشف مريض')
                ->url(fn(Patient $record): string => '/admin/patients/' . $record->id . '/overview')
                ->icon('fas-list'),
            EditAction::make(),
        ];
    }
}
