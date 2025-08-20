<?php

namespace App\Filament\Resources\Invoices\Schemas;

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
                        ->default(auth()->user()->center_id ?? null)
                        ->relationship('center', 'name')
                        ->searchable()
                        ->preload()
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
