<?php

namespace App\Filament\Resources\Units;

use App\Filament\Resources\Units\Pages\ListUnits;
use App\Filament\Resources\Units\Pages\ViewUnit;
use App\Filament\Resources\Units\Schemas\UnitForm;
use App\Filament\Resources\Units\Schemas\UnitInfolist;
use App\Filament\Resources\Units\Tables\UnitsTable;
use App\Models\Unit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class UnitResource extends Resource
{
    protected static ?string $model = Unit::class;

    protected static string|BackedEnum|null $navigationIcon = 'fas-ruler';

    protected static ?string $label = 'وحدة قياس';
    protected static ?string $pluralLabel = 'وحدات القياس';

    protected static string|null|UnitEnum $navigationGroup = 'إدارة المخزون';

    public static function form(Schema $schema): Schema
    {
        return UnitForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UnitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UnitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUnits::route('/'),
//            'create' => CreateUnit::route('/create'),
            'view' => ViewUnit::route('/{record}'),
//            'edit' => EditUnit::route('/{record}/edit'),
        ];
    }
}
