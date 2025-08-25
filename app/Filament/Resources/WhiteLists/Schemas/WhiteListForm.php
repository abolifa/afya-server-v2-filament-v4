<?php

namespace App\Filament\Resources\WhiteLists\Schemas;

use App\Filament\Forms\Components\BooleanField;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class WhiteListForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('المستخدم')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->required(),
                BooleanField::make('can_see_activities')
                    ->label('يستطيع رؤية سجل النشاطات')
                    ->default(false)
                    ->required(),
                BooleanField::make('can_see_all_stock')
                    ->label('يستطيع رؤية جميع المخازن')
                    ->default(false)
                    ->required(),
                BooleanField::make('can_select_all_centers')
                    ->label('يستطيع اختيار جميع المراكز')
                    ->default(false)
                    ->required(),
            ])->columns(3);
    }
}
