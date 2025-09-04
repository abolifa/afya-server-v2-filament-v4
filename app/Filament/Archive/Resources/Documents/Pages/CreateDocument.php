<?php

namespace App\Filament\Archive\Resources\Documents\Pages;

use App\Filament\Archive\Resources\Documents\DocumentResource;
use App\Services\IncomingAttachmentService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;
        try {
            if ($record->document_path) {
                Storage::disk('public')->delete($record->document_path);
            }
            $service = app(IncomingAttachmentService::class);
            $merged = $service->buildMergedPdf(
                $record->attachments ?? [],
                $record->id,
                outputFolder: 'documents',
            );
            $record->update(['document_path' => $merged]);
            Notification::make()
                ->title('تم تجهيز ملف المرفقات')
                ->body($merged ? 'تم إنشاء ملف PDF مدمج.' : 'لا توجد مرفقات صالحة للدمج.')
                ->success()
                ->send();
        } catch (Throwable $e) {
            Notification::make()
                ->title('فشل دمج المرفقات')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
