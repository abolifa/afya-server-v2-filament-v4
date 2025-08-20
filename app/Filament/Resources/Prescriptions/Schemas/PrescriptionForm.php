<?php

namespace App\Filament\Resources\Prescriptions\Schemas;

use App\Filament\Forms\Components\BooleanField;
use App\Models\Appointment;
use App\Models\Patient;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class PrescriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Select::make('patient_id')
                        ->label('المريض')
                        ->required()
                        ->reactive()
                        ->searchable()
                        ->preload()
                        ->afterStateUpdated(function (Set $set, $state) {
                            $patient = Patient::find($state);
                            $set('center_id', $patient?->center_id);
                        })
                        ->relationship('patient', 'name'),
                    Select::make('center_id')
                        ->label('المركز')
                        ->relationship('center', 'name')
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->afterStateUpdated(fn(Set $set) => $set('doctor_id', null))
                        ->required(),
                    Select::make('doctor_id')
                        ->label('الطبيب')
                        ->required()
                        ->relationship(
                            name: 'doctor',
                            titleAttribute: 'name',
                            modifyQueryUsing: function (Builder $query, Get $get) {
                                return $query
                                    ->where('doctor', true)
                                    ->when($get('center_id'), fn(Builder $q, $centerId) => $q->where('center_id', $centerId));
                            }
                        )
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->disabled(fn(Get $get) => blank($get('center_id')))
                        ->hint(fn(Get $get) => blank($get('center_id')) ? 'اختر المركز أولاً' : null),
                    DatePicker::make('date')
                        ->label('تاريخ الوصفة')
                        ->suffixAction(
                            Action::make('today')
                                ->icon('heroicon-o-calendar')
                                ->action(fn(Set $set) => $set('date', now()->format('Y-m-d')))
                                ->tooltip('تعيين التاريخ إلى اليوم')
                        )
                        ->required(),
                    Select::make('appointment_id')
                        ->label('الموعد')
                        ->searchable()
                        ->reactive()
                        ->disabled(fn(Get $get) => blank($get('patient_id')))
                        ->options(fn(Get $get) => $get('patient_id')
                            ? Appointment::where('patient_id', $get('patient_id'))
                                ->orderByDesc('date')
                                ->orderByDesc('time')
                                ->pluck('date', 'id')
                            : collect()
                        )
                        ->hint(fn(Get $get) => blank($get('patient_id')) ? 'اختر المريض أولاً' : null),
                    BooleanField::make('dispensed')
                        ->label('تم الصرف')
                        ->dehydrated()
                        ->disabled(fn($record) => $record->dispensed == true)
                        ->default(false),
                    Textarea::make('notes')
                        ->label('ملاحظات')
                        ->columnSpanFull(),
                ])->columns()
                    ->columnSpanFull(),

                Section::make([
                    Repeater::make('items')
                        ->label('الأصناف')
                        ->relationship('items')
                        ->schema([
                            Select::make('product_id')
                                ->label('الدواء')
                                ->relationship('product', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('frequency')
                                ->label('التكرار')
                                ->options([
                                    'daily' => 'يومي',
                                    'weekly' => 'أسبوعي',
                                    'monthly' => 'شهري',
                                ])
                                ->native(false)
                                ->default('daily')
                                ->required(),

                            TextInput::make('interval')
                                ->label('الفاصل الزمني (الفترة)')
                                ->numeric()
                                ->default(1)
                                ->required(),

                            TextInput::make('times_per_interval')
                                ->label('عدد الجرعات في الفترة')
                                ->numeric()
                                ->default(1)
                                ->required(),
                            TextInput::make('dose_amount')
                                ->label('مقدار الجرعة')
                                ->numeric()
                                ->required(),
                            Select::make('dose_unit')
                                ->label('وحدة الجرعة')
                                ->options([
                                    'mg' => 'ملغم',
                                    'ml' => 'ملل',
                                    'tablet' => 'قرص',
                                    'capsule' => 'كبسولة',
                                    'unit' => 'وحدة',
                                    'drop' => 'قطرة',
                                ])
                                ->native(false)
                                ->required(),
                            DatePicker::make('start_date')
                                ->label('بداية المدة')
                                ->displayFormat('d/m/Y')
                                ->default(now())
                                ->required(),
                            DatePicker::make('end_date')
                                ->label('نهاية المدة')
                                ->displayFormat('d/m/Y')
                                ->nullable(),
                        ])->columns(),
                ])->columnSpanFull(),
            ]);
    }
}
