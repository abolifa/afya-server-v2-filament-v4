<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Filament\Forms\Components\BooleanField;
use App\Models\Device;
use App\Models\Patient;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Select::make('patient_id')
                        ->label('المريض')
                        ->relationship('patient', 'name')
                        ->searchable()
                        ->preload()
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, ?string $state) {
                            $patient = $state ? Patient::find($state) : null;
                            $set('center_id', $patient?->center_id);
                            $set('device_id', $patient?->device_id);
                            $set('doctor_id', null);
                        })
                        ->required(),
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
                    Select::make('device_id')
                        ->label('الجهاز')
                        ->searchable()
                        ->preload()
                        ->options(fn() => Device::query()
                            ->get()
                            ->mapWithKeys(function ($device) {
                                $label = $device->name;
                                if (!$device->active) {
                                    $label .= ' (جهاز موقوف)';
                                }
                                return [$device->id => $label];
                            }))
                        ->disableOptionWhen(fn($value) => !Device::find($value)?->active),
                ]),

                Section::make([
                    DatePicker::make('date')
                        ->label('التاريخ')
                        ->default(Carbon::now())
                        ->displayFormat('d/m/Y')
                        ->required(),
                    TimePicker::make('time')
                        ->label('الوقت')
                        ->time('h:i A')
                        ->default(Carbon::now()->format('h:i A'))
                        ->required(),
                    Select::make('status')
                        ->label('حالة الموعد')
                        ->options([
                            'pending' => 'قيد الانتظار',
                            'confirmed' => 'مؤكد',
                            'cancelled' => 'ملغي',
                            'completed' => 'مكتمل',
                        ])
                        ->default('pending')
                        ->disabledOn('create')
                        ->required(),
                    Group::make([
                        BooleanField::make('intended')
                            ->label('تم الحضور')
                            ->default(false)
                            ->disabledOn('create')
                            ->required(),
                        BooleanField::make('ordered')
                            ->label('إنشاء طلب')
                            ->reactive()
                            ->default(false),
                    ])->columns()
                ]),


                Section::make([
                    Repeater::make('order_items')
                        ->label('عناصر الطلب')
                        ->table([
                            Repeater\TableColumn::make('العنصر')
                                ->width('50%')
                                ->alignCenter(),
                            Repeater\TableColumn::make('الكمية')
                                ->width('50%')
                                ->alignCenter(),
                        ])
                        ->schema([
                            Select::make('product_id')
                                ->relationship('order.items.product', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('quantity')
                                ->numeric()
                                ->default(1)
                                ->minValue(1)
                                ->required(),
                        ])
                        ->columns()
                        ->columnSpanFull(),
                ])->columns()
                    ->hidden(fn(Get $get) => !$get('ordered'))
                    ->columnSpanFull(),


                Section::make([
                    RichEditor::make('notes')
                        ->label('ملاحظات')
                        ->columnSpanFull(),
                ])
                    ->columnSpanFull(),
            ]);
    }
}
