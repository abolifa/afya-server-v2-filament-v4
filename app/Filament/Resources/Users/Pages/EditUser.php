<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function beforeSave(): void
    {
        if (
            $this->record->hasRole(config('filament-shield.super_admin.name', 'super_admin'))
            && !auth()->user()->hasRole(config('filament-shield.super_admin.name', 'super_admin'))
        ) {
            Notification::make()
                ->title('خطأ')
                ->body('لا يمكنك تعديل المستخدم.')
                ->danger()
                ->send();

            $this->halt();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
