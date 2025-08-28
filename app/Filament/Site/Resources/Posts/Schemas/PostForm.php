<?php

namespace App\Filament\Site\Resources\Posts\Schemas;

use App\Filament\Forms\Components\BooleanField;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('العنوان')
                    ->required()
                    ->reactive()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, Set $set, $state) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $set('slug', Str::slug($state));
                    })
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('الرابط')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(ignoreRecord: true),
                RichEditor::make('content')
                    ->columnSpanFull(),
                TagsInput::make('tags')
                    ->helperText('#ليبيا #تونس #مصر')
                    ->label('الوسوم'),
                BooleanField::make('active'),
                FileUpload::make('main_image')
                    ->label('الصورة الرئيسية')
                    ->directory('posts')
                    ->disk('public')
                    ->imageEditor()
                    ->columnSpanFull()
                    ->image(),
                FileUpload::make('other_images')
                    ->label('صور إضافية')
                    ->directory('posts')
                    ->disk('public')
                    ->multiple()
                    ->columnSpanFull()
                    ->imageEditor()
                    ->image(),
            ]);
    }
}
