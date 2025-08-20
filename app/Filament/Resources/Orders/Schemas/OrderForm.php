<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Patient;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class OrderForm
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
                        ->reactive()
                        ->searchable()
                        ->preload()
                        ->relationship('center', 'name')
                        ->helperText(function ($state, Get $get) {
                            $patient = Patient::find($get('patient_id'));
                            if (!$patient) {
                                return null;
                            }
                            if (!$state) {
                                return null;
                            }
                            if ($state != $patient->center_id) {
                                return "المركز المحدد لا يتوافق مع مركز المريض";
                            } else {
                                return null;
                            }
                        })
                        ->required(),
                    Select::make('status')
                        ->label('الحالة')
                        ->options([
                            'pending' => 'قيد الإنتظار',
                            'confirmed' => 'مؤكد',
                            'cancelled' => 'ملغي'
                        ])
                        ->native(false)
                        ->default('pending')
                        ->disabledOn('create')
                        ->required(),
                ])->columns(3)->columnSpanFull(),

                Section::make([
                    Repeater::make('items')
                        ->label('الأصناف')
                        ->relationship('items')
                        ->table([
                            Repeater\TableColumn::make('الصنف')
                                ->width('50%'),
                            Repeater\TableColumn::make('الكمية')
                                ->width('50%'),
                        ])
                        ->schema([
                            Select::make('product_id')
                                ->required()
                                ->relationship('product', 'name')
                                ->searchable()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->preload(),
                            TextInput::make('quantity')
                                ->required()
                                ->default(1)
                                ->minValue(1)
                                ->maxValue(1000)
                                ->numeric(),
                        ])->columns()
                        ->minItems(1)
                        ->validationMessages([
                            'min' => 'يجب إضافة صنف واحد على الأقل',
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}
