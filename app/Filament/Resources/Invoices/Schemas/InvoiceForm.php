<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Unit;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    Select::make('center_id')
                        ->label('المركز')
                        ->relationship('center', 'name')
                        ->searchable()
                        ->preload()
                        ->dehydrated()
                        ->required(),
                    Select::make('supplier_id')
                        ->label('المورد')
                        ->searchable()
                        ->preload()
                        ->relationship('supplier', 'name'),
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
                ])->columns(3)
                    ->columnSpanFull(),

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
//                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
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
                                ->minValue(1),
                        ])->columns()
                        ->minItems(1)
                        ->validationMessages([
                            'min' => 'يجب إضافة صنف واحد على الأقل',
                        ]),
                ])->columnSpanFull(),

            ]);
    }
}
