<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('اسم الصنف')
                    ->required(),
                Select::make('type')
                    ->label('نوع الصنف')
                    ->options([
                        'medicine' => 'دواء',
                        'equipment' => 'معدات',
                        'service' => 'خدمة',
                        'other' => 'أخرى',
                    ])
                    ->native(false)
                    ->required(),
                FileUpload::make('image')
                    ->label('صورة الصنف')
                    ->directory('products')
                    ->visibility('public')
                    ->disk('public')
                    ->columnSpanFull()
                    ->image(),
                DatePicker::make('expiry_date')
                    ->label('تاريخ انتهاء الصلاحية')
                    ->nullable(),
                TextInput::make('alert_threshold')
                    ->label('حد التنبيه')
                    ->required()
                    ->numeric()
                    ->default(10),
                RichEditor::make('description')
                    ->label('الوصف')
                    ->columnSpanFull(),
                RichEditor::make('usage')
                    ->label('طريقة الاستخدام')
                    ->columnSpanFull(),

            ]);
    }
}
