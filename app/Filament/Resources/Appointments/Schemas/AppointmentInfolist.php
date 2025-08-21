<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Filament\Infolists\Components\TableEntry;
use Filament\Actions\Action;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppointmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextEntry::make('center.name')
                        ->label('المركز'),
                    TextEntry::make('patient.name')
                        ->label('المريض'),
                    TextEntry::make('doctor.name')
                        ->label('الطبيب'),
                    TextEntry::make('device.name')
                        ->label('الجهاز'),
                    TextEntry::make('date')
                        ->label('التاريخ')
                        ->date('d/m/Y'),
                    TextEntry::make('time')
                        ->label('الوقت')
                        ->time('h:i A'),
                    TextEntry::make('status')
                        ->label('الحالة')
                        ->formatStateUsing(fn($state) => match ($state) {
                            'pending' => 'قيد الانتظار',
                            'confirmed' => 'مؤكد',
                            'cancelled' => 'ملغي',
                            'completed' => 'مكتمل',
                            default => $state,
                        })->badge()
                        ->color(fn($state) => match ($state) {
                            'pending' => 'warning',
                            'confirmed' => 'success',
                            'cancelled' => 'danger',
                            'completed' => 'primary',
                            default => 'secondary',
                        }),
                    IconEntry::make('intended')
                        ->label('الحضور')
                        ->boolean(),
                    TextEntry::make('created_at')
                        ->label('تاريخ الإنشاء'),
                    TextEntry::make('start_time')
                        ->label('وقت البدء')
                        ->placeholder('غير محدد')
                        ->time('h:i A'),
                    TextEntry::make('end_time')
                        ->label('وقت الانتهاء')
                        ->placeholder('غير محدد')
                        ->time('h:i A'),
                    TextEntry::make('notes')
                        ->label('الملاحظات')
                        ->limit(50)
                        ->placeholder('لا توجد ملاحظات')
                        ->color('danger')
                        ->formatStateUsing(fn($state) => $state ? strip_tags($state) : 'لا توجد ملاحظات'),
                ])->columns(3)
                    ->columnSpanFull(),

                Section::make([
                    TableEntry::make('order.items')
                        ->label('عناصر الطلب'),
                ])->columnSpanFull()
                    ->headerActions([
                        Action::make('view_order')
                            ->label('مشاهدة الطلب')
                            ->icon('fas-arrow-right')
                            ->button()
                            ->color('secondary')
                            ->size('sm')
                            ->visible(fn($record) => optional($record->order)->exists)
                            ->url(fn($record) => optional($record->order)->id
                                ? route('filament.admin.resources.orders.view', ['record' => $record->order->id])
                                : null),
                    ]),
            ]);
    }
}
