<?php

namespace App\Filament\Site\Resources\Structures\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StructureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->label('الهيكل الأب')
                    ->native(false)
                    ->relationship('parent', 'name'),
                TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->label('النوع')
                    ->native(false)
                    ->required()
                    ->options([
                        'authority' => 'هيئة',
                        'directorate' => 'إدارة',
                        'department' => 'قسم',
                        'division' => 'شعبة',
                        'unit' => 'وحدة',
                        'center' => 'مركز',
                        'office' => 'مكتب',
                    ]),
                TextInput::make('phone')
                    ->label('الهاتف')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('العنوان')
                    ->maxLength(255),
                Repeater::make('employees')
                    ->label('الأعضاء')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('position')
                            ->label('الوظيفة')
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('الهاتف')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->maxLength(255),
                    ]),
            ]);
    }
}
