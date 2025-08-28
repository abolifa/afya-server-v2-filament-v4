<?php

namespace App\Filament\Site\Resources\Announcements\Schemas;

use App\Filament\Forms\Components\BooleanField;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AnnouncementForm
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
                RichEditor::make('content')
                    ->label('المحتوى')
                    ->columnSpanFull(),
                FileUpload::make('images')
                    ->label('الصور')
                    ->multiple()
                    ->image()
                    ->imageEditor()
                    ->directory('announcements')
                    ->disk('public')
                    ->visibility('public')
                    ->columnSpanFull(),
                BooleanField::make('active'),
            ]);
    }
}
