<?php

namespace App\Filament\Resources\TransferInvoices\Schemas;

use App\Helpers\CommonHelpers;
use App\Models\Unit;
use Closure;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class TransferInvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Select::make('from_center_id')
                        ->label('المصدر')
                        ->searchable()
                        ->preload()
                        ->relationship('fromCenter', 'name')
                        ->default(auth()->user()->center_id ?? null)
                        ->disabled(!Auth::user()->can_see_all_stock)
                        ->dehydrated()
                        ->reactive()
                        ->required(),
                    Select::make('to_center_id')
                        ->relationship('toCenter', 'name')
                        ->label('الوجهة')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->disableOptionWhen(fn($value, Get $get) => $value == $get('from_center_id')),
                    Select::make('status')
                        ->label('الحالة')
                        ->native(false)
                        ->options([
                            'pending' => 'قيد المعالجة',
                            'confirmed' => 'مؤكد',
                            'cancelled' => 'ملغي',
                        ])
                        ->default('pending')
                        ->disabledOn('create')
                        ->required(),
                ])->columnSpanFull()
                    ->columns(3),

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
                                        $centerId = (int)$get('../../from_center_id');
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
