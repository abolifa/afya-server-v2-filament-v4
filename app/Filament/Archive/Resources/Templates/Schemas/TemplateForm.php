<?php

namespace App\Filament\Archive\Resources\Templates\Schemas;

use App\Filament\Forms\Components\BooleanField;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class TemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('إسم القالب')
                    ->required(),
                TextInput::make('greetings')
                    ->columnSpanFull()
                    ->label('التحية'),
                TextInput::make('closing')
                    ->columnSpanFull()
                    ->label('الخاتمة'),
                TextInput::make('commissioner')
                    ->label('المفوض'),
                TextInput::make('role')
                    ->label('المنصب'),
                Group::make([
                    BooleanField::make('show_commissioner')
                        ->default(false)
                        ->label('إظهار المفوض'),
                    BooleanField::make('show_role')
                        ->default(false)
                        ->label('إظهار المنصب'),
                    BooleanField::make('show_signature')
                        ->default(false)
                        ->label('إظهار التوقيع'),
                    BooleanField::make('show_stamp')
                        ->default(false)
                        ->label('إظهار الختم'),
                ])->columns(4)
                    ->columnSpanFull(),
                FileUpload::make('letterhead')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('letterhead')
                    ->columnSpanFull()
                    ->label('الرسالة المعنونة'),
                FileUpload::make('signature')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('letterhead')
                    ->label('التوقيع'),
                FileUpload::make('stamp')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->visibility('public')
                    ->directory('letterhead')
                    ->label('الختم'),
            ])->columns();
    }
}
