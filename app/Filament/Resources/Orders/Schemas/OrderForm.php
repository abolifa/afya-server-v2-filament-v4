<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Helpers\CommonHelpers;
use App\Models\Patient;
use App\Models\Unit;
use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

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
                        ->default(auth()->user()->center_id ?? null)
                        ->disabled(!Auth::user()->can_see_all_stock)
                        ->dehydrated()
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
                                ->width('33.33%'),
                            Repeater\TableColumn::make('وحدة القياس')
                                ->width('33.33%'),
                            Repeater\TableColumn::make('الكمية')
                                ->width('33.33%'),
                        ])
                        ->schema([
                            Select::make('product_id')
                                ->required()
                                ->relationship('product', 'name')
                                ->searchable()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->preload(),
                            Select::make('unit_id')
                                ->searchable()
                                ->options(fn() => Unit::query()
                                    ->get()
                                    ->mapWithKeys(fn($unit) => [
                                        $unit->id => "$unit->name - $unit->conversion_factor"
                                    ])
                                    ->toArray()
                                )
                                ->preload()
                                ->required(),
                            TextInput::make('quantity')
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->rule(function (Get $get) {
                                    return function (string $attribute, $value, Closure $fail) use ($get) {
                                        $qty = (int)$value;
                                        $productId = (int)$get('product_id');
                                        $unitId = (int)$get('unit_id');
                                        $centerId = (int)$get('../../center_id');
                                        if (!$qty || !$productId || !$unitId || !$centerId) {
                                            return;
                                        }
                                        $existingQty = (int)($get('original_quantity') ?? 0);
                                        $existingUnitId = (int)($get('original_unit_id') ?? 0);
                                        $ok = CommonHelpers::canTakeFromStock(
                                            productId: $productId,
                                            centerId: $centerId,
                                            requestedQty: $qty,
                                            unitId: $unitId,
                                            existingQty: $existingQty,
                                            existingUnitId: $existingUnitId
                                        );
                                        if (!$ok) {
                                            $fail("الكمية المطلوبة تتجاوز المخزون المتاح للمركز.");
                                        }
                                    };
                                }),
                        ])->columns()
                        ->minItems(1)
                        ->validationMessages([
                            'min' => 'يجب إضافة صنف واحد على الأقل',
                        ]),
                ])->columnSpanFull(),
            ]);
    }
}
