<?php

namespace App\Filament\Resources\TransferInvoices\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

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
