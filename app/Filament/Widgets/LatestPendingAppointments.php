<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestPendingAppointments extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->heading('أحدث المواعيد المعلقة')
            ->emptyStateHeading('لا توجد مواعيد معلقة')
            ->emptyStateIcon('fas-calendar-alt')
            ->query(
                Appointment::with(['patient', 'doctor', 'center', 'order'])
                    ->where('status', 'pending')
                    ->latest()
                    ->limit(5),
            )
            ->recordUrl(fn($record) => route('filament.admin.resources.appointments.view', $record->id))
            ->headerActions([
                Action::make('viewAll')
                    ->label('عرض الكل')
                    ->url(route('filament.admin.resources.appointments.index'))
                    ->icon('fas-list')
                    ->button()
                    ->size('sm')
                    ->color('secondary'),
            ])
            ->columns([
                TextColumn::make('patient.name')
                    ->label('المريض'),
                TextColumn::make('center.name')
                    ->label('المركز')
                    ->alignCenter()
                    ->tooltip(function ($state, $record) {
                        $patientCenterId = $record->patient?->center_id;
                        $appointmentCenterId = $record->center_id;
                        if (!$patientCenterId) {
                            return null;
                        }
                        return $patientCenterId !== $appointmentCenterId
                            ? 'خارج مركز المريض'
                            : null;
                    })
                    ->color(function ($state, $record) {
                        $patientCenterId = $record->patient?->center_id;
                        $appointmentCenterId = $record->center_id;
                        if (!$patientCenterId) {
                            return null;
                        }
                        return $patientCenterId !== $appointmentCenterId
                            ? 'danger'
                            : null;
                    }),
                TextColumn::make('doctor.name')
                    ->label('الطبيب')
                    ->alignCenter(),
                TextColumn::make('date')
                    ->label('التاريخ')
                    ->date('d/m/Y')
                    ->alignCenter(),
                TextColumn::make('time')
                    ->label('الوقت')
                    ->time('h:i A')
                    ->alignCenter(),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'cancelled' => 'ملغي',
                        'completed' => 'مكتمل',
                        default => 'غير معروف',
                    })
                    ->badge()
                    ->color(fn($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'completed' => 'info',
                        default => 'secondary',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('confirm')
                        ->label('تأكيد')
                        ->color('success')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->visible(fn($record) => $record->status === 'pending')
                        ->action(fn($record) => $record->update(['status' => 'confirmed'])),

                    Action::make('cancel')
                        ->label('إلغاء')
                        ->color('danger')
                        ->icon('heroicon-o-x-mark')
                        ->requiresConfirmation()
                        ->action(fn($record) => $record->update(['status' => 'cancelled'])),

                    Action::make('reschedule')
                        ->label('إعادة جدولة')
                        ->color('warning')
                        ->icon('heroicon-o-calendar')
                        ->schema([
                            DatePicker::make('date')
                                ->label('التاريخ')
                                ->required(),
                            TimePicker::make('time')
                                ->label('الوقت')
                                ->required(),
                        ])
                        ->action(function ($record, array $data) {
                            $record->update([
                                'date' => $data['date'],
                                'time' => $data['time'],
                                'status' => 'pending',
                            ]);
                        })
                        ->visible(fn($record) => $record->status !== 'completed'),
                    Action::make('complete')
                        ->label('اكتمال')
                        ->color('info')
                        ->icon('heroicon-o-check-circle')
                        ->visible(fn($record) => $record->update(['status' => 'completed']))
                        ->schema([
                            TimePicker::make('start_time')
                                ->label('وقت البدء')
                                ->required(),
                            TimePicker::make('end_time')
                                ->label('وقت الانتهاء')
                                ->required(),
                        ])
                        ->action(function ($record, array $data) {
                            $record->update([
                                'start_time' => $data['start_time'],
                                'end_time' => $data['end_time'],
                                'status' => 'completed',
                            ]);
                        })
                        ->visible(fn($record) => $record->status !== 'completed'),
                ])
                    ->link()
                    ->label('إجراءات'),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
