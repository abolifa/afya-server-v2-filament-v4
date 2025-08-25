<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Support\SharedTableColumns;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('patient.name')
                    ->label('المريض')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('center.name')
                    ->label('المركز')
                    ->sortable()
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
                    })
                    ->searchable(),
                TextColumn::make('doctor.name')
                    ->label('الطبيب')
                    ->sortable()
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('date')
                    ->label('التاريخ')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('time')
                    ->label('الوقت')
                    ->time('h:i A')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('الحالة')
                    ->sortable()
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
                    })
                    ->searchable(),
                IconColumn::make('intended')
                    ->label('حضور')
                    ->alignCenter()
                    ->boolean(),
                TextColumn::make('device.name')
                    ->label('الجهاز')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                ...SharedTableColumns::blame(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'cancelled' => 'ملغي',
                        'completed' => 'مكتمل',
                    ]),
                SelectFilter::make('intended')
                    ->label('حضور')
                    ->options([
                        true => 'حضور',
                        false => 'غياب',
                    ])->default('true'),
                SelectFilter::make('center_id')
                    ->label('المركز')
                    ->relationship('center', 'name')
                    ->searchable(),
                SelectFilter::make('doctor_id')
                    ->label('الطبيب')
                    ->relationship('doctor', 'name')
                    ->searchable(),
                SelectFilter::make('device_id')
                    ->label('الجهاز')
                    ->relationship('device', 'name')
                    ->searchable(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make('view'),

                    // Confirm
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


                    Action::make('activities')
                        ->url(fn($record) => AppointmentResource::getUrl('activities', ['record' => $record]))
                        ->label('الأنشطة')
                        ->visible(Auth::user()->whiteList && Auth::user()->whiteList->can_see_activities)
                        ->icon('lucide-activity-square'),
                ]),

                RestoreAction::make(),
                ForceDeleteAction::make(),

                EditAction::make(),
            ]);
    }
}
