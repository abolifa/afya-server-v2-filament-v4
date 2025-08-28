<?php

namespace App\Filament\Site\Resources\Sliders\Schemas;

use App\Filament\Forms\Components\BooleanField;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class SliderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ToggleButtons::make('type')
                    ->label('نوع الشريحة')
                    ->options([
                        'image' => 'صورة',
                        'url' => 'رابط',
                        'post' => 'منشور',
                    ])
                    ->default('image')
                    ->inline()
                    ->grouped()
                    ->reactive()
                    ->required(),
                FileUpload::make('image')
                    ->label('صورة الشريحة')
                    ->disk('public')
                    ->directory('sliders')
                    ->visibility('public')
                    ->imageEditor()
                    ->image()
                    ->columnSpanFull()
                    ->required(),
                Select::make('post_id')
                    ->label('المنشور المرتبط')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->disabled(fn($get) => $get('type') !== 'post')
                    ->required(fn($get) => $get('type') === 'post')
                    ->relationship('post', 'title'),
                TextInput::make('url')
                    ->label('رابط الشريحة')
                    ->disabled(fn($get) => $get('type') !== 'url')
                    ->required(fn($get) => $get('type') === 'url')
                    ->url()
                    ->placeholder('https://example.com')
                    ->maxLength(255),
                BooleanField::make('active')
                    ->label('نشط'),
            ]);
    }
}
