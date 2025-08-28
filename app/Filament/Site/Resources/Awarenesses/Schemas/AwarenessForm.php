<?php

namespace App\Filament\Site\Resources\Awarenesses\Schemas;

use App\Filament\Forms\Components\BooleanField;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AwarenessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('العنوان')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                RichEditor::make('description')
                    ->label('المحتوى')
                    ->columnSpanFull(),
                BooleanField::make('active'),
                Repeater::make('attachments')
                    ->label('المرفقات')
                    ->defaultItems(1)
                    ->addActionLabel('إضافة مرفق')
                    ->reorderable()
                    ->collapsible()
                    ->collapsed(false)
                    ->columnSpanFull()
                    ->columns(3)
                    ->table([
                        Repeater\TableColumn::make('العنوان')
                            ->width('33.33%'),
                        Repeater\TableColumn::make('المحتوى')
                            ->width('33.33%'),
                        Repeater\TableColumn::make('الصورة')
                            ->width('33.33%'),
                    ])
                    ->schema([
                        TextInput::make('title')
                            ->label('عنوان المرفق'),
                        TextInput::make('content')
                            ->label('محتوى المرفق'),
                        FileUpload::make('image')
                            ->label('صورة المرفق')
                            ->avatar()
                            ->alignCenter()
                            ->image()
                            ->imageEditor()
                            ->directory('awareness')
                            ->disk('public'),
                    ]),
            ]);
    }
}
